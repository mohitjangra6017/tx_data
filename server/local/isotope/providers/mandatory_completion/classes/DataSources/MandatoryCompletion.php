<?php
/**
 * @package isotopeprovider_mandatory_completion
 * @author Ben Lobo <ben.lobo@kineo.com>
 * @copyright City & Guilds Kineo 2019
 * @license http://www.kineo.com
 */

namespace isotopeprovider_mandatory_completion\DataSources;

use local_isotope\Data\SourceInterface;
use totara_core\totara_user;
use totara_job\job_assignment;

/**
 * Data source class to obtain mandatory completion data for users within an
 * organisation.
 */
class MandatoryCompletion implements SourceInterface
{
    const STATUS_INCOMPLETE = 0;
    const STATUS_COMPLETE = 1;
    const STATUS_OVERDUE = 2;

    protected $orgId;
    protected $targetOrgChildren;
    protected $filters;

    public function __construct($orgId, $targetOrgChildren)
    {
        $this->orgId = $orgId;
        $this->targetOrgChildren = $targetOrgChildren;
        $this->filters = [];
    }

    /**
     * Get the data provided by this data source.
     *
     * @return mixed
     */
    public function getData()
    {
        global $FULLME;

        $items = [];

        foreach ($this->targetOrgChildren as $targetOrgChild) {

            if ($targetOrgChild->parentid != $this->orgId) {
                continue;
            }

            list($completedPercent, $notCompletedPercent, $overduePercent) = array_values($this->getSingleOrgPercentData($targetOrgChild->id));

            $items[] = [
                'name' => $targetOrgChild->fullname,
                'completed' => $completedPercent,
                'notcompleted' => $notCompletedPercent,
                'overdue' => $overduePercent,
                'link' => (new \moodle_url($FULLME, ['orgid' => $targetOrgChild->id]))->out(false),
                'blocks' => true,
            ];
        }

        return $items;
    }

    /**
     * Get aggregate completion data for a single organisation. Returns an array
     * containing the percentage of users in the organisation who are complete,
     * not complete and overdue for their mandatory learning.
     * 
     * @param int $orgId
     * @return array
     */
    public function getSingleOrgPercentData($orgId)
    {
        $completedPercent = 0;
        $notCompletedPercent = 0;
        $overduePercent = 0;
        list($completed, $notCompleted, $overdue) = array_values($this->getCompletionNumbers($orgId));
        $total = $completed + $notCompleted + $overdue;

        if ($total != 0) {
            $completedPercent = $this->getPercent($completed, $total);
            $notCompletedPercent = $this->getPercent($notCompleted, $total);
            $overduePercent = $this->getPercent($overdue, $total);
        }

        return [$completedPercent, $notCompletedPercent, $overduePercent];
    }

    /**
     * Get completion data for a single user. Returns an array containing
     * the percentage of the user's mandatory learning that is either complete,
     * not complete or overdue.
     * 
     * @param int $orgId
     * @param int $userId
     * @return array
     */
    public function getSingleUserPercentData($orgId, $userId)
    {
        $completedPercent = 0;
        $notCompletedPercent = 0;
        $overduePercent = 0;
        list($completed, $notCompleted, $overdue) = array_values($this->getCompletionNumbers($orgId, $userId));
        $total = $completed + $notCompleted + $overdue;

        if ($total != 0) {
            $completedPercent = $this->getPercent($completed, $total);
            $notCompletedPercent = $this->getPercent($notCompleted, $total);
            $overduePercent = $this->getPercent($overdue, $total);
        }

        return [$completedPercent, $notCompletedPercent, $overduePercent];
    }

    /**
     * Gets individual completion data for all users in a single organisation.
     * Returns an 2-dimensional array. Each item in the array contains the
     * percentages for an individual user's mandatory learning that is either complete,
     * not complete or overdue.
     * 
     * @global object $SESSION
     * @global object $FULLME
     * @param int $orgId
     * @return array
     */
    public function getOrgUserPercentData($orgId)
    {
        global $SESSION;

        $items = [];

        foreach ($SESSION->compliance_data[$orgId]['users'] as $userId => $user) {
            $completed = $user['completed'];
            $notCompleted = $user['notcompleted'];
            $overdue = $user['overdue'];
            $total = $completed + $notCompleted + $overdue;
            $completedPercent = 0;
            $notCompletedPercent = 0;
            if ($total != 0) {
                $completedPercent = $this->getPercent($completed, $total);
                $notCompletedPercent = $this->getPercent($notCompleted, $total);
                $overduePercent = $this->getPercent($overdue, $total);
            }
            $user = totara_user::get_user($userId);
            $items[] = [
                'name' => fullname($user),
                'completed' => is_nan($completedPercent) ? '0' : $completedPercent,
                'notcompleted' => is_nan($notCompletedPercent) ? '0' : $notCompletedPercent,
                'overdue' => is_nan($overduePercent) ? '0' : $overduePercent,
                'link' => $this->getUserLevelUrl($userId)->out(false),
            ];
        }
        return $items;
    }

    /**
     * Calculate one value as a percentage of another
     * 
     * @param int $proportion
     * @param int $total
     * @return int
     */
    private function getPercent($proportion, $total, $precision = 0)
    {
        return round(($proportion * 100) / $total, $precision);
    }

    /**
     * Determine the URL for the user level link based on whether or not the
     * current user is the direct manager of the user whose data is being displayed.
     * 
     * @param int $userId
     * @return \moodle_url
     */
    public function getUserLevelUrl($userId)
    {
        global $USER;

        $isManager = job_assignment::is_managing($USER->id, $userId);
        if ($isManager) {
            $url = (new \moodle_url('/totara/program/required.php', ['userid' => $userId]));
        } else {
            $url = (new \moodle_url('/local/isotope/providers/mandatory_completion/required.php', ['userid' => $userId]));
        }
        return $url;
    }

    /**
     * Set the value of the specified filter.
     *
     * @param $filterName
     * @param $value
     * @return mixed
     */
    public function setFilter($filterName, $value)
    {
        $this->filters[$filterName] = $value;
        return $this;
    }

    /**
     * Retrieve mandatory completion data from a session cache.
     * 
     * @global object $SESSION
     * @param int $orgId
     * @param int $userId
     * @return array
     */
    public function getCompletionNumbers($orgId, $userId = 0)
    {
        global $SESSION;

        if (!$userId) {
            return [
                'completed' => $SESSION->compliance_data[$orgId]['compliance']['completed'],
                'notcompleted' => $SESSION->compliance_data[$orgId]['compliance']['notcompleted'],
                'overdue' => $SESSION->compliance_data[$orgId]['compliance']['overdue']
            ];
        } else {
            return [
                'completed' => $SESSION->compliance_data[$orgId]['users'][$userId]['completed'],
                'notcompleted' => $SESSION->compliance_data[$orgId]['users'][$userId]['notcompleted'],
                'overdue' => $SESSION->compliance_data[$orgId]['users'][$userId]['overdue']
            ];
        }
    }

    /**
     * Calculate and return an array containing the mandatory completion data
     * for a specific organisation (including all child orgs).
     * 
     * @global object $DB
     * @param int $orgId
     * @return array
     */
    public function getCompletionData($orgId)
    {
        global $DB, $CFG;

        require_once($CFG->dirroot .'/totara/core/totara.php');

        $data = [];
        $complete = self::STATUS_COMPLETE;
        $overdue = self::STATUS_OVERDUE;
        $incomplete = self::STATUS_INCOMPLETE;
        $progComplete = STATUS_PROGRAM_COMPLETE;
        $certComplete = CERTIFSTATUS_COMPLETED;
        $certExpired = CERTIFSTATUS_EXPIRED;
        $now = time();

        $organisation = new \organisation();
        $organisationChildren = $organisation->get_item_descendants($orgId);

        foreach ($organisationChildren as $key => $organisationChild) {

            $data[$organisationChild->id] = [];
            $data[$organisationChild->id]['organisation'] = $organisationChild;
            $data[$organisationChild->id]['users'] = [];
            $data[$organisationChild->id]['compliance'] = [
                'completed' => 0,
                'notcompleted' => 0,
                'overdue' => 0,
            ];

            $progUserAssignments = [];
            $params = [];

            $idFieldName = $DB->sql_concat('ja.id', "'-'", 'pua.id');
            $orgPathLike1 = $DB->sql_like('o.path', ':orgpath1');
            $params['orgpath1'] = "%/{$organisationChild->id}/%";
            $orgPathLike2 = $DB->sql_like('o.path', ':orgpath2');
            $params['orgpath2'] = "%/{$organisationChild->id}";

            $sql = "SELECT
                        DISTINCT($idFieldName) AS id,
                        ja.id AS jaid,
                        pua.id AS puaid,
                        pua.userid,
                        pua.programid AS programid,
                        {$organisationChild->id} AS toplevelorgid,
                        o.id AS userorgid,
                        o.path AS userorgpath,
                        CASE WHEN p.certifid IS NULL
                        THEN CASE WHEN pc.status = {$progComplete}
                             THEN {$complete}
                             WHEN pc.timedue BETWEEN 0 AND {$now}
                             THEN {$overdue} 
                             ELSE {$incomplete}
                             END
                        ELSE CASE WHEN cc.status = {$certComplete}
                             THEN {$complete} 
                             WHEN cc.status = {$certExpired}
                             THEN {$overdue} 
                             ELSE {$incomplete}
                             END
                        END AS status
                    FROM {prog_user_assignment} pua
                    JOIN {user} u ON u.id = pua.userid
                    JOIN {prog} p ON p.id = pua.programid
                    JOIN {job_assignment} ja ON pua.userid = ja.userid
                    JOIN {org} o ON o.id = ja.organisationid
                    LEFT JOIN {prog_completion} pc ON (pc.programid = pua.programid AND pc.userid = pua.userid AND pc.coursesetid = 0)
                    LEFT JOIN {certif_completion} cc ON (cc.userid = pua.userid AND cc.certifid = p.certifid)
                    WHERE u.suspended = 0
                    AND u.deleted = 0
                    AND ($orgPathLike1 OR $orgPathLike2)";

            $progUserAssignments = $DB->get_records_sql($sql, $params);

            if (!is_array($progUserAssignments)) {
                continue;
            }

            foreach ($progUserAssignments as $progUserAssignment) {
                // Add viewable call to run tenant checks
                if (!totara_program_is_viewable($progUserAssignment->programid, $progUserAssignment->userid)) {
                    continue;
                }
                if (!isset($data[$organisationChild->id]['users'][$progUserAssignment->userid])) {
                    $data[$organisationChild->id]['users'][$progUserAssignment->userid] = [
                        'completed' => 0,
                        'notcompleted' => 0,
                        'overdue' => 0,
                    ];
                }
                switch ($progUserAssignment->status) {
                    case self::STATUS_COMPLETE:
                        $data[$organisationChild->id]['users'][$progUserAssignment->userid]['completed']++;
                        $data[$organisationChild->id]['compliance']['completed']++;
                        break;
                    case self::STATUS_INCOMPLETE:
                        $data[$organisationChild->id]['users'][$progUserAssignment->userid]['notcompleted']++;
                        $data[$organisationChild->id]['compliance']['notcompleted']++;
                        break;
                    case self::STATUS_OVERDUE:
                        $data[$organisationChild->id]['users'][$progUserAssignment->userid]['overdue']++;
                        $data[$organisationChild->id]['compliance']['overdue']++;
                        break;
                }
            }
        }

        return $data;
    }
}
