<?php
/**
 * @copyright 2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 *
 * DEVIOTIS2
 */

namespace mod_assessment\processor;

use context_course;
use context_module;
use core_user;
use dml_exception;
use Exception;
use mod_assessment\entity\assessment_version_assignment;
use mod_assessment\event\attempt_created;
use mod_assessment\factory\assessment_version_assignment_factory;
use mod_assessment\helper;
use mod_assessment\helper\debug_progress_trace;
use mod_assessment\message\evaluatorselected;
use mod_assessment\model;
use mod_assessment\model\assessment;
use mod_assessment\model\attempt;
use null_progress_trace;
use progress_trace;

class role_assignments
{

    /**
     * @param int $roleid
     * @param int $courseid
     * @return array
     * @todo: handle NOT operator?
     */
    public static function get_assessments_with_roleid_rules(int $roleid, $courseid = 0): array
    {

        static $recs = []; // caching for repeated calls with same function args

        $key = $roleid . '_' . $courseid;

        if (!isset($recs[$key])) {

            global $DB;
            // @todo: exclude records with both singlereviewer and singleevaluator?
            $sql = "SELECT DISTINCT a.id FROM {assessment} a
                        JOIN {assessment_version} v ON v.assessmentid = a.id AND v.timeopened > 0 AND (v.timeclosed IS NULL OR v.timeclosed = 0)
                        JOIN {assessment_ruleset} ars ON ars.versionid = v.id
                        JOIN {assessment_rule} ar ON ar.rulesetid = ars.id AND ar.type IN ('role', 'roleincoursegroup')
                             AND ar.value LIKE '%\"{$roleid}\"%'
                        WHERE 1=1
                       ";
            if ($courseid > 0) {
                $sql .= " AND a.course = :courseid";
            }
            $params = ['courseid' => $courseid];
            $recs[$key] = $DB->get_records_sql($sql, $params);
        }

        return $recs[$key];
    }

    /**
     * @param int cohortid
     * @return array
     * @throws dml_exception
     * @todo: handle NOT operator?
     */
    public static function get_assessments_with_cohortid_rules($cohortid): array
    {

        static $recs = []; // caching for repeated calls with same cohortid

        $key = $cohortid;

        if (!isset($recs[$key])) {

            global $DB;
            // @todo: exclude records with both singlereviewer and singleevaluator?
            $sql = "SELECT DISTINCT a.id FROM {assessment} a
                        JOIN {assessment_version} v ON v.assessmentid = a.id AND v.timeopened > 0 AND (v.timeclosed IS NULL OR v.timeclosed = 0)
                        JOIN {assessment_ruleset} ars ON ars.versionid = v.id
                        JOIN {assessment_rule} ar ON ar.rulesetid = ars.id AND ar.type = 'cohort'
                             AND ar.value LIKE '%\"{$cohortid}\"%'
                        WHERE 1=1
                       ";
            $params = [];
            $recs[$key] = $DB->get_records_sql($sql, $params);
        }

        return $recs[$key];
    }

    /**
     * Get assessment ids for any assessment that has the specified ruletype assigned (and optionally exists in the specified course).
     *
     * @param string $ruletype
     * @param int $courseid
     * @return array
     */
    public static function get_assessments_with_rule_type(string $ruletype, $courseid = 0): array
    {

        static $recs = []; // caching for repeated calls with same ruletype/courseid combo

        $key = $ruletype . '_' . $courseid;

        if (!isset($recs[$key])) {

            global $DB;

            $course_where = '';
            if ($courseid > 0) {
                $course_where = "AND a.course = :courseid";
            }
            // @todo: exclude records with both singlereviewer and singleevaluator?
            $sql = "SELECT DISTINCT a.id FROM {assessment} a
                        JOIN {assessment_version} v ON v.assessmentid = a.id AND v.timeopened > 0 AND (v.timeclosed IS NULL OR v.timeclosed = 0)
                        JOIN {assessment_ruleset} ars ON ars.versionid = v.id
                        JOIN {assessment_rule} ar ON ar.rulesetid = ars.id AND ar.type = :ruletype
                        WHERE 1=1 {$course_where}
                       ";
            $params = ['ruletype' => $ruletype, 'courseid' => $courseid];
            $recs[$key] = $DB->get_records_sql($sql, $params);
        }

        return $recs[$key];
    }

    /**
     * Mark a selection of assessment activities as requiring role assignment refresh, filtering based on arguments.
     * The next time the scheduled task runs, these marked assessments will have roles refreshed.
     *
     * This will be triggered by observed events.
     *
     * @param int $courseid If set, mark any assessments in the course as needing refresh
     * @param int $assessmentid If set, mark specified assessment as refresh
     * @param int $userid If set, mark any assessment where userid is a learner or any other assigned roles as needing refresh
     * (can be used in conjunction with course id)
     * @param bool $markall
     * @param bool $ignorecache
     * @throws dml_exception
     */
    public static function mark_role_assignments_dirty($courseid = 0, $assessmentid = 0, $userid = 0, $markall = false, $ignorecache = false)
    {
        global $DB;

        // For multiple successive calls using same filter combination (e.g. from event observers), don't need to re-process.
        static $processed_combinations = [];
        $key = $courseid . '_' . $assessmentid . '_' . $userid;
        if (!$ignorecache && isset($processed_combinations[$key])) {
            return;
        }
        $processed_combinations[$key] = $key;

        // Update refresh flag(s) based on filters.

        if ($markall) { // refresh all
            $sql = "UPDATE {assessment} SET needsrolesrefresh = 1";
            $DB->execute($sql, []);
            return;
        }

        if ($userid > 0) {
            $params = ['learnerid' => $userid, 'roleuserid' => $userid];

            $course_where = '';
            if ($courseid > 0) {
                $course_where = "AND a.course = :courseid";
                $params['courseid'] = $courseid;
            }
            $assessment_where = '';
            if ($assessmentid > 0) {
                $assessment_where = "AND av.assessmentid = :assessmentid";
                $params['assessmentid'] = $assessmentid;
            }

            $sql = "UPDATE {assessment} AS a SET needsrolesrefresh = 1
                    FROM
                        {assessment_version} AS av
                        JOIN {assessment_attempt} AS at ON at.versionid = av.id
                        LEFT JOIN {assessment_attempt_assignments} AS aat ON aat.attemptid = at.id
                    WHERE
                        av.assessmentid = a.id AND av.timeopened > 0 AND (av.timeclosed IS NULL OR av.timeclosed = 0)
                        {$course_where}
                        {$assessment_where}
                        AND (at.userid = :learnerid OR aat.userid = :roleuserid)
                    ";
            $DB->execute($sql, $params);
        } elseif ($assessmentid > 0) {
            $sql = "UPDATE {assessment} SET needsrolesrefresh = 1 WHERE id = :assessmentid";
            $DB->execute($sql, ['assessmentid' => $assessmentid]);
        } elseif ($courseid > 0) {
            $sql = "UPDATE {assessment} SET needsrolesrefresh = 1 WHERE course = :courseid";
            $DB->execute($sql, ['courseid' => $courseid]);
        }
    }

    /**
     * Update user role assignments for any assessements that have been marked "dirty" (needsrolesrefresh = 1).
     * This is triggered by scheduled task or possibly by observed events.
     *
     * @param int[]|null $learnerids
     * \progress_trace $progress_trace
     * @param debug_progress_trace|null $progress_trace
     * @return number[]
     */
    public static function update_dirty_role_assignments(?array $learnerids = null, ?debug_progress_trace $progress_trace = null): array
    {

        $trace = (is_null($progress_trace) ? new null_progress_trace() : $progress_trace);

        $trace->output(__METHOD__ . PHP_EOL);

        $result = ['updatecount' => 0];

        if ($assessments = assessment::instances(['needsrolesrefresh' => 1])) {
            $result['updatecount'] = count($assessments);
            $trace->output("Found {$result['updatecount']} assessment(s) where roles need updating..." . PHP_EOL);

            foreach ($assessments as $assessment) {
                self::update_role_assignments($assessment, $learnerids, $progress_trace);
            }
        }

        return $result;
    }

    /**
     * Get latest, active version for specified assessment, assign evaluators/reviewer.
     *
     * We still need to notify evaluators, don't do this here as it could affect responsiveness/performance,
     * this is handled by a scheduled task.
     *
     * @param assessment $assessment |id
     *
     * @param int|int[] $learnerids If specified, only update assignments for users in list, otherwise update
     * for anyone enrolled in course to which the assessment belongs.
     *
     * @param progress_trace $progress_trace
     * @return bool|string[]
     */
    public static function update_role_assignments(assessment $assessment, $learnerids = null, $progress_trace = null)
    {
        $trace = (is_null($progress_trace) ? new null_progress_trace() : $progress_trace);
        $trace->output(__METHOD__ . " Assessment: '{$assessment->name}' [id: {$assessment->id}]..." . PHP_EOL);

        $version = model\version::active_instance($assessment);
        if (empty($version)) {
            $assessment->set_needsrolesrefresh(false)->save();
            $trace->output("No active version available for assessment, done." . PHP_EOL);
            return false;
        }
        $trace->output("Assessment version = {$version->version} [id: $version->id]" . PHP_EOL);

        // Get applicable roles to assign.
        $rolestoassign = model\role::get_assignable_roles();
        foreach ($rolestoassign as $role => $rolelabel) {
            if ($version->has_rules_for_role($role)) {
                continue;
            }

            if (assessment_version_assignment_factory::exists(['role' => $role, 'versionid' => $version->get_id()])) {
                continue;
            }

            $trace->output("Note: assessment version has no rules set for {$rolelabel} role." . PHP_EOL);
            unset($rolestoassign[$role]);
        }

        // Get applicable learners.
        $learners = [];
        $learnerids = (is_scalar($learnerids) ? [$learnerids] : $learnerids);
        if (!empty($learnerids)) {
            foreach ($learnerids as $userid) {
                $learners[$userid] = core_user::get_user($userid);
            }
        } else {
            // If we didn't specify particular leaners to update assignments for, get all enrolled users,
            $learners = self::get_enrolled_users($assessment);
        }
        if (!$learners) {
            $assessment->set_needsrolesrefresh(false)->save();
            $trace->output("No learners provided for whom to assign roles, done assigning for '{$assessment->name}' [id: {$assessment->id}]." . PHP_EOL);
            return false;
        }
        $trace->output("Processing role assignments for " . count($learners) . " learner(s) on assessment..." . PHP_EOL);

        // Make the assignments.
        foreach ($rolestoassign as $role => $rolelabel) {
            $trace->output("Assigning {$rolelabel} role users to '{$assessment->name}' [id: {$assessment->id}]..." . PHP_EOL);

            if ($role == model\role::EVALUATOR && $version->singleevaluator) {
                $assessment->set_needsrolesrefresh(false)->save();
                $trace->output("Nothing to do for assessment [id: {$assessment->id}], single evaluator only." . PHP_EOL);
                continue;
            }

            if ($role == model\role::REVIEWER && $version->singlereviewer) {
                $assessment->set_needsrolesrefresh(false)->save();
                $trace->output("Nothing to do for assessment [id: {$assessment->id}], single reviewer only." . PHP_EOL);
                continue;
            }

            foreach ($learners as $learner) {
                if (helper\completion::is_complete($assessment, $learner->id)) {
                    $trace->output("Skipped assigning roles. Learner already completed assessment [id: {$assessment->id}]: {$learner->firstname} {$learner->lastname} [id: {$learner->id}, username: {$learner->username}, email: {$learner->email}]" . PHP_EOL);
                    continue;
                }

                $trace->output("Assigning roles on active attempt for \"{$learner->firstname} {$learner->lastname}\" [id: {$learner->id}, username: {$learner->username}, email: {$learner->email}] ..." . PHP_EOL);
                // Look for an existing, active attempt
                try {
                    $attempt = attempt::instance_active($version, $learner->id);
                } catch (Exception $ex) {
                    if (!helper\attempt::can_make_new_attempt($assessment, $learner->id)) {
                        $trace->output("No new attempt can be created for \"{$learner->firstname}\" {$learner->lastname} [id: {$learner->id}, username: {$learner->username}, email: {$learner->email}]. Skipping assignments." . PHP_EOL);
                        continue;
                    }

                    $attempt = new attempt();
                    $attempt->set_status($attempt::STATUS_NOTSTARTED);
                    $attempt->set_userid($learner->id);
                    $attempt->set_versionid($version->id);
                    $attempt->save();

                    $context = context_module::instance($assessment->get_cmid());
                    attempt_created::create_from_attempt($attempt, $context)->trigger();
                }

                $processor = new role_user_processor($version, new model\role($role));
                $role_users = $processor->get_valid_role_users($attempt, false);
                $trace->output("Assigning " . count($role_users) . " user(s) as {$rolelabel} for learner... ");
                if ($role == model\role::EVALUATOR) {
                    $attempt->set_evaluatorids(array_keys($role_users))->save();
                } elseif ($role == model\role::REVIEWER) {
                    $attempt->set_reviewerids(array_keys($role_users))->save();
                }
                $attempt->save(); // Removes any no-longer valid role user assignments and saves new ones.
                $trace->output(" DONE." . PHP_EOL);
            }

            $assessment->set_needsrolesrefresh(false)->save();
            $trace->output("Finished assigning {$rolelabel} role users for '{$assessment->name}' [id: {$assessment->id}]." . PHP_EOL);
        }
    }

    /**
     * For any currently active assessment versions attempts that are incomplete, notify any assigned evaluators
     * who have not yet already been notified.
     *
     * @param progress_trace|null $progress_trace
     */
    public static function notify_unnotified_evaluators(?progress_trace $progress_trace = null)
    {
        global $CFG, $DB;

        $trace = (is_null($progress_trace) ? new null_progress_trace() : $progress_trace);
        $trace->output(__METHOD__ . "..." . PHP_EOL);

        static $assessments = []; // for caching repeated calls using same records
        static $evaluators = [];  //
        static $attempts = [];    //

        $failed_status = attempt::STATUS_FAILED; // any status below this value is incomplete
        $sql = "SELECT
                    aat.id, aat.attemptid, aat.userid AS evaluatorid, at.userid AS learnerid, av.singleevaluator, av.assessmentid, aat.role
                    FROM {assessment_attempt_assignments} aat
                    JOIN {assessment_attempt} at ON at.id = aat.attemptid AND at.status < :failedstatus
                    JOIN {assessment_version} av ON av.id = at.versionid AND av.timeopened > 0 AND av.timeclosed IS NULL
                WHERE
                    aat.timenotified = 0 AND aat.role = :evaluatorrole
                ORDER BY av.assessmentid ASC, aat.attemptid ASC
               ";
        $params = ['evaluatorrole' => helper\role::EVALUATOR, 'failedstatus' => $failed_status];

        if ($recs = $DB->get_records_sql($sql, $params)) {
            if (isset($CFG->noemailever) && $CFG->noemailever) {
                $trace->output("NOTE:'noemailever' flag is set in config, no emails will actually be sent out." . PHP_EOL);
            }
            if (isset($CFG->divertallemailsto) && !empty($CFG->divertallemailsto)) {
                $trace->output("NOTE:'divertallemailsto' is set in config, current value: {$CFG->divertallemailsto}." . PHP_EOL . PHP_EOL);
            }
            $trace->output("Processing " . count($recs) . " evaluator notifications..." . PHP_EOL);

            $sent_count = 0;
            $unsent_count = 0;
            $processed_count = 0;

            foreach ($recs as $rec) {
                if (!isset($attempts[$rec->attemptid])) {
                    $attempts[$rec->attemptid] = attempt::instance(['id' => $rec->attemptid]);
                }

                if (!isset($assessments[$rec->assessmentid])) {
                    $assessments[$rec->assessmentid] = assessment::instance(['id' => $rec->assessmentid]);
                }
                if (!isset($evaluators[$rec->evaluatorid])) {
                    $evaluators[$rec->evaluatorid] = $DB->get_record('user', ['id' => $rec->evaluatorid]);
                }

                $learner = $DB->get_record('user', ['id' => $rec->learnerid]);
                if (evaluatorselected::send($evaluators[$rec->evaluatorid], $learner, $assessments[$rec->assessmentid],
                    $rec->singleevaluator)) {
                    $sent_count++;
                } else {
                    $unsent_count++;
                }
                $attempts[$rec->attemptid]->set_evaluators_notified(array_keys($evaluators));
                $trace->output('.');
                $processed_count++;
            }
            $trace->output(PHP_EOL . PHP_EOL);
            $trace->output("Notifications processed. {$sent_count} sent, {$unsent_count} not sent." . PHP_EOL);
        }
    }

    /**
     * @param assessment $assessment |id
     * @param string $rolefilter mdl_role.shortname to match user course enrollment If set, only return users with this role.
     * @return array
     */
    public static function get_enrolled_users(assessment $assessment, $rolefilter = 'student'): array
    {
        global $DB;

        $assessment = (is_int($assessment) ? assessment::instance(['id' => $assessment]) : $assessment);
        $contextid = context_course::instance($assessment->course)->id;

        $users = get_enrolled_users(context_course::instance($assessment->course, '', 0, 'u.*', null));

        if (!empty($rolefilter)) {
            if ($roleid = $DB->get_field('role', 'id', ['shortname' => $rolefilter])) {
                foreach ($users as $id => $user) {
                    if (!$DB->record_exists('role_assignments', ['userid' => $id, 'roleid' => $roleid, 'contextid' => $contextid])) {
                        unset($users[$id]);
                    }
                }
            }
        }

        return $users;
    }
}
