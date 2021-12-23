<?php
/**
 * Course Tagging interface
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses;

defined('MOODLE_INTERNAL') || die;

interface CourseTagging
{
    /**
     * Get all the tags to the given courses.
     *
     * @param int[] $courseIds
     * @return string[]
     */
    public function getTags(array $courseIds): array;

    /**
     * Get all courses with the given tag
     *
     * @param string $tag
     * @return array
     */
    public function getCourses(string $tag): array;
}