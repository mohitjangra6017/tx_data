<?php
/**
 * Completion Course Based
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses\CourseBased;

use block_related_courses\CourseBased;
use dml_exception;
use moodle_database;

defined('MOODLE_INTERNAL') || die;

class CompletionCourseBased implements CourseBased
{
    /**
     * @var moodle_database
     */
    protected $db;

    /**
     * @var int
     */
    protected $userId;

    /**
     * CompletionCourseBased constructor.
     * @param moodle_database $db
     * @param int $userId
     */
    public function __construct(moodle_database $db, int $userId)
    {
        $this->db = $db;
        $this->userId = $userId;
    }

    /**
     * Return a list of course ids that the recommendation will be based on
     * @return array|object[]
     * @throws dml_exception
     */
    public function getCourseIds(): array
    {
        $ids = $this->db->get_fieldset_sql(
            'SELECT course
               FROM {course_completions}
              WHERE userid = :userid AND timecompleted > 0',
            ['userid' => $this->userId]
        );

        return array_combine($ids, $ids);
    }
}