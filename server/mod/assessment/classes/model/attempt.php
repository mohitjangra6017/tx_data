<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\model;

use coding_exception;
use dml_exception;
use Exception;
use mod_assessment\entity\SimpleDBO;
use mod_assessment\processor\role_user_processor;
use moodle_database;

defined('MOODLE_INTERNAL') || die();

class attempt extends SimpleDBO
{

    public const TABLE = 'assessment_attempt';

    public const STATUS_COMPLETE = 50;
    public const STATUS_FAILED = 30;
    public const STATUS_INPROGRESS = 25;
    public const STATUS_NOTSTARTED = 10;
    public const STATUS_TERMINATED = 86;

    /** @var int */
    public int $attempt;

    /** @var int[] */
    public array $evaluatorids = []; // Users assigned to attempt in evaluator role

    /** @var int[] */
    public array $reviewerids = []; // Users assigned to attempt in reviewer role

    /** @var float|null */
    public ?float $grade;

    /** @var int|null */
    public ?int $reviewedbyid; // id of who reviewed the attempt, NOT necessarily an assigned reviewer role user

    /** @var int */
    public int $status;

    /** @var int|null */
    public ?int $timecompleted;

    /** @var int|null */
    public ?int $timereviewed;

    /** @var int|null */
    public ?int $timestarted;

    /** @var int */
    public int $userid;

    /** @var int */
    public int $versionid;

    /** @var int */
    public int $timearchived;

    public function get_id(): ?int
    {
        return $this->id;
    }

    public function get_userid(): int
    {
        return $this->userid;
    }

    public function get_versionid(): int
    {
        return $this->versionid;
    }

    public function get_timearchived(): int
    {
        return $this->timearchived;
    }

    public function is_archived(): bool
    {
        return !empty($this->timearchived);
    }

    /**
     *
     * @return array
     */
    protected function get_stored_evaluator_ids(): array
    {
        global $DB;
        $evaluatorids = [];
        $params = ['attemptid' => $this->id, 'role' => role::EVALUATOR];
        if ($ids = $DB->get_records_menu('assessment_attempt_assignments', $params, 'id ASC', 'id, userid')) {
            $evaluatorids = array_values($ids);
        }
        return $evaluatorids;
    }

    /**
     *
     * @return array
     */
    protected function get_stored_reviewer_ids(): array
    {
        global $DB;
        $reviewerids = [];
        $params = ['attemptid' => $this->id, 'role' => role::REVIEWER];
        if ($ids = $DB->get_records_menu('assessment_attempt_assignments', $params, 'id ASC', 'id, userid')) {
            $reviewerids = array_values($ids);
        }
        return $reviewerids;
    }

    /**
     * Set parameters in the object
     *
     * @param $data
     * @param bool $usesetter Flags data to use model setters
     * @return self
     */
    public function set_data($data, $usesetter = false): attempt
    {
        parent::set_data($data, $usesetter);

        $evaluatorids = $this->get_stored_evaluator_ids();
        $this->set_evaluatorids($evaluatorids);

        $reviewerids = $this->get_stored_reviewer_ids();
        $this->set_reviewerids($reviewerids);

        return $this;
    }

    /**
     * Completely remove the object from the db
     *
     * @return bool
     * @global moodle_database $DB
     */
    public function delete(): bool
    {
        global $DB;

        $DB->delete_records('assessment_attempt_assignments', ['attemptid' => $this->id]); // delete assignments first

        return $DB->delete_records(static::TABLE, ['id' => $this->id]);
    }

    /**
     * @param int $assessmentid
     * @param int $userid
     * @return int
     * @global moodle_database $DB
     */
    protected function get_attempt_new_index(int $assessmentid, int $userid): int
    {
        global $DB;

        //@todo: do we care about archived attempts being included in the count?
        // it has to, if we're going to have unique attempts
        $sql = "SELECT COUNT(*) + 1
                  FROM {assessment_attempt} attempt
                  JOIN {assessment_version} version ON version.id = attempt.versionid
                 WHERE version.assessmentid = :assessmentid
                       AND attempt.userid = :userid";

        return $DB->count_records_sql($sql, ['assessmentid' => $assessmentid, 'userid' => $userid]);
    }

    /**
     * @param int $limit
     * @return object[] {user}
     * @throws dml_exception
     * @global moodle_database $DB
     */
    public function get_evaluators($limit = 0): array
    {
        global $DB;
        if ($this->is_preview()) {
            $evaluator = static::get_preview_user();
            return [$evaluator->id => $evaluator];
        }
        return $DB->get_records_list('user', 'id', $this->evaluatorids, 'firstname ASC, lastname ASC', '*', 0, $limit);
    }

    public function get_evaluators_count(): int
    {
        return count($this->evaluatorids);
    }

    /**
     * @param int $limit
     * @return object[] {user}
     * @throws dml_exception
     * @global moodle_database $DB
     */
    public function get_reviewers($limit = 0): array
    {
        global $DB;
        if ($this->is_preview()) {
            $reviewer = static::get_preview_user();
            return [$reviewer->id => $reviewer];
        }

        return $DB->get_records_list('user', 'id', $this->reviewerids, 'firstname ASC, lastname ASC', '*', 0, $limit);
    }

    public function get_reviewers_count(): int
    {
        return count($this->reviewerids);
    }

    public static function get_preview_user(): object
    {
        $user = (object)get_all_user_name_fields();
        $user->id = 0;
        $user->firstname = get_string('preview', 'assessment');
        $user->lastname = get_string('user');
        return $user;
    }

    /**
     * @return int
     */
    public function get_status(): int
    {
        return $this->status;
    }

    public function get_user()
    {
        global $DB;
        static $user;

        if ($this->is_preview()) {
            return static::get_preview_user();
        }

        if (!isset($user) && isset($this->userid)) {
            $user = $DB->get_record('user', ['id' => $this->userid]);
        }
        return $user;
    }

    /**
     * @param version $version
     * @param int $userid
     * @return self fetch latest, non-archived, incomplete attempt for specified version and user.
     * @global moodle_database $DB
     */
    public static function instance_active(version $version, int $userid): attempt
    {
        global $DB;

        list($statussql, $statusparams) = $DB->get_in_or_equal(
            [self::STATUS_NOTSTARTED, self::STATUS_INPROGRESS],
            SQL_PARAMS_NAMED
        );

        // Ignore archived attempts.
        $record = $DB->get_record_select(
            self::TABLE,
            "status {$statussql} AND userid = :userid AND versionid = :versionid AND timearchived = 0 ORDER BY timestarted DESC",
            $statusparams + ['userid' => $userid, 'versionid' => $version->id],
            '*',
            MUST_EXIST
        );

        $attempt = new self();
        $attempt->set_data($record);
        return $attempt;
    }

    /**
     * @param version $version
     * @return self
     */
    public static function instance_preview(version $version): attempt
    {
        $attempt = new self();
        $attempt->set_evaluatorids(self::get_preview_user()->id);
        $attempt->set_status(self::STATUS_NOTSTARTED);
        $attempt->set_userid(self::get_preview_user()->id);
        $attempt->set_versionid($version->id);
        return $attempt;
    }

    /**
     * @param int $userid
     * @param int $assessmentid
     * @param int[] $evaluatorids
     * @param int[] $reviewerids
     * @param bool $archived Whether to return only archived attempts (otherwise returns only non-archived).
     * @return self[]
     * @global moodle_database $DB
     */
    public static function instances_for_user(int $userid, int $assessmentid, $evaluatorids = null, $reviewerids = null, $archived = false): array
    {
        global $DB;

        $extra_joins = [];
        $extra_where = [];
        $extra_params = [];
        if (is_array($evaluatorids)) {
            $extra_joins[] = "JOIN {assessment_attempt_assignments} assigneval ON assigneval.attemptid = attempt.id AND assigneval.role = :evaluatorrole";
            list($in_sql, $in_params) = $DB->get_in_or_equal($evaluatorids, SQL_PARAMS_NAMED, 'param', true, null);
            $extra_where[] = "(assigneval.userid {$in_sql})";
            $extra_params['evaluatorrole'] = role::EVALUATOR;
            $extra_params = array_merge($extra_params, $in_params);
        }
        if (is_array($reviewerids)) {
            $extra_joins[] = "JOIN {assessment_attempt_assignments} assignrev ON assignrev.attemptid = attempt.id AND assignrev.role = :reviewerrole";
            list($in_sql, $in_params) = $DB->get_in_or_equal($reviewerids, SQL_PARAMS_NAMED, 'param', true, null);
            $extra_where[] = "(assignrev.userid {$in_sql})";
            $extra_params['reviewerrole'] = role::REVIEWER;
            $extra_params = array_merge($extra_params, $in_params);
        }

        if ($archived) {
            $extra_where[] = "(attempt.timearchived > 0)";
        } else {
            $extra_where[] = "(attempt.timearchived = 0)";
        }

        $extra_joins_sql = join(PHP_EOL, $extra_joins);
        $extra_where_sql = (empty($extra_where) ? '' : 'AND ' . join(PHP_EOL . ' AND ', $extra_where));
        $sql = "SELECT DISTINCT attempt.*
                  FROM {assessment_attempt} attempt
                  JOIN {assessment_version} version ON version.id = attempt.versionid
                  {$extra_joins_sql}
                 WHERE version.assessmentid = :assessmentid
                       AND attempt.userid = :userid {$extra_where_sql}";
        $params = ['assessmentid' => $assessmentid, 'userid' => $userid];
        $params = array_merge($params, $extra_params);

        // Sort the results.
        $sql .= " ORDER BY attempt.attempt";

        $records = $DB->get_records_sql($sql, $params);
        $instances = [];
        foreach ($records as $record) {
            $instance = new self();
            $instance->set_data($record);
            $instances[$instance->id] = $instance;
        }

        return $instances;
    }

    /**
     * @return bool
     */
    public function is_complete(): bool
    {
        return !empty($this->timecompleted);
    }

    // IOTIS2 Start

    /**
     * @return bool
     */
    public function is_single_evaluator(): bool
    {
        $version = version::instance(['id' => $this->versionid], MUST_EXIST);
        return $version->singleevaluator;
    }

    /**
     *
     * @param bool $any If true, where there are multiple evaluators return true if any of them pass the rules
     * for the evaluator role assignment (otherwise ALL must pass the rules)
     * @param int|int[] $evaluatoridstocheck If specified, check these against he rules (otherwise use object ids in $this->evaluatorids)
     * @return bool
     * @throws Exception
     */
    public function is_evaluator_valid($evaluatoridstocheck = null, $any = true): bool
    {
        return $this->is_user_role_valid(new role(role::EVALUATOR), $any, $evaluatoridstocheck);
    }

    /**
     * @return bool Whether at least one evaluator is currently assigned to this user attempt.
     */
    public function is_evaluator_assigned(): bool
    {

        return ($this->get_evaluators_count() > 0);
    }

    /**
     * @return bool
     */
    public function is_single_reviewer(): bool
    {
        $version = version::instance(['id' => $this->versionid], MUST_EXIST);
        return $version->singlereviewer;
    }

    /**
     *
     * @param bool $any If true, where there are multiple reviewers return true if any of them pass the rules
     * for the reviewer role assignment (otherwise ALL must pass the rules)
     * @param int|int[] $revieweridstocheck If specified, check these against he rules (otherwise use object ids in $this->reviewerids)
     * @return bool
     * @throws Exception
     */
    public function is_reviewer_valid($revieweridstocheck = null, $any = true): bool
    {
        return $this->is_user_role_valid(new role(role::REVIEWER), $any, $revieweridstocheck);
    }

    /**
     * @return bool Whether at least one reviewer is currently assigned to this user attempt.
     */
    public function is_reviewer_assigned(): bool
    {

        return ($this->get_reviewers_count() > 0);
    }

    /**
     * @param role $role See \mod_assessment\helper\role constants
     * @param bool $any If true, where there are multiple users in the specified role return true if any of them pass the rules
     * for that role assignment (otherwise ALL must pass the rules)
     * @param null $useridstocheck If specified, check these against he rules (otherwise use object ids in $this->userids)
     * @return bool
     */
    public function is_user_role_valid(role $role, $any = true, $useridstocheck = null): bool
    {
        if (!is_null($useridstocheck)) {
            $useridstocheck = (is_scalar($useridstocheck) ? [$useridstocheck] : $useridstocheck);
        }

        if (empty($useridstocheck)) {
            switch ($role->value()) {
                case role::EVALUATOR:
                    $useridstocheck = $this->evaluatorids;
                    break;
                case role::REVIEWER:
                    $useridstocheck = $this->reviewerids;
                    break;
            }
        }

        if (empty($useridstocheck)) {
            return false;
        }

        $version = version::instance(['id' => $this->versionid], MUST_EXIST);
        $processor = new role_user_processor($version, $role);
        $validusers = $processor->get_valid_role_users($this);
        $validusers = array_map(
            function ($item) {
                return $item->id;
            },
            $validusers
        );

        foreach ($useridstocheck as $usercheck) {
            $uservalid = in_array($usercheck, $validusers);

            if ($uservalid && $any) {
                return true;
            }

            if (!$uservalid && !$any) {
                return false;
            }
        }

        return true;
    }

    // IOTIS2 End

    public function is_preview(): bool
    {
        return $this->userid == 0;
    }

    public function mark_reviewed(): attempt
    {
        global $USER;

        $this->set_reviewedbyid($USER->id);
        $this->set_timereviewed(time());
        return $this;
    }

    /**
     * @return bool
     */
    public function needs_review(): bool
    {
        // Include failed and passed (complete).
        return (in_array($this->status, [self::STATUS_FAILED, self::STATUS_COMPLETE]) && empty($this->timereviewed));
    }

    public function presave(&$isupdate = false)
    {
        if (!$isupdate && empty($this->attempt)) {
            $version = version::instance(['id' => $this->versionid], MUST_EXIST);
            $this->attempt = $this->get_attempt_new_index($version->assessmentid, $this->userid);
        }
        if (!$isupdate && empty($this->timearchived)) {
            $this->timearchived = 0;
        }
    }

    public function is_active(): bool
    {
        return ($this->status <= self::STATUS_INPROGRESS);
    }

    /**
     * Save role assignments for user/attempt
     * {@inheritDoc}
     *
     * @see \mod_assessment\entity\SimpleDBO::postsave()
     */
    public function postsave($isupdate = false)
    {
        global $DB;

        $assignment_recs = [role::EVALUATOR => [], role::REVIEWER => []];

        // Create lists of role users to store
        if (!empty($this->evaluatorids)) {
            foreach ($this->evaluatorids as $userid) {
                $assignment_recs[role::EVALUATOR][$userid] = $userid;
            }
        }
        if (!empty($this->reviewerids)) {
            foreach ($this->reviewerids as $userid) {
                $assignment_recs[role::REVIEWER][$userid] = $userid;
            }
        }

        foreach ($assignment_recs as $role => $userids) {
            // Delete any stored role user assignments which are not currently set in the role user lists to store
            $stored_role_user_ids = [];
            if ($role == role::EVALUATOR) {
                $stored_role_user_ids = $this->get_stored_evaluator_ids();
            } elseif ($role == role::EVALUATOR) {
                $stored_role_user_ids = $this->get_stored_reviewer_ids();
            }
            foreach ($stored_role_user_ids as $userid) {
                if (!isset($assignment_recs[$role][$userid])) {
                    $params = ['attemptid' => $this->id, 'role' => $role, 'userid' => $userid];
                    $DB->delete_records('assessment_attempt_assignments', $params);
                }
            }
            unset($stored_role_user_ids);

            // Insert/update any new role user assignments (notification are handled by scheduled task)
            foreach ($userids as $userid) {
                $params = ['attemptid' => $this->id, 'role' => $role, 'userid' => $userid];
                $update = false;
                if ($rec = $DB->get_record('assessment_attempt_assignments', $params)) {
                    $update = true;
                } else {
                    $rec = (object)$params;
                    $rec->timecreated = time();
                }
                $rec->timemodified = time();

                if ($update) {
                    $DB->update_record('assessment_attempt_assignments', $rec);
                } else {
                    $rec->id = $DB->insert_record('assessment_attempt_assignments', $rec);
                }
            }
        }

        unset($assignment_recs);
    }

    /**
     *
     * @param $role
     * @param null $userids
     * @return bool
     * @throws coding_exception
     * @throws dml_exception
     */
    public function set_role_users_notified($role, $userids = null): bool
    {
        global $DB;

        $userids = (is_scalar($userids) ? [$userids] : $userids);

        $params = ['timenotified' => time(), 'attemptid' => $this->id, 'role' => $role];

        $extra_where = "AND 1=1";
        if (!empty($userids)) {
            list($in_sql, $in_params) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);
            $extra_where = "AND userid {$in_sql}";
            $params = array_merge($params, $in_params);
        }

        $sql = "UPDATE
                    {assessment_attempt_assignments} SET timenotified = :timenotified
                WHERE
                    attemptid = :attemptid AND role = :role {$extra_where}
                ";

        return $DB->execute($sql, $params);
    }

    /**
     * @param null $evaluatorids
     * @return bool
     * @throws coding_exception
     * @throws dml_exception
     */
    public function set_evaluators_notified($evaluatorids = null): bool
    {
        return $this->set_role_users_notified(role::EVALUATOR, $evaluatorids);
    }

    /**
     * @param null $reviewerids
     * @return bool
     * @throws coding_exception
     * @throws dml_exception
     */
    public function set_reviewers_notified($reviewerids = null): bool
    {
        return $this->set_role_users_notified(role::REVIEWER, $reviewerids);
    }

    /**
     * @param int $attempt
     * @return self
     */
    public function set_attempt(int $attempt): attempt
    {
        $this->attempt = $attempt;
        return $this;
    }

    /**
     * @param int[]|int $evaluatorids
     * @return self
     */
    public function set_evaluatorids($evaluatorids): attempt
    {
        if (is_scalar($evaluatorids)) {
            $evaluatorids = [$evaluatorids];
        }
        $this->evaluatorids = $evaluatorids;
        return $this;
    }

    /**
     * @param int[]|int $reviewerids
     * @return self
     */
    public function set_reviewerids($reviewerids): attempt
    {
        if (is_scalar($reviewerids)) {
            $reviewerids = [$reviewerids];
        }
        $this->reviewerids = $reviewerids;
        return $this;
    }

    /**
     * @param int[]|int $reviewedbyid id of who reviewed the attempt, NOT necessarily an assigned reviewer role user.
     * @return self
     */
    public function set_reviewedbyid($reviewedbyid): attempt
    {
        $this->reviewedbyid = $reviewedbyid;
        return $this;
    }

    /**
     * @param float $grade
     * @return self
     */
    public function set_grade(float $grade): attempt
    {
        $this->grade = $grade;
        return $this;
    }

    /**
     * @param int $status
     * @return self
     */
    public function set_status(int $status): attempt
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param int $timecompleted
     * @return self
     */
    public function set_timecompleted(int $timecompleted): attempt
    {
        $this->timecompleted = $timecompleted;
        return $this;
    }

    /**
     * @param int $timearchived
     * @return self
     */
    public function set_timearchived(int $timearchived): attempt
    {
        $this->timearchived = $timearchived;
        return $this;
    }

    /**
     * @param int $timereviewed
     * @return self
     */
    public function set_timereviewed(int $timereviewed): attempt
    {
        $this->timereviewed = $timereviewed;
        return $this;
    }

    /**
     * @param int $timestarted
     * @return self
     */
    public function set_timestarted(int $timestarted): attempt
    {
        $this->timestarted = $timestarted;
        return $this;
    }

    /**
     * @param int $userid
     * @return self
     */
    public function set_userid(int $userid): attempt
    {
        $this->userid = $userid;
        return $this;
    }

    /**
     * @param int $versionid
     * @return self
     */
    public function set_versionid(int $versionid): attempt
    {
        $this->versionid = $versionid;
        return $this;
    }
}
