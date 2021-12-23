<?php
/**
 * Course tagged via tags
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses\CourseTagging;

use block_related_courses\CourseTagging;
use core_tag_area;
use core_tag_tag;
use dml_missing_record_exception;

defined('MOODLE_INTERNAL') || die;

class TagCourseTagging implements CourseTagging
{
    /**
     * {@inheritdoc}
     */
    public function getTags(array $courseIds): array
    {
        $tags = [];

        foreach ($courseIds as $courseid) {
            $coursetags = core_tag_tag::get_item_tags(null, 'course', $courseid);
            foreach ($coursetags as $tag) {
                if (!isset($tags[$tag->name])) {
                    $tags[] = $tag->name;
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
     * @throws dml_missing_record_exception
     */
    public function getCourses(string $tag): array
    {
        return core_tag_tag::get_by_name(
            core_tag_area::get_collection(null, 'course'),
            $tag
        )->get_tagged_items('core', 'course');
    }
}