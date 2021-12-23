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

use Exception;
use mod_assessment\entity\SimpleDBO;
use moodle_database;

defined('MOODLE_INTERNAL') || die();

class stage extends SimpleDBO
{

    public const TABLE = 'assessment_stage';

    /** @var string $name */
    public string $name = '';

    /** @var int $name */
    public int $newpage = 0;

    private int $oldid = 0;

    /**
     * @param version $version
     * @return self[]
     * @global moodle_database $DB
     */
    public static function instances_from_version(version $version): array
    {
        global $DB;

        $instances = [];

        $sql = "SELECT stage.*, avs.sortorder, avs.versionid, avs.id AS versionstageid
                  FROM {assessment_stage} stage
                  JOIN {assessment_version_stage} avs ON avs.stageid = stage.id
                 WHERE avs.versionid = :versionid
              ORDER BY avs.sortorder";
        $records = $DB->get_records_sql($sql, ['versionid' => $version->id]);

        if ($records) {
            foreach ($records as $record) {
                $instance = new self();
                $instances[] = $instance->set_data($record);
            }
        }

        return $instances;
    }

    public function get_id(): ?int
    {
        return $this->id;
    }

    /**
     * Safely removes a stage for an assessment version
     *
     * @param version $version
     * @throws Exception
     * @global moodle_database $DB
     */
    public function delete_version(version $version)
    {
        global $DB;
        if (!$version->is_draft()) {
            throw new Exception(get_string('error_versionlock', 'assessment'));
        }

        $versionstage = version_stage::instance(['stageid' => $this->id, 'versionid' => $version->id], MUST_EXIST);
        $versionstage->delete();

        // Delete if not associated with any other versions.
        if (!$DB->record_exists(version_stage::TABLE, ['stageid' => $this->id])) {
            $this->delete();
        }

        // Remove associated questions.
        if ($questions = question::instances_from_versionstage($versionstage)) {
            foreach ($questions as $question) {
                $question->delete_version($version);
            }
        }
    }

    /**
     * @return bool
     * @global moodle_database $DB
     */
    public function has_multiple_versions(): bool
    {
        global $DB;
        if (!isset($this->id)) {
            return false;
        }
        return $DB->count_records(version_stage::TABLE, ['stageid' => $this->id]) > 1;
    }

    /**
     * @return bool
     */
    public function needs_remap(): bool
    {
        return isset($this->oldid);
    }

    protected function required_change($newval, $oldval)
    {
        if ($newval != $oldval && $this->has_multiple_versions() && !$this->needs_remap()) {
            $this->oldid = $this->id;
            unset($this->id);
        }
    }

    /**
     * @param string $name
     * @return self
     */
    public function set_name(string $name): stage
    {
        $this->required_change($name, $this->name);
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $newpage
     * @return self
     */
    public function set_newpage(string $newpage): stage
    {
        $this->required_change($newpage, $this->newpage);
        $this->newpage = $newpage;
        return $this;
    }
}
