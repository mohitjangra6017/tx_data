<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment;

use core\event\base;
use core\event\cohort_deleted;
use core\event\cohort_member_added;
use core\event\cohort_member_removed;
use core\event\group_member_added;
use core\event\group_member_removed;
use core\event\role_assigned;
use core\event\role_unassigned;
use core\event\user_enrolment_created;
use mod_assessment\event\attempt_created;
use mod_assessment\event\stage_completed;
use mod_assessment\model\attempt;
use mod_assessment\processor\attempt_completion_processor;
use mod_assessment\processor\completion;
use mod_assessment\processor\role_assignments;
use totara_job\event\job_assignment_deleted;
use totara_job\event\job_assignment_updated;
use core\event\user_deleted;
use core\event\user_updated;
use core\event\user_loggedin;

defined('MOODLE_INTERNAL') || die();

class observer
{

    // For performance when multiple actions are observed involving same assessment(s).
    protected static $assessments_to_refresh = [];

    public static function attempt_created(attempt_created $event)
    {
        $attempt = attempt::instance(['id' => $event->get_data()['objectid']]);
        $processor = new attempt_completion_processor($attempt);
        $processor->generate_completions();
    }

    public static function stage_completed(stage_completed $event)
    {
        $data = $event->get_data();
        $attempt = model\attempt::instance(['id' => $data['other']], MUST_EXIST);

        $attmemptprocessor = new attempt_completion_processor($attempt);
        $attmemptprocessor->update_statuses();

        $processor = new completion();
        if ($processor->is_attempt_complete($attempt)) {
            $processor->complete_attempt($attempt);
        }
    }

    /**
     * Need to refresh any user role assignments involving the course the user enrolled into.
     *
     * @param user_enrolment_created $event
     */
    public static function user_enrolment_created(user_enrolment_created $event)
    {
        if ($event->courseid <> SITEID) {
            role_assignments::mark_role_assignments_dirty($event->courseid);

            processor\assessment_due_processor::update_by_course($event->courseid, $event->relateduserid);
        }
    }

    /**
     * Need to refresh any user role assignments involving assessments whose rules reference the audience deleted.
     *
     * @param cohort_deleted $event
     */
    public static function cohort_deleted(cohort_deleted $event)
    {
        $cohortid = $event->objectid;

        if (!empty($cohortid)) {
            if ($assessmentids = role_assignments::get_assessments_with_cohortid_rules($cohortid)) {
                foreach ($assessmentids as $assessmentid => $rec) {
                    if (!isset(self::$assessments_to_refresh[$assessmentid])) {
                        role_assignments::mark_role_assignments_dirty(0, $assessmentid);
                        self::$assessments_to_refresh[$assessmentid] = $assessmentid;
                    }
                }
            }
        }
    }

    /**
     * Need to refresh any user role assignments involving assessments whose rules reference the audience involved.
     *
     * @param cohort_member_added $event
     */
    public static function cohort_member_added(cohort_member_added $event)
    {
        $cohortid = $event->objectid;

        if (!empty($cohortid)) {
            if ($assessmentids = role_assignments::get_assessments_with_cohortid_rules($cohortid)) {
                foreach ($assessmentids as $assessmentid => $rec) {
                    if (!isset(self::$assessments_to_refresh[$assessmentid])) {
                        role_assignments::mark_role_assignments_dirty(0, $assessmentid);
                        self::$assessments_to_refresh[$assessmentid] = $assessmentid;
                    }
                }
            }
        }
    }

    /**
     * Need to refresh any user role assignments involving assessments whose rules reference the audience involved.
     *
     * @param cohort_member_removed $event
     */
    public static function cohort_member_removed(cohort_member_removed $event)
    {
        $cohortid = $event->objectid;

        if (!empty($cohortid)) {
            if ($assessmentids = role_assignments::get_assessments_with_cohortid_rules($cohortid)) {
                foreach ($assessmentids as $assessmentid => $rec) {
                    if (!isset(self::$assessments_to_refresh[$assessmentid])) {
                        role_assignments::mark_role_assignments_dirty(0, $assessmentid);
                        self::$assessments_to_refresh[$assessmentid] = $assessmentid;
                    }
                }
            }
        }
    }

    /**
     * Need to refresh any user role assignments involving assessments whose rules include 'roleincoursegroup' type rules.
     *
     * @param group_member_added $event
     */
    public static function group_member_added(group_member_added $event)
    {
        $groupid = $event->objectid;

        if (!empty($groupid) && $event->contextlevel == CONTEXT_COURSE && $event->contextinstanceid <> SITEID) {
            $courseid = $event->contextinstanceid;
            if ($assessmentids = role_assignments::get_assessments_with_rule_type('roleincoursegroup', $courseid)) {
                foreach ($assessmentids as $assessmentid => $rec) {
                    if (!isset(self::$assessments_to_refresh[$assessmentid])) {
                        role_assignments::mark_role_assignments_dirty(0, $assessmentid);
                        self::$assessments_to_refresh[$assessmentid] = $assessmentid;
                    }
                }
            }
        }
    }

    /**
     * Need to refresh any user role assignments involving assessments whose rules include 'roleincoursegroup' type rules.
     *
     * @param group_member_removed $event
     */
    public static function group_member_removed(group_member_removed $event)
    {
        $groupid = $event->objectid;

        if (!empty($groupid) && $event->contextlevel == CONTEXT_COURSE && $event->contextinstanceid <> SITEID) {
            $courseid = $event->contextinstanceid;
            if ($assessmentids = role_assignments::get_assessments_with_rule_type('roleincoursegroup', $courseid)) {
                foreach ($assessmentids as $assessmentid => $rec) {
                    if (!isset(self::$assessments_to_refresh[$assessmentid])) {
                        role_assignments::mark_role_assignments_dirty(0, $assessmentid);
                        self::$assessments_to_refresh[$assessmentid] = $assessmentid;
                    }
                }
            }
        }
    }

    /**
     * Need to refresh any user role assignments involving assessments whose rules reference the (Totara) role involved.
     *
     * @param role_assigned $event
     */
    public static function role_assigned(role_assigned $event)
    {
        $roleid = $event->objectid;

        if (!empty($roleid)) {
            $courseid = ($event->contextlevel == CONTEXT_COURSE && $event->contextinstanceid <> SITEID ? $event->contextinstanceid : 0);
            if ($assessmentids = role_assignments::get_assessments_with_roleid_rules($roleid, $courseid)) {
                foreach ($assessmentids as $assessmentid => $rec) {
                    if (!isset(self::$assessments_to_refresh[$assessmentid])) {
                        role_assignments::mark_role_assignments_dirty(0, $assessmentid);
                        self::$assessments_to_refresh[$assessmentid] = $assessmentid;
                    }
                }
            }
        }
    }

    /**
     * Need to refresh any user role assignments involving assessments whose rules reference the (Totara) role involved.
     *
     * @param role_unassigned $event
     */
    public static function role_unassigned(role_unassigned $event)
    {
        $roleid = $event->objectid;

        if (!empty($roleid)) {
            $courseid = ($event->contextlevel == CONTEXT_COURSE && $event->contextinstanceid <> SITEID ? $event->contextinstanceid : 0);
            if ($assessmentids = role_assignments::get_assessments_with_roleid_rules($roleid, $courseid)) {
                foreach ($assessmentids as $assessmentid => $rec) {
                    if (!isset(self::$assessments_to_refresh[$assessmentid])) {
                        role_assignments::mark_role_assignments_dirty(0, $assessmentid);
                        self::$assessments_to_refresh[$assessmentid] = $assessmentid;
                    }
                }
            }
        }
    }

    /**
     * Need to refresh any user role assignments involving learner for whom the job assignment was updated and which
     * include "manager" type rules.
     *
     * @param base $event
     */
    public static function job_assignment_updated(base $event)
    {
        $learnerid = $event->relateduserid;

        if ($assessmentids = role_assignments::get_assessments_with_rule_type('manager')) {
            foreach ($assessmentids as $assessmentid => $rec) {
                role_assignments::mark_role_assignments_dirty(0, $assessmentid, $learnerid);
            }
        }
    }

    /**
     * User deleted - delete users assessment records
     *
     * @param user_deleted $event
     * @global \moodle_database $DB
     */
    public static function user_deleted(user_deleted $event)
    {
        global $DB;

        $DB->delete_records('assessment_due', array('userid' => $event->relateduserid));
    }

    /**
     * User updated - check the profile date
     *
     * @param user_updated $event
     */
    public static function user_updated(user_updated $event)
    {

        processor\assessment_due_processor::update_by_user_profile($event->relateduserid);
    }

    /*
     * User logged in - check if its the first time
     *
     * @global \stdClass $USER
     * @param \core\event\user_loggedin $event
     * @return void
     */
    public static function user_loggedin(user_loggedin $event)
    {
        global $USER;

        if ($USER->firstaccess !== $USER->currentlogin) {
            // This is not the first login.
            return;
        }

        processor\assessment_due_processor::update_by_user_login($USER->id);
    }
}
