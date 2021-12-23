<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\processor;

use coding_exception;
use csv_import_reader;
use Exception;
use mod_assessment\entity\assessment_version_assignment_log;
use mod_assessment\factory\assessment_version_assignment_log_factory;
use mod_assessment\model\role;
use mod_assessment\model\version;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/csvlib.class.php');

class version_assignments_csv_processor
{

    protected csv_import_reader $cir;
    protected version $version;
    protected role $role;

    /** @var int */
    protected int $count;

    /** @var int */
    protected int $importid;

    /** @var string[] */
    protected array $errors;

    public function __construct(csv_import_reader $cir, version $version, role $role)
    {
        $this->cir = $cir;
        $this->version = $version;
        $this->role = $role;

        $this->errors = [];
    }

    public function validate_columns(): bool
    {
        if (!empty($this->errors)) {
            return true;
        }

        $headers = $this->cir->get_columns();
        if (!$headers) {
            $headers = [];
        }

        if (false === array_search('learnerid', $headers)) {
            $this->errors[] = get_string('error:import_missinglearnerid', 'mod_assessment');
        }

        if ($this->role->value() == role::EVALUATOR && false === array_search('evaluatorid', $headers)) {
            $this->errors[] = get_string('error:import_missingevaluatorid', 'mod_assessment');
        }

        if ($this->role->value() == role::REVIEWER && false === array_search('reviewerid', $headers)) {
            $this->errors[] = get_string('error:import_missingreviewerid', 'mod_assessment');
        }

        return empty($this->errors);
    }

    public function get_errors(): array
    {
        return $this->errors;
    }

    public function get_importid(): int
    {
        if (isset($this->importid)) {
            return $this->importid;
        }

        $this->importid = assessment_version_assignment_log::get_next_importid();
        return $this->importid;
    }

    public function execute()
    {
        if (!$this->validate_columns()) {
            throw new Exception("Cannot execute version assignments processor while csv has errors.");
        }

        $this->cir->init();
        $index = 1;
        while ($data = $this->cir->next()) {
            $index++;
            $data = array_combine($this->cir->get_columns(), $data);
            $this->save_row($index, $data);
        }

        if ($index == 1) {
            $this->errors[] = get_string('error:import_emptycsv', 'mod_assessment');
        }
    }

    public function save_row($csvrow, $data)
    {
        $record = [
            'importid' => $this->get_importid(),
            'versionid' => $this->version->get_id(),
            'csvrow' => $csvrow,
            'role' => $this->role->value(),
            'useridraw' => $data[$this->get_roleid_column()],
            'learneridraw' => $data['learnerid'],
            'timecreated' => time(),
            'skipped' => true
        ];

        // Create log record.
        $entity = assessment_version_assignment_log_factory::create_from_data($record);
        $entity->save();
    }

    protected function get_roleid_column(): string
    {
        switch ($this->role->value()) {
            case role::EVALUATOR:
                return 'evaluatorid';
            case role::REVIEWER:
                return 'reviewerid';
            default:
                throw new coding_exception('Invalid role value for column');
        }
    }
}
