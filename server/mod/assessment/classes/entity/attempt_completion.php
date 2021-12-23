<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\entity;

use mod_assessment\model\attempt_completion_status;
use mod_assessment\model\role;
use stdClass;

defined('MOODLE_INTERNAL') || die();

class attempt_completion extends base
{

    protected ?int $id;
    protected int $attemptid;
    protected role $role;
    protected attempt_completion_status $status;

    public function __construct(int $attemptid, role $role, attempt_completion_status $status, int $id = null)
    {
        $this->id = $id;
        $this->attemptid = $attemptid;
        $this->role = $role;
        $this->status = $status;
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public function get_attemptid(): int
    {
        return $this->attemptid;
    }

    public function get_role(): role
    {
        return $this->role;
    }

    public function get_status(): attempt_completion_status
    {
        return $this->status;
    }

    public function set_status(attempt_completion_status $status)
    {
        $this->status = $status;
    }

    public static function get_tablename(): string
    {
        return 'assessment_attempt_completion';
    }

    public function export_for_database(): stdClass
    {
        return (object)[
            'id' => $this->id,
            'attemptid' => $this->attemptid,
            'role' => $this->role->value(),
            'status' => $this->status->value(),
        ];
    }
}
