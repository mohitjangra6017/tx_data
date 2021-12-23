<?php
/**
 * @copyright City & Guilds Kineo 2019
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_leaderboard\Grade;

use coding_exception;
use core\event\base;
use dml_exception;
use local_leaderboard\Utils;
use stdClass;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../lib.php');


class GradeEvent
{
    /** @var base */
    protected $event;

    /** @var object */
    protected $score;

    public function __construct(base $event, $score)
    {
        $this->event = $event;
        $this->score = $score;
        $this->score->userid = $this->event->relateduserid;
    }

    /**
     * @return mixed|object|stdClass
     */
    public function fetchUserGrade()
    {
        if (array_search($this->event->eventname, Utils::GRADE_EVENTS) === false) {
            return $this->score;
        }

        return $this->getEventGrade();
    }

    /**
     * @return mixed|object|stdClass
     * @throws coding_exception
     * @throws dml_exception
     */
    protected function getEventGrade()
    {
        switch ($this->event->eventname) {
            case '\\' . \mod_quiz\event\attempt_submitted::class:
                return $this->handleQuizAttempted();

            case '\\' . \mod_assign\event\submission_graded::class:
                return $this->handleAssignSubmissionGraded();

            case '\\' . \mod_lesson\event\essay_assessed::class:
                return $this->handleLessonEssayAssessed();

            case '\\' . \mod_scorm\event\status_submitted::class:
                return $this->handleScormStatusSubmitted();

            default:
                $this->score->score = 0;
                return $this->score;
        }
    }

    /**
     * @return stdClass
     * @throws coding_exception
     * @throws dml_exception
     */
    protected function handleQuizAttempted(): stdClass
    {
        global $DB;

        $data = $this->event->get_data();

        $quiz = $DB->get_record('quiz', ['id' => $data['other']['quizid']]);
        $course = get_course($data['courseid']);
        $cMod = get_coursemodule_from_instance('quiz', $quiz->id);

        $isComplete = quiz_get_completion_state($course, $cMod, $data['userid'], false);

        if ($isComplete) {
            $this->score->score = QuizGrade::getNewScore($quiz, $data);
        } else {
            $this->score->score = 0;
        }

        return $this->score;
    }

    /**
     * @return object
     * @throws dml_exception
     */
    protected function handleAssignSubmissionGraded(): object
    {
        $this->score->score = $this->getRoundedGrade();

        return $this->score;
    }

    /**
     * @return object
     * @throws dml_exception
     */
    protected function handleLessonEssayAssessed(): object
    {
        $this->score->score = $this->getRoundedGrade();

        return $this->score;
    }

    /**
     * Captures when scorm status set to completed or passed, and returns the score (% grade)
     * @return object
     */
    protected function handleScormStatusSubmitted(): object
    {
        global $DB;
        $scormElement = $this->event->other['cmielement'];
        $scormValue = $this->event->other['cmivalue'];

        try {
            if (in_array($scormElement, ['cmi.completion_status', 'cmi.core.lesson_status', 'cmi.success_status'])
                && in_array($scormValue, ['completed', 'passed'])) {

                $params = ['scormid' => $this->event->objectid, 'userid' => $this->event->relateduserid];

                // Score elements could be one of 'cmi.core.score.raw', 'cmi.score.raw'
                $scormScoreElements = ['cmi.core.score.raw', 'cmi.score.raw'];
                [$inSql, $inParams] = $DB->get_in_or_equal($scormScoreElements, SQL_PARAMS_NAMED);
                $params = $params + $inParams;
                $records = $DB->get_records_select($this->event->objecttable, "element {$inSql}", $params, '', 'value');
                $values = array_map(
                    function ($o) {
                        return $o->value;
                    },
                    $records
                );

                if (!empty($values)) {
                    $this->score->score = max($values);
                } else {
                    $this->score->score = 0;
                }
            }
        } catch (\Exception $exception) {
            $this->score->score = 0;
        }

        return $this->score;
    }

    /**
     * @return float|int
     * @throws dml_exception
     */
    private function getRoundedGrade()
    {
        global $DB;

        $grade = $DB->get_field($this->event->objecttable, 'grade', ['id' => $this->event->objectid]);
        return round($grade) > 0 ? round($grade) : 0;
    }
}