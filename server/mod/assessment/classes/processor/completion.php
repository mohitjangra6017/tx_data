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

namespace mod_assessment\processor;

use completion_info;
use mod_assessment\helper\role;
use mod_assessment\model\answer;
use mod_assessment\model\assessment;
use mod_assessment\model\attempt;
use mod_assessment\model\question;
use mod_assessment\model\question_perms;
use mod_assessment\model\stage_completion;
use mod_assessment\model\version;
use mod_assessment\model\version_stage;
use moodle_database;
use totara_job\job_assignment;
use function grade_update;

defined('MOODLE_INTERNAL') or die;

require_once($CFG->libdir . '/gradelib.php');

class completion
{

    public function complete_attempt(attempt $attempt)
    {
        global $DB;

        if ($attempt->is_complete()) {
            return;
        }

        // Calculate grade.
        $points = 0;
        $pointstotal = 0;
        foreach ($this->get_gradeable_questions($attempt->versionid) as $question) {
            $denominator = 0;
            $numerator = 0;

            $roles = $question->get_roles_with_permission(question_perms::CAN_ANSWER);
            foreach ($roles as $role) {
                $answer = answer::instance([
                    'attemptid' => $attempt->id,
                    'questionid' => $question->id,
                    'role' => $role
                ], MUST_EXIST);
                $denominator += $question->get_max_score();
                $numerator += $question->get_score($answer);
            }

            $points += ($numerator / $denominator) * $question->weight;
            $pointstotal += $question->weight;
        }

        $grade = $pointstotal ? ($points / $pointstotal) * 100 : 100;  // If no gradeable questions, let's just assume you're awesome.
        $this->update_grade($attempt, $grade);

        $attempt->set_timecompleted(time());
        $attempt->set_grade($grade);
        $attempt->set_status($attempt::STATUS_COMPLETE);
        $attempt->save();

        // Mark activity as complete.
        $version = version::instance(['id' => $attempt->versionid], MUST_EXIST);
        $assessment = assessment::instance(['id' => $version->assessmentid], MUST_EXIST);
        $course = get_course($assessment->course);

        $modinfo = get_fast_modinfo($course, $attempt->userid);
        $cmid = $assessment->get_cmid();
        $cm = $modinfo->get_cm($cmid);

        $completion = new completion_info($course);
        if ($completion->is_enabled($cm) == COMPLETION_TRACKING_AUTOMATIC) {
            if ($assessment->statusrequired == $assessment::STATUS_COMPLETE) {
                $completion->update_state($cm, COMPLETION_COMPLETE, $attempt->userid);
            } elseif ($assessment->statusrequired == $assessment::STATUS_PASSED) {
                if ($attempt->grade >= $assessment->get_gradepass()) {
                    $completion->update_state($cm, COMPLETION_COMPLETE_PASS, $attempt->userid);
                } else {
                    $completion->update_state($cm, COMPLETION_COMPLETE_FAIL, $attempt->userid);
                    $attempt->set_status($attempt::STATUS_FAILED)->save();
                }
            }
        }

        // Send notifications to managers.
        $learner = $DB->get_record('user', ['id' => $attempt->userid]);
        $managerids = job_assignment::get_all_manager_userids($learner->id);
        foreach ($managerids as $managerid) {
            $manager = $DB->get_record('user', ['id' => $managerid]);
            \mod_assessment\message\completion::send($manager, $learner, $assessment);
        }
    }

    /**
     * @param int $versionid
     * @return question[]
     * @global moodle_database $DB
     */
    protected function get_gradeable_questions(int $versionid): array
    {
        global $DB;

        $questions = [];
        $questionsdata = $DB->get_records_sql(
            "SELECT q.*
               FROM {assessment_question} q
               JOIN {assessment_version_question} vq ON vq.questionid = q.id
              WHERE vq.versionid = :versionid AND q.weight > 0",
            ['versionid' => $versionid]
        );
        foreach ($questionsdata as $questiondata) {
            $question = question::class_from_type($questiondata->type);
            $question->set_data($questiondata);
            $questions[$question->id] = $question;
        }
        return $questions;
    }

    public function is_attempt_complete(attempt $attempt): bool
    {
        $version = version::instance(['id' => $attempt->versionid]);

        // Get stages required for completion?
        $versionstages = version_stage::instances(['versionid' => $version->id]);
        $versionstages = array_filter($versionstages, 'self::is_stage_required');

        // Are the stages required for completion?
        foreach ($versionstages as $versionstage) {
            if (!$this->is_stage_complete($attempt, $versionstage)) {
                return false;
            }
        }

        return true;
    }

    public function is_stage_complete(attempt $attempt, version_stage $versionstage): bool
    {
        $requiredroles = $versionstage->get_roles_cananswer();

        if (isset($requiredroles[role::LEARNER])) {
            $completion = stage_completion::instance([
                'attemptid' => $attempt->id,
                'role' => role::LEARNER,
                'stageid' => $versionstage->stageid
            ]);

            if (!$completion || !$completion->timecompleted) {
                return false;
            }
        }
        if (isset($requiredroles[role::EVALUATOR])) {
            $completion = stage_completion::instance([
                'attemptid' => $attempt->id,
                'role' => role::EVALUATOR,
                'stageid' => $versionstage->stageid
            ]);

            if (!$completion || !$completion->timecompleted) {
                return false;
            }
        }

        if (isset($requiredroles[role::REVIEWER])) {
            $completion = stage_completion::instance([
                'attemptid' => $attempt->id,
                'role' => role::REVIEWER,
                'stageid' => $versionstage->stageid
            ]);

            if (!$completion || !$completion->timecompleted) {
                return false;
            }
        }

        return true;
    }

    public function is_stage_required(version_stage $versionstage): bool
    {
        return !empty($versionstage->get_roles_cananswer());
    }

    public function update_grade(attempt $attempt, $grade)
    {
        $version = version::instance(['id' => $attempt->versionid], MUST_EXIST);
        $assessment = assessment::instance(['id' => $version->assessmentid], MUST_EXIST);
        $gradeitem = [
            'rawgrade' => $grade,
            'userid' => $attempt->userid,
        ];

        grade_update(
            'mod/assessment',
            $assessment->course,
            'mod',
            'assessment',
            $assessment->id,
            0,
            [$attempt->userid => $gradeitem]
        );
    }
}
