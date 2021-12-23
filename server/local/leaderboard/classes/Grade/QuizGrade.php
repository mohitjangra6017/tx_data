<?php
/**
 * @copyright City & Guilds Kineo 2019
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_leaderboard\Grade;

use dml_exception;
use stdClass;

defined('MOODLE_INTERNAL') || die();

class QuizGrade
{
    /**
     * @param $quiz
     * @param $eventData
     * @return false|int|mixed
     * @throws dml_exception
     */
    public static function getNewScore($quiz, $eventData)
    {
        global $DB, $CFG;
        require_once $CFG->dirroot . '/mod/quiz/locallib.php';
        require_once $CFG->dirroot . '/mod/quiz/lib.php';

        $userId = $eventData['userid'];
        $contextId = $eventData['contextid'];

        $sql = "SELECT SUM(score) FROM {local_leaderboard_user} WHERE userid = :userid AND contextid = :contextid";
        $sumLocalScore = $DB->get_field_sql($sql, ['userid' => $userId, 'contextid' => $contextId]);

        $attempts = quiz_get_user_attempts($quiz->id, $userId, 'finished');
        $bestGrade = quiz_calculate_best_grade($quiz, $attempts);
        $adjustedGrade = (int)quiz_rescale_grade($bestGrade, $quiz, false);

        if ($adjustedGrade > $sumLocalScore) {
            $newScore = $adjustedGrade - $sumLocalScore;
        } else {
            $newScore = 0;
        }

        return $newScore;
    }
}