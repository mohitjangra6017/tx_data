<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage assessment
 */

namespace mod_assessment\model;

use Exception;
use mod_assessment\entity\SimpleDBO;
use mod_assessment\helper;
use mod_assessment\helper\completion;

defined('MOODLE_INTERNAL') || die();

class stage_completion extends SimpleDBO
{

    public const TABLE = 'assessment_stage_completion';

    /** @var attempt */
    protected attempt $attempt;

    /** @var int */
    public int $attemptid = 0;

    /** @var int */
    public int $stageid;

    /** @var int */
    public int $userid;

    /** @var int */
    public int $role;

    /** @var int */
    public int $timestarted;

    /** @var int|null */
    public ?int $timecompleted;

    public function get_attempt(): ?attempt
    {
        if (!isset($this->attempt)) {
            $this->attempt = attempt::instance(['id' => $this->attemptid], MUST_EXIST);
        }
        return $this->attempt;
    }

    public function get_status()
    {

        // Check if status based on own actions.
        try {
            $attempt = $this->get_attempt();
        } catch (\dml_missing_record_exception $exception) {
            // If there's no attempt, it can't have started! (Should only happen on previews)
            return get_string('notstarted', 'assessment');
        }

        $versionstage = version_stage::instance(['stageid' => $this->stageid, 'versionid' => $this->attempt->versionid]);
        if (completion::is_stage_required($versionstage, $this->role)) {
            if ($this->is_complete()) {
                return get_string('completed', 'assessment');
            } elseif ($this->is_started()) {
                return get_string('inprogress', 'assessment');
            }
            return get_string('notstarted', 'assessment');
        }

        // If role has no required actions, get the progress of others, if applicable.
        $otherroles = helper\role::get_other_roles($this->role);
        foreach ($otherroles as $otherrole) {
            // Use other role's status if current role has no required actions and other role does.
            $required_roles_count = 0;
            $not_started_roles_count = 0;

            if (completion::is_stage_required($versionstage, $otherrole)) {
                $required_roles_count++;
                $othercompletion = self::make([
                    'attemptid' => $this->attemptid,
                    'stageid' => $this->stageid,
                    'role' => $otherrole
                ]);
                $othercompletion->set_attempt($this->attempt);

                if ($othercompletion->is_complete()) {
                    continue;
                }

                // At least one of the required roles has started but not finished the stage, so the overall status is in-progress.
                if ($othercompletion->is_started()) {
                    return get_string('inprogress', 'assessment');
                }

                $not_started_roles_count++;
            }

            // None of the required roles have started the stage yet.
            if ($required_roles_count > 0 && $required_roles_count == $not_started_roles_count) {
                return get_string('notstarted', 'assessment');
            }
        }

        // Either no required roles or all required roles have completed.
        return get_string('completed', 'assessment');
    }

    /**
     * @return bool
     */
    public function is_complete(): bool
    {
        return !empty($this->timecompleted);
    }

    /**
     * @return bool
     */
    public function is_started(): bool
    {
        return !empty($this->timestarted);
    }

    /**
     * @param attempt $attempt
     * @return self
     * @throws Exception
     */
    public function set_attempt(attempt $attempt): stage_completion
    {
        $this->attempt = $attempt;

        if (isset($attempt->id) && $attempt->id != $this->attempt->id) {
            throw new Exception('Cannot set attempt different that completion attemptid');
        }

        $this->attemptid = $attempt->id;
        return $this;
    }

    /**
     * @param int $attemptid
     * @return self
     */
    public function set_attemptid(int $attemptid): stage_completion
    {
        $this->attemptid = $attemptid;
        return $this;
    }

    /**
     * @param int $stageid
     * @return self
     */
    public function set_stageid(int $stageid): stage_completion
    {
        $this->stageid = $stageid;
        return $this;
    }

    /**
     * @param int $userid
     * @return self
     */
    public function set_userid(int $userid): stage_completion
    {
        $this->userid = $userid;
        return $this;
    }

    /**
     * @param int $role
     * @return self
     */
    public function set_role(int $role): stage_completion
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @param int $timestarted
     * @return self
     */
    public function set_timestarted(int $timestarted): stage_completion
    {
        $this->timestarted = $timestarted;
        return $this;
    }

    /**
     * @param int $timecompleted
     * @return self
     */
    public function set_timecompleted(int $timecompleted): stage_completion
    {
        $this->timecompleted = $timecompleted;
        return $this;
    }
}
