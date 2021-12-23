<?php
/**
 * Created Time Course Sorting
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses\CourseSorting;

use block_related_courses\CourseSorting;

defined('MOODLE_INTERNAL') || die;

class CreatedTimeCourseSorting implements CourseSorting
{
    /**
     * Sort the given courses
     *
     * @param object[] $courses
     * @return object[]
     */
    public function sort(array $courses): array
    {
        uasort(
            $courses,
            function ($a, $b) {
                return $a->timecreated - $b->timecreated;
            }
        );

        return $courses;
    }
}