<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\entity;

use Exception;
use mod_assessment\model\import_error;
use mod_assessment\model\role;

defined('MOODLE_INTERNAL') || die();

class assessment_version_assignment_log
{

    /** @var int|null */
    protected ?int $id;

    /** @var int|null */
    protected ?int $userid;

    /** @var role */
    protected role $role;

    /** @var int|null */
    protected ?int $learnerid;

    /** @var int */
    protected int $versionid;

    /** @var int */
    protected int $importid;

    /** @var int */
    protected int $csvrow;

    /** @var string */
    protected string $learneridraw;

    /** @var string */
    protected string $useridraw;

    /** @var bool */
    protected bool $skipped;

    /** @var int */
    protected int $timecreated;

    /** @var import_error */
    protected import_error $errorcode;

    /** @var int */
    protected $timeconfirmed;

    public function __construct(
        int $importid,
        int $csvrow,
        string $learneridraw,
        string $useridraw,
        bool $skipped,
        int $timecreated,
        role $role,
        int $versionid,
        int $userid = null,
        int $learnerid = null,
        $timeconfirmed = null,
        import_error $errorcode = null,
        int $id = null
    )
    {
        $this->id = $id;
        $this->set_userid($userid);
        $this->set_learnerid($learnerid);
        $this->role = $role;
        $this->learnerid = $learnerid;
        $this->versionid = $versionid;

        $this->importid = $importid;
        $this->csvrow = $csvrow;
        $this->learneridraw = $learneridraw;
        $this->useridraw = $useridraw;
        $this->set_skipped($skipped);
        $this->timecreated = $timecreated;
        $this->timeconfirmed = $timeconfirmed;
        $this->set_errorcode($errorcode);

        // Validation.
        if (!in_array($role->value(), [role::REVIEWER, role::EVALUATOR])) {
            throw new Exception("Can only assign reviewer and evaluator roles to a learner.");
        }
    }

    public static function get_next_importid(): int
    {
        global $DB;
        $importid = (int)$DB->get_field_sql("SELECT importid FROM {" . self::get_tablename() . "} ORDER BY importid DESC LIMIT 1");
        $importid++;
        return $importid;
    }

    public static function get_tablename(): string
    {
        return 'assessment_version_assignment_log';
    }

    public function export_for_database(): \stdClass
    {
        return (object)[
            'id' => $this->id ?? null,
            'userid' => $this->userid,
            'role' => $this->role->value(),
            'learnerid' => $this->learnerid,
            'versionid' => $this->versionid,
            'importid' => $this->importid,
            'csvrow' => $this->csvrow,
            'learneridraw' => $this->learneridraw,
            'useridraw' => $this->useridraw,
            'skipped' => $this->skipped,
            'timecreated' => $this->timecreated,
            'timeconfirmed' => $this->timeconfirmed,
            'errorcode' => $this->errorcode->value(),
        ];
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public function get_errorcode(): import_error
    {
        return $this->errorcode;
    }

    public function set_errorcode(?import_error $errorcode): self
    {
        $this->errorcode = $errorcode ?? new import_error();
        return $this;
    }

    public function get_importid(): int
    {
        return $this->importid;
    }

    public function get_learneridraw(): string
    {
        return $this->learneridraw;
    }

    public function get_useridraw(): string
    {
        return $this->useridraw;
    }

    public function get_learnerid(): ?int
    {
        return $this->learnerid;
    }

    public function set_learnerid(?int $learnerid): self
    {
        $this->learnerid = $learnerid;
        return $this;
    }

    public function get_userid(): ?int
    {
        return $this->userid;
    }

    public function set_userid(?int $userid): self
    {
        $this->userid = $userid;
        return $this;
    }

    public function get_role(): role
    {
        return $this->role;
    }

    public function get_versionid(): int
    {
        return $this->versionid;
    }

    public function is_confirmed(): bool
    {
        return !empty($this->timeconfirmed);
    }

    public function is_skipped(): bool
    {
        return $this->skipped;
    }

    public function set_skipped(bool $skipped): self
    {
        $this->skipped = $skipped;
        return $this;
    }

    public function mark_confirmed(): self
    {
        $this->timeconfirmed = time();
        return $this;
    }

    // TODO: Inherit generic entity methods.

    public function exists(): bool
    {
        return !empty($this->id);
    }

    public function delete()
    {
        global $DB;

        if (!$this->exists()) {
            throw new Exception('Cannot delete record that does not exist');
        }

        $DB->delete_records(static::get_tablename(), ['id' => $this->id]);
    }

    public function save()
    {
        global $DB;

        if ($this->exists()) {
            $DB->update_record(static::get_tablename(), $this->export_for_database());
        } else {
            $this->id = $DB->insert_record(static::get_tablename(), $this->export_for_database());
        }
    }
}
