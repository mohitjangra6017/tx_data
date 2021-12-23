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
use stdClass;

defined('MOODLE_INTERNAL') || die();

class answer extends SimpleDBO
{

    public const TABLE = 'assessment_answer';

    /** @var int */
    public int $attemptid;

    /** @var int */
    public int $questionid;

    /** @var int */
    public int $role;

    /** @var int */
    public int $userid;

    /** @var string */
    public string $value;

    /**
     * @param int $attemptid
     * @return self
     */
    public function set_attemptid(int $attemptid): answer
    {
        $this->attemptid = $attemptid;
        return $this;
    }

    /**
     * @param int $questionid
     * @return self
     */
    public function set_questionid(int $questionid): answer
    {
        $this->questionid = $questionid;
        return $this;
    }

    /**
     * @param int $role
     * @return self
     */
    public function set_role(int $role): answer
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @param int $userid
     * @return self
     */
    public function set_userid(int $userid): answer
    {
        $this->userid = $userid;
        return $this;
    }

    /**
     *
     * @return stdClass|false
     */
    public function get_user()
    {
        global $DB;
        return $DB->get_record('user', ['id' => $this->userid]);
    }

    /**
     * @param string $value
     * @return self
     */
    public function set_value(string $value): answer
    {
        $this->value = $value;
        return $this;
    }

}
