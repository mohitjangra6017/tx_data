<?php
/**
 * Popular Course Sorting
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses\CourseSorting;

use block_related_courses\CourseSorting;
use coding_exception;
use dml_exception;
use moodle_database;

defined('MOODLE_INTERNAL') || die;

class PopularCourseSorting implements CourseSorting
{
    /**
     * @var moodle_database
     */
    protected $db;

    /**
     * Popular constructor.
     *
     * @param moodle_database $db
     */
    public function __construct(moodle_database $db)
    {
        $this->db = $db;
    }

    /**
     * Sort the given courses
     *
     * @param object[] $courses
     * @return object[]
     * @throws coding_exception
     * @throws dml_exception
     */
    public function sort(array $courses): array
    {
        if (!empty($courses)) {
            [$sql, $params] = $this->db->get_in_or_equal(array_keys($courses));

            $enrolments = $this->db->get_records_sql_menu(
                "SELECT c.id, count(ue.id)
                   FROM {course} c
              LEFT JOIN {enrol} e ON e.courseid = c.id
              LEFT JOIN {user_enrolments} ue ON ue.enrolid = e.id
                  WHERE c.id {$sql}
               GROUP BY c.id",
                $params
            );

            uasort(
                $courses,
                function ($a, $b) use ($enrolments) {
                    return $enrolments[$b->id] - $enrolments[$a->id];
                }
            );
        }

        return $courses;
    }
}