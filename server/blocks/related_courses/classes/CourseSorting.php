<?php
/**
 * Course Sorting
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses;

defined('MOODLE_INTERNAL') || die;

interface CourseSorting
{
    /**
     * Sort the given courses
     *
     * @param object[] $courses
     * @return object[]
     */
    public function sort(array $courses): array;
}