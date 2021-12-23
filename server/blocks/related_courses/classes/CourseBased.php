<?php
/**
 * Course Based
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses;

defined('MOODLE_INTERNAL') || die;

interface CourseBased
{
    /**
     * Return a list of course ids that the recommendation will be based on
     *
     * @return object[]
     */
    public function getCourseIds(): array;
}