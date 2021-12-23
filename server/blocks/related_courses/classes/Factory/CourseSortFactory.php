<?php
/**
 * Course Sort Factory
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses\Factory;

use block_related_courses\CourseSorting;
use block_related_courses\CourseSorting\CreatedTimeCourseSorting;
use block_related_courses\CourseSorting\FullNameCourseSorting;
use block_related_courses\CourseSorting\PopularCourseSorting;

defined('MOODLE_INTERNAL') || die;

class CourseSortFactory
{
    /**
     * Courses will be sorted via
     */
    public const SORT_FULLNAME = 1;

    public const SORT_CREATEDTIME = 2;

    public const SORT_POPULAR = 3;

    /**
     * @param int $sort
     * @return CourseSorting
     */
    public static function create(int $sort): CourseSorting
    {
        global $DB;

        switch ($sort) {
            case self::SORT_POPULAR:
                return new PopularCourseSorting($DB);

            case self::SORT_CREATEDTIME:
                return new CreatedTimeCourseSorting();

            case self::SORT_FULLNAME:
            default:
                return new FullNameCourseSorting();
        }
    }
}