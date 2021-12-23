<?php
/**
 * Course tagged via custom fields
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses\CourseTagging;

use block_related_courses\CourseTagging;
use coding_exception;
use container_course\course;
use dml_exception;
use moodle_database;

defined('MOODLE_INTERNAL') || die;

class CustomFieldCourseTagging implements CourseTagging
{
    /**
     * @var moodle_database
     */
    protected $db;

    /**
     * CustomField constructor.
     * @param moodle_database $db
     */
    public function __construct(moodle_database $db)
    {
        $this->db = $db;
    }

    /**
     * Get all the tags to the given courses.
     *
     * @param int[] $courseIds
     * @return string[]
     * @throws coding_exception
     * @throws dml_exception
     */
    public function getTags(array $courseIds): array
    {
        $tags = [];

        if (empty($courseIds)) {
            return $tags;
        }

        [$sql, $params] = $this->db->get_in_or_equal($courseIds);
        $params[] = course::get_type();

        $records = $this->db->get_records_sql_menu(
            "SELECT cid.id, cid.data
               FROM {course_info_field} cif
               JOIN {course_info_data} cid ON cid.fieldid = cif.id
               JOIN {course} c ON c.id = cid.courseid
              WHERE cif.datatype = 'multiselect' AND cid.courseid {$sql}
              AND c.containertype = ?",
            $params
        );

        foreach ($records as $record) {
            foreach (json_decode($record) as $item) {
                if (!in_array($item->option, $tags)) {
                    $tags[] = $item->option;
                }
            }
        }

        return $tags;
    }

    /**
     * Get all courses with the given tag
     *
     * @param string $tag
     * @return array
     * @throws dml_exception
     * @throws coding_exception
     */
    public function getCourses(string $tag): array
    {
        return $this->db->get_records_sql(
            "SELECT c.*
               FROM {course_info_field} cif
               JOIN {course_info_data} cid ON cid.fieldid = cif.id
               JOIN {course} c ON c.id = cid.courseid
              WHERE cif.datatype = 'multiselect' 
              AND " . $this->db->sql_like('cid.data', '?') . "
              AND c.containertype = ?",
            [
                '%"' . $tag . '"%',
                course::get_type(),
            ]
        );
    }
}