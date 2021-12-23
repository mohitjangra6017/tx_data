<?php
/**
 * Course Tagging Factory
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses\Factory;

use block_related_courses\CourseTagging;
use block_related_courses\CourseTagging\CustomFieldCourseTagging;
use block_related_courses\CourseTagging\TagCourseTagging;

defined('MOODLE_INTERNAL') || die;

class CourseTaggingFactory
{
    /**
     * Courses will be tagged using
     */
    public const TAGGED_TAG = 1;

    public const TAGGED_CUSTOMFIELD = 2;

    /**
     * @param $tagging
     * @return CourseTagging
     */
    public static function create($tagging): CourseTagging
    {
        global $DB;

        switch ($tagging) {
            case self::TAGGED_CUSTOMFIELD:
                return new CustomFieldCourseTagging($DB);

            case self::TAGGED_TAG:
            default:
                return new TagCourseTagging();
        }
    }
}