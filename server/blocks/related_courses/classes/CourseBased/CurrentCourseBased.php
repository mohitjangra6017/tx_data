<?php
/**
 * Current Course Based
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses\CourseBased;

use block_related_courses\CourseBased;

defined('MOODLE_INTERNAL') || die;

class CurrentCourseBased implements CourseBased
{
    /**
     * @var int
     */
    protected $currentCourseId;

    /**
     * CurrentCourseBased constructor.
     * @param int $currentCourseId
     */
    public function __construct(int $currentCourseId)
    {
        $this->currentCourseId = $currentCourseId;
    }

    /**
     * Return a list of courses the recommendation will be based on
     *
     * @return int[]
     */
    public function getCourseIds(): array
    {
        return [$this->currentCourseId];
    }
}