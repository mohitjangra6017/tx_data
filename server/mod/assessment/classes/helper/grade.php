<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\helper;

use mod_assessment\model\assessment;

class grade
{

    public const METHOD_HIGHEST = 1;

    /**
     * @param assessment $assessment
     * @param int $learnerid
     * @return float
     * @global \moodle_database $DB
     */
    public static function get_best_grade(assessment $assessment, int $learnerid): float
    {
        global $DB;

        $sql = "SELECT attempt.*
                FROM {assessment_attempt} attempt
                JOIN {assessment_version} version ON version.id = attempt.versionid
                WHERE version.assessmentid = :assessmentid 
                   AND attempt.userid = :userid 
                   AND attempt.timecompleted > 0
                   AND attempt.timearchived = 0
                ORDER BY attempt.grade DESC";
        $attempt = $DB->get_record_sql($sql, ['assessmentid' => $assessment->id, 'userid' => $learnerid], IGNORE_MULTIPLE);
        if (!$attempt || !is_numeric($attempt->grade)) {
            return 0;
        }
        return $attempt->grade;
    }
}
