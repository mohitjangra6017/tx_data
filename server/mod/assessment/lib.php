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

use core\session\manager;
use mod_assessment\model\answer;
use mod_assessment\model\assessment;
use mod_assessment\model\attempt;
use mod_assessment\model\override;
use mod_assessment\model\question_perms;
use mod_assessment\model\ruleset;
use mod_assessment\model\stage_completion;
use mod_assessment\processor\role_assignments;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/lib/gradelib.php');

/**
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool|null TRUE if the feature is supported
 */
function assessment_supports(string $feature): ?bool
{
    switch ($feature) {
        case FEATURE_COMPLETION_HAS_RULES:
        case FEATURE_GRADE_HAS_GRADE:
        case FEATURE_GRADE_OUTCOMES:
        case FEATURE_SHOW_DESCRIPTION:
        case FEATURE_BACKUP_MOODLE2:
        case FEATURE_ARCHIVE_COMPLETION:
            return true;
        default:
            return null;
    }
}

/**
 * Add a assessment module to a course
 *
 * @param object $instance module form data
 * @return int {assessment} id
 */
function assessment_add_instance(object $instance): int
{
    $assessment = new mod_assessment\model\assessment();
    $assessment->set_data($instance)->save();

    // Create version.
    $version = new mod_assessment\model\version();
    $version->set_assessmentid($assessment->id);

    // (set some version/assessment defaults)
    $default = get_config('assessment', 'defaultsingleevaluator');
    $version->set_singleevaluator($default);

    $default = get_config('assessment', 'defaultsinglereviewer');
    $version->set_singlereviewer($default);

    $default = get_config('assessment', 'defaulthidegradeinoverview');
    $assessment->set_hidegrade($default)->save();

    $version->save();

    // Create grade.
    assessment_grade_configure($assessment);

    return $assessment->id;
}

/**
 * Delete a assessment module
 *
 * @param int id
 * @return bool
 */
function assessment_delete_instance(int $id): bool
{
    $assessment = mod_assessment\model\assessment::instance(['id' => $id], MUST_EXIST);
    $assessment->delete();

    $versions = mod_assessment\model\version::instances(['assessmentid' => $assessment->id]);
    foreach ($versions as $version) {
        $version->delete();

        $attempts = attempt::instances(['versionid' => $version->id]);
        foreach ($attempts as $attempt) {
            $attempt->delete();

            $answers = answer::instances(['attemptid' => $attempt->id]);
            foreach ($answers as $answer) {
                $answer->delete();
            }

            $stagecompletions = stage_completion::instances(['attemptid' => $attempt->id]);
            foreach ($stagecompletions as $stagecompletion) {
                $stagecompletion->delete();
            }
        }

        $overrides = override::instances(['assessmentid' => $assessment->id]);
        foreach ($overrides as $override) {
            $override->delete();
        }

        $rulesets = ruleset::instances(['versionid' => $version->id]);
        foreach ($rulesets as $ruleset) {
            $ruleset->delete();

            $rules = mod_assessment\model\rule::instances(['rulesetid' => $ruleset->id]);
            foreach ($rules as $rule) {
                $rule->delete();
            }
        }

        $versionstages = mod_assessment\model\version_stage::instances(['versionid' => $version->id]);
        foreach ($versionstages as $versionstage) {
            $versionstage->delete();

            $stages = mod_assessment\model\stage::instances(['id' => $versionstage->stageid]);
            foreach ($stages as $stage) {
                $stage->delete();
            }
        }

        $versionquestions = mod_assessment\model\version_question::instances(['versionid' => $version->id]);
        foreach ($versionquestions as $versionquestion) {
            $versionquestion->delete();

            $questions = mod_assessment\factory\assessment_question_factory::fetch_all(['id' => $versionquestion->questionid]);
            foreach ($questions as $question) {
                $question->delete();
            }
        }
    }

    return true;
}

/**
 * Update a assessment module
 *
 * @param object $instance module form data
 * @return bool
 */
function assessment_update_instance(object $instance): bool
{
    $assessment = mod_assessment\model\assessment::instance(['id' => $instance->instance]);
    $assessment->set_data($instance)->save();

    assessment_grade_configure($assessment);

    return true;
}

/**
 * Serves the folder files.
 *
 * @param object $course
 * @param object $cm
 * @param object $context
 * @param string $filearea
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if file not found, does not return if found - just send the file
 */
function mod_assessment_pluginfile(object $course, object $cm, object $context, string $filearea, array $args, bool $forcedownload, array $options = array()): bool
{
    require_course_login($course, false, $cm);
    $fs = get_file_storage();

    $itemid = (int)array_shift($args);
    $filename = array_shift($args);

    if (!$file = $fs->get_file($context->id, 'mod_assessment', $filearea, $itemid, '/', $filename)) {
        send_file_not_found();
    }

    manager::write_close();
    send_stored_file($file, 60 * 60, 0, true, $options);
}

/**
 * Obtains the automatic completion state for this module based on activity type.
 *
 * @param object $course
 * @param stdClass|cm_info $cm
 * @param int $userid
 * @param bool $type Type of comparison (or/and; can be used as return value if no conditions)
 * @return bool true if completed, false if not, $type if conditions not set.
 */
function assessment_get_completion_state(object $course, $cm, int $userid, bool $type): bool
{
    $assessment = mod_assessment\model\assessment::instance(['id' => $cm->instance], MUST_EXIST);
    if (mod_assessment\helper\completion::is_complete($assessment, $userid)) {
        return true;
    }

    return false;
}

/**
 * @param assessment $assessment
 * @return int Returns GRADE_UPDATE_OK, GRADE_UPDATE_FAILED, GRADE_UPDATE_MULTIPLE or GRADE_UPDATE_ITEM_LOCKED
 */
function assessment_grade_configure(assessment $assessment): int
{

    // Update grade configuration.
    $itemdetails = [
        'itemname' => $assessment->name,
        'hidden' => false,
        'reset' => false,
    ];
    return grade_update('mod/assessment', $assessment->course, 'mod', 'assessment', $assessment->id, 0, null, $itemdetails);
}

/**
 * For any existing data in the DB using the old permissions constants values, convert these to use the new constant values.
 *
 * @return int number of questions for which permission were converted
 */
function assessment_convert_old_question_permissions(): int
{
    global $DB;

    $search_perms = [10, 11, 100, 101, 110, 111];
    $perms_in = '(' . join(',', $search_perms) . ')';

    $fixed_count = 0;

    $sql = "SELECT id, learnerperms, evaluatorperms
            FROM {" . mod_assessment\model\question::TABLE . "}
            WHERE (learnerperms IN {$perms_in}) OR (evaluatorperms IN {$perms_in})";
    $params = [];

    if ($recs = $DB->get_records_sql($sql, $params)) {
        foreach ($recs as $rec) {
            $rec->learnerperms = (empty($rec->learnerperms) ? 0 : assessment_convert_old_permissions($rec->learnerperms));
            $rec->evaluatorperms = (empty($rec->evaluatorperms) ? 0 : assessment_convert_old_permissions($rec->evaluatorperms));
            $DB->update_record(mod_assessment\model\question::TABLE, $rec);
            $fixed_count++;
        }
    }

    return $fixed_count;
}

/**
 * Convert a pre-upgraded assessment question permission format to the new format
 * which utilises the new permission constant values in mod_assessment\model\question.
 *
 * @param int $old_perm
 * @return int
 */
function assessment_convert_old_permissions(int $old_perm): int
{
    static $old_constants = [
        'PERM_CANANSWER' => 10,
        'PERM_CANVIEWOTHER' => 1,
        'PERM_REQUIREANSWER' => 100,
    ];

    $new_perms = []; // Cache for repeated calls.

    if (!isset($new_perms[$old_perm])) {
        $new_perms[$old_perm] = 0;

        if ($old_perm & $old_constants['PERM_CANANSWER']) {
            $new_perms[$old_perm] |= question_perms::CAN_ANSWER;
        }
        if ($old_perm & $old_constants['PERM_CANVIEWOTHER']) {
            $new_perms[$old_perm] |= question_perms::CAN_VIEW_OTHER;
        }
        if ($old_perm & $old_constants['PERM_REQUIREANSWER']) {
            $new_perms[$old_perm] |= question_perms::IS_REQUIRED;
        }
    }

    return $new_perms[$old_perm];
}

/**
 * Upgrade code drops fields assessment_attempt.evaluatorid and creates new table assessment_attempt_assignments.
 *
 * Before this happens we need to store the existing evaluator in the new table and copy over reviewerid to reviewedbyid.
 */
function assessment_upgrade_transfer_assignments(): int
{
    global $DB;

    $transfer_count = 0;

    $now = time();

    if ($recs = $DB->get_records(mod_assessment\model\attempt::TABLE, null, 'id ASC')) {
        foreach ($recs as $rec) {
            if (isset($rec->evaluatorid) && !empty($rec->evaluatorid)) {
                $attempt = new mod_assessment\model\attempt();
                $attempt->set_data($rec);

                if (!in_array($rec->evaluatorid, $attempt->evaluatorids)) {
                    $attempt->evaluatorids[] = $rec->evaluatorid; // Add transferred evaluator if not present.
                } else {
                    continue; // Evaluator was already present, skip this record.
                }

                $attempt->postsave(true); // Just re-save the evaluator role assignments (creates new one if necessary).

                // Ensure that notifications are not sent out again to existing evaluators.
                $sql = "UPDATE {assessment_attempt_assignments} SET timenotified = :timenotified WHERE attemptid = :attemptid";
                $params = ['timenotified' => $now, 'attemptid' => $attempt->id];
                $DB->execute($sql, $params);

                $transfer_count++;
            }
        }
    }

    return $transfer_count;
}

/**
 * Archives user's completed assessment attempts for a course:
 * - Flag completed attempts as archived
 * - Discard all incomplete attempts
 * - Mark assessment as incomplete
 *
 * @param int $userid
 * @param int $courseid
 * @param int|null $windowopens
 *
 * @return bool always true
 * @internal This function should only be used by the course archiving API.
 *           It should never invalidate grades or activity completion state as these
 *           operations need to be performed in specific order and are done inside
 *           the archive_course_activities() function.
 */
function assessment_archive_completion(int $userid, int $courseid, ?int $windowopens = null): bool
{

    $now = time();
    if ($assessments = assessment::instances(['course' => $courseid])) {
        foreach ($assessments as $assessment) {
            // Get all un-archived attempts for each assessment in the course.
            $archived = false;
            if ($attempts = attempt::instances_for_user($userid, $assessment->id, null, null, $archived)) {
                foreach ($attempts as $attempt) {
                    if ($attempt->is_archived()) {
                        continue; // Should never happen, but let's just check anyway to be sure.
                    }

                    // Archive completed attempts.
                    if ($attempt->is_complete()) {
                        $attempt->set_timearchived($now)->save();
                    } // Delete all others.
                    else {
                        // Discard stage completions for attempt, if exists
                        // Do we really need to do this?
                        /* @var stage_completion $sc */
                        if ($stagecompletions = stage_completion::instances(['attemptid' => $attempt->id])) {
                            foreach ($stagecompletions as $sc) {
                                $sc->delete();
                            }
                        }

                        $attempt->delete();
                    }
                }
            }
        }

        role_assignments::mark_role_assignments_dirty($courseid); // Role assignments for assessment will need to be recalculated.
    }

    return true;
}