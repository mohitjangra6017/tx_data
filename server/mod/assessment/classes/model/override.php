<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\model;

use mod_assessment\entity\SimpleDBO;

defined('MOODLE_INTERNAL') || die();

class override extends SimpleDBO
{

    public const TABLE = 'assessment_override';

    /** @var int */
    public int $attempts;

    /** @var int */
    public int $assessmentid;

    /** @var int */
    public int $userid;

    public function get_assessmentid(): int
    {
        return $this->assessmentid;
    }

    public function get_attempts(): int
    {
        if (empty($this->id) & (!isset($this->attempts)) && isset($this->assessmentid)) {
            $assessment = assessment::instance(['id' => $this->assessmentid], MUST_EXIST);
            $this->attempts = $assessment->attempts;
        }
        return $this->attempts;
    }

    public function get_userid(): int
    {
        return $this->userid;
    }

    public function is_unlimited(): bool
    {
        return $this->get_attempts() == \mod_assessment\helper\attempt::UNLIMITED;
    }

    public function presave(&$isupdate = false)
    {
        $this->timemodified = time();
    }

    /**
     * @param int $assessmentid
     * @return self
     */
    public function set_assessmentid(int $assessmentid): override
    {
        $this->assessmentid = $assessmentid;
        return $this;
    }

    /**
     * @param int $attempts
     * @return self
     */
    public function set_attempts(int $attempts): override
    {
        $this->attempts = $attempts;
        return $this;
    }

    /**
     * @param int $userid
     * @return self
     */
    public function set_userid(int $userid): override
    {
        $this->userid = $userid;
        return $this;
    }
}
