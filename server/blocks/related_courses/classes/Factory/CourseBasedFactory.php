<?php
/**
 * Course Based Factory
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses\Factory;

use block_related_courses\CourseBased;
use block_related_courses\CourseBased\CompletionCourseBased;
use block_related_courses\CourseBased\CurrentCourseBased;
use block_related_courses\CourseBased\NoneCourseBased;

defined('MOODLE_INTERNAL') || die;

class CourseBasedFactory
{
    /**
     * Show courses based on...
     */
    public const BASED_COMPLETED = 1;

    public const BASED_CURRENT = 2;

    /**
     * @param int $based
     * @return CourseBased
     */
    public static function create(int $based): CourseBased
    {
        global $DB, $USER, $COURSE;

        switch ($based) {
            case self::BASED_CURRENT:
                return new CurrentCourseBased($COURSE->id);

            case self::BASED_COMPLETED:
                return new CompletionCourseBased($DB, $USER->id);

            default:
                return new NoneCourseBased();
        }
    }
}