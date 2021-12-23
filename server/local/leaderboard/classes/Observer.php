<?php

namespace local_leaderboard;

defined('MOODLE_INTERNAL') || die;

require_once(__DIR__ . '../../lib.php');

use coding_exception;
use core\event\base;
use core\event\course_module_completion_updated;
use dml_exception;
use local_leaderboard\event\adhoc_leaderboard;
use local_leaderboard\Grade\GradeEvent;

class Observer
{
    /**
     * Process the triggered event adding scores to the relevant users.
     *
     * @param base $event
     * @throws dml_exception
     */
    public static function eventTriggered(base $event)
    {
        global $DB;

        if ($event instanceof adhoc_leaderboard) {
            $score = $DB->get_record('local_leaderboard', ['eventname' => $event->eventname]);
            $score->score = $event->other['score'];
            $scores = [$score];
        } else {
            $scores = $DB->get_records('local_leaderboard', ['eventname' => $event->eventname, 'deleted' => 0]);
        }

        foreach ($scores as $score) {
            if (self::assert($score, $event)) {
                // Overriding principle, if related user id then points awarded to them (KTS-642)
                // i.e. don't reward admin users for admin tasks
                $userid = isset($event->relateduserid) ? $event->relateduserid : $event->userid;
                $score = self::calculateScore($score, $event);
                $score->contextid = $event->contextid;
                $score->courseid = $event->courseid ?? null;
                // Update user id if grading has changed it
                if (isset($score->userid) && (int)$score->userid !== (int)$userid) {
                    $userid = $score->userid;
                }
                self::saveUserScore($score, $userid);
            }
        }
    }

    /**
     * Add score to a user.
     *
     * @param object $score
     * @param int $userid
     * @return bool|int
     * @throws dml_exception
     */
    protected static function saveUserScore(object $score, int $userid)
    {
        global $DB;

        if (!empty($userid) && (isset($score->score) && $score->score >= 1)) {
            return $DB->insert_record(
                'local_leaderboard_user',
                (object)[
                    'leaderboardid' => $score->id,
                    'userid' => $userid,
                    'score' => $score->score,
                    'contextid' => $score->contextid,
                    'courseid' => $score->courseid,
                    'timescored' => time(),
                ]
            );
        }

        return false;
    }

    /**
     * Validate the event meet all the score conditions.
     * @param $score
     * @param base $event
     * @return bool
     * @throws coding_exception
     * @throws dml_exception
     */
    protected static function assert($score, base $event)
    {
        global $DB;
        $eventData = $event->get_data();

        if (!empty($score->frequency)) {
            if ($timescored = self::getLastTimeScored($score->id, $event->userid)) {
                if (time() - $timescored < $score->frequency) {
                    return false;
                }
            }
        }

        if ($eventData['eventname'] == '\\' . course_module_completion_updated::class) {
            $snapShot = $event->get_record_snapshot('course_modules_completion', $eventData['objectid']);
            $isModComplete = $snapShot->completionstate == COMPLETION_COMPLETE || $snapShot->completionstate == COMPLETION_COMPLETE_PASS;
            $canScoreAgain = Utils::canScoreAgain($eventData['courseid'], $eventData['userid']);
            $hasScoreAlready = $DB->record_exists(
                'local_leaderboard_user',
                [
                    'userid' => $eventData['userid'],
                    'contextid' => $eventData['contextid'],
                    'courseid' => $eventData['courseid'],
                ]
            );

            return ($isModComplete && !$hasScoreAlready) || ($isModComplete && $hasScoreAlready && $canScoreAgain);
        }

        return true;
    }

    /**
     * Get the last time a user got a score in a specific score event.
     *
     * @param int $leaderboardId
     * @param int $userid
     * @return mixed
     * @throws dml_exception
     */
    protected static function getLastTimeScored(int $leaderboardId, int $userid)
    {
        global $DB;

        return $DB->get_field_sql(
            'SELECT max(timescored)
               FROM {local_leaderboard_user}
              WHERE leaderboardid = ? AND userid = ?',
            [$leaderboardId, $userid]
        );
    }

    /**
     * Calculate the score for a given event.
     *
     * @param object $score
     * @param base $event
     * @return object
     */
    protected static function calculateScore(object $score, base $event): object
    {
        if (!empty($score->usegrade) && array_search($event->eventname, Utils::GRADE_EVENTS) !== false) {
            $gradeEvent = new GradeEvent($event, $score);
            $score = $gradeEvent->fetchUserGrade();
            return $score;
        }

        $courseId = !empty($event->courseid) ? $event->courseid
            : (!empty($event->other['course']) ? $event->other['course'] : 0);

        if (!empty($courseId) && $courseId != SITEID) {

            if ($multiplier = self::getCourseMultiplier($courseId)) {
                if (is_numeric($multiplier)) {
                    $score->score = round($score->score * $multiplier);
                }
            }
        } else if (($event->component == "totara_program") && !empty($event->objectid)) {
            if ($multiplier = self::getProgMultiplier($event->objectid)) {
                if (is_numeric($multiplier)) {
                    $score->score = round($score->score * $multiplier);
                }
            }
        }

        return $score;
    }

    /**
     * Return the score multiplier for a given course.
     *
     * @param int $courseid
     * @return mixed
     * @throws dml_exception
     */
    protected static function getCourseMultiplier(int $courseid)
    {
        global $DB;

        return $DB->get_field(
            'course_info_data',
            'data',
            [
                'courseid' => $courseid,
                'fieldid' => get_config('local_leaderboard', 'coursemultiplierfieldid'),
            ]
        );
    }

    /**
     * Return the score multiplier for a given program
     * @param $progid
     * @return mixed
     * @throws dml_exception
     */
    protected static function getProgMultiplier($progid)
    {
        global $DB;

        return $DB->get_field(
            'prog_info_data',
            'data',
            [
                'programid' => $progid,
                'fieldid' => get_config('local_leaderboard', 'progmultiplierfieldid'),
            ]
        );
    }
}