<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\model;

use mod_assessment\entity\SimpleDBO;
use moodle_database;

defined('MOODLE_INTERNAL') || die();

class assessment extends SimpleDBO
{

    public const TABLE = 'assessment';

    public const STATUS_COMPLETE = 50;
    public const STATUS_PASSED = 80;

    public $course;
    public $name;
    public $intro;
    public $introformat;
    public $attempts;
    public $extraattempts;
    public $grademethod;
    public $gradepass;
    public $statusrequired;
    public $timedue;
    public $duedays;
    public $duetype;
    public $duefieldid;

    /** @var bool $hidegrade */
    public $hidegrade = 0;

    /** @var bool $hidegrade */
    public $needsrolesrefresh = 0;

    public static function get_tablename(): string
    {
        return self::TABLE;
    }

    public function get_id(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     * @global moodle_database $DB
     */
    public function get_cmid(): int
    {
        global $DB;

        $params = ['course' => $this->course, 'module' => $this->get_moduleid(), 'instance' => $this->id];
        return $DB->get_field('course_modules', 'id', $params, MUST_EXIST);
    }

    public function get_gradepass()
    {
        return $this->gradepass;
    }

    /**
     * @return int id of associated {modules} record
     * @global moodle_database $DB
     */
    public function get_moduleid(): int
    {
        global $DB;
        static $moduleid;

        if ($moduleid) {
            return $moduleid;
        }

        $moduleid = $DB->get_field('modules', 'id', ['name' => 'assessment'], MUST_EXIST);
        return $moduleid;
    }

    /**
     * @return bool
     */
    public function must_complete(): bool
    {
        return in_array($this->statusrequired, [self::STATUS_COMPLETE, self::STATUS_PASSED]);
    }

    /**
     * @return bool
     */
    public function must_pass(): bool
    {
        return $this->statusrequired == self::STATUS_PASSED;
    }

    /**
     * @param bool $hidegrade
     * @return self
     */
    public function set_hidegrade(bool $hidegrade): assessment
    {
        $this->hidegrade = $hidegrade;
        return $this;
    }

    /**
     * @param bool $needsrolesrefresh
     * @return self
     */
    public function set_needsrolesrefresh(bool $needsrolesrefresh): assessment
    {
        $this->needsrolesrefresh = $needsrolesrefresh;
        return $this;
    }

    /**
     * @param int $timedue
     * @return self
     */
    public function set_timedue(int $timedue): assessment
    {
        $this->timedue = $timedue;
        return $this;
    }

    /**
     * @param int $duedays
     * @return self
     */
    public function set_duedays(int $duedays): assessment
    {
        $this->duedays = $duedays;
        return $this;
    }

    /**
     * @param int $duetype
     * @return self
     */
    public function set_duetype(int $duetype): assessment
    {
        $this->duetype = $duetype;
        return $this;
    }

    /**
     * @param int|null $duefieldid
     * @return self
     */
    public function set_duefieldid(?int $duefieldid): assessment
    {
        $this->duefieldid = $duefieldid;
        return $this;
    }

    /**
     * Delete children
     *
     * @global moodle_database $DB
     */
    public function delete(): bool
    {
        global $DB;

        $DB->delete_records('assessment_due', array('assessmentid' => $this->id));

        return parent::delete();
    }
}
