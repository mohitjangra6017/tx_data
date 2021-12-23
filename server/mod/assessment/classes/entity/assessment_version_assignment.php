<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\entity;

use Exception;
use mod_assessment\model\role;
use stdClass;

defined('MOODLE_INTERNAL') || die();

class assessment_version_assignment extends base
{

    /** @var int|null */
    protected ?int $id;

    /** @var int */
    protected int $userid;

    /** @var role */
    protected role $role;

    /** @var int */
    protected int $learnerid;

    /** @var int */
    protected int $versionid;

    public function __construct(int $userid, role $role, int $learnerid, int $versionid, int $id = null)
    {
        $this->id = $id;
        $this->userid = $userid;
        $this->role = $role;
        $this->learnerid = $learnerid;
        $this->versionid = $versionid;

        // Validation.
        if (!in_array($role->value(), [role::REVIEWER, role::EVALUATOR])) {
            throw new Exception("Can only assign reviewer and evaluator roles to a learner.");
        }
    }

    public static function get_tablename(): string
    {
        return 'assessment_version_assignment';
    }

    public function export_for_database(): stdClass
    {
        return (object)[
            'id' => $id ?? null,
            'userid' => $this->userid,
            'role' => $this->role->value(),
            'learnerid' => $this->learnerid,
            'versionid' => $this->versionid,
        ];
    }

    public function get_id(): ?int
    {
        return $this->id;
    }

    public function get_learnerid(): int
    {
        return $this->learnerid;
    }

    public function get_role(): role
    {
        return $this->role;
    }

    public function get_userid(): int
    {
        return $this->userid;
    }

    public function get_versionid(): int
    {
        return $this->versionid;
    }

}
