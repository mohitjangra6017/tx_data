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

use coding_exception;
use Exception;
use mod_assessment\entity\SimpleDBO;
use mod_assessment\helper\role;
use moodle_database;
use rb_column;
use reportbuilder;
use stdClass;
use totara_form\element;
use totara_form\form;

defined('MOODLE_INTERNAL') || die();

abstract class question extends SimpleDBO
{

    public const TABLE = 'assessment_question';

    /** @var string $question */
    public string $question = '';

    /** @var float $weight */
    public float $weight = 0.0;

    /** @var int $learnerperms */
    public $learnerperms;

    /** @var int $evaluatorperms */
    public $evaluatorperms;

    /** @var int $reviewerperms */
    public $reviewerperms;

    /** @var string $config */
    public $config;

    /** @var string $type */
    public string $type;
    private int $oldid;

    /**
     * @param mixed $conditions
     * @param int $strictness
     * @return self
     * @global moodle_database $DB
     */
    public static function instance($conditions, $strictness = IGNORE_MISSING): ?question
    {
        global $DB;
        if (!$data = $DB->get_record(static::TABLE, $conditions, '*', $strictness)) {
            return null;
        }

        $object = static::class_from_type($data->type);
        $object->set_data($data);
        return $object;
    }

    /**
     * @param string $type
     * @return question
     */
    public static function class_from_type(string $type): question
    {
        $class = "\\mod_assessment\\question\\{$type}\\model\\question";
        return new $class();
    }

    public static function get_tablename(): string
    {
        return self::TABLE;
    }

    /**
     * @param version_stage $versionstage
     * @param int $perpage
     * @param int $page
     * @return self[]
     * @global moodle_database $DB
     */
    public static function instances_from_versionstage(version_stage $versionstage, $perpage = 0, $page = 0): array
    {
        global $DB;

        $instances = [];

        $sql = "SELECT question.*, avq.sortorder, avq.versionid, avq.id AS versionquestionid, avq.stageid
                  FROM {assessment_question} question
                  JOIN {assessment_version_question} avq ON avq.questionid = question.id
                 WHERE avq.versionid = :versionid AND avq.stageid = :stageid AND avq.parentid IS NULL
              ORDER BY avq.sortorder";
        $params = ['versionid' => $versionstage->versionid, 'stageid' => $versionstage->stageid];
        $records = $DB->get_records_sql($sql, $params, $page, $perpage);

        if ($records) {
            foreach ($records as $record) {
                $instance = static::class_from_type($record->type);
                $instances[] = $instance->set_data($record);
            }
        }

        return $instances;
    }

    public function get_id(): ?int
    {
        return $this->id;
    }

    public function presave(&$isupdate = false)
    {
        if (!$this->is_gradable()) {
            $this->weight = 0;
        }
    }

    /**
     * @param int $role
     * @param attempt|null $attempt
     * @return bool
     */
    public function can_view(int $role, ?attempt $attempt): bool
    {
        $role = new \mod_assessment\model\role($role);
        $canview = $this->check_permission($role, question_perms::CAN_ANSWER);
        $canview = $canview || $this->check_permission($role, question_perms::CAN_VIEW_OTHER);

        if (!$canview && $attempt && $vq = version_question::instance(['questionid' => $this->id, 'versionid' => $attempt->versionid])) {
            $has_perm = $this->check_permission($role, question_perms::CAN_VIEW_SUBMITTED);

            if ($stagecompletions = stage_completion::instances(['attemptid' => $attempt->id, 'stageid' => $vq->stageid])) {
                foreach ($stagecompletions as $stagecompletion) {
                    // Let the question be viewed if the relevant permission is set for the role, but only if the stage has been submitted by somebody...
                    if ($stagecompletion->is_complete()) {
                        if ($this->is_question()) {
                            $role_answer = $this->get_answer($attempt->id, $stagecompletion->role);
                            $has_role_answered = ($role_answer && strlen($role_answer->value) > 0);
                            $canview |= ($has_role_answered && $has_perm);
                        } else {
                            $canview |= $has_perm;
                        }
                    }
                }
            }
        }

        return $canview;
    }

    public function exists(): bool
    {
        return !empty($this->id);
    }

    public function get_config(): ?stdClass
    {
        $config = json_decode($this->config);
        return $config ? $config : null;
    }

    /**
     * @return string
     */
    public function get_label(): string
    {
        return get_string('pluginname', 'assquestion_' . $this->type);
    }

    public function get_question(): string
    {
        return $this->question;
    }

    /**
     * @param int $permission
     * @return array
     */
    public function get_roles_with_permission(int $permission): array
    {
        $roles = [];

        if ($this->check_permission(new \mod_assessment\model\role(role::EVALUATOR), $permission)) {
            $roles[] = role::EVALUATOR;
        }
        if ($this->check_permission(new \mod_assessment\model\role(role::LEARNER), $permission)) {
            $roles[] = role::LEARNER;
        }

        return $roles;
    }

    /**
     * @param int $learnerperms
     * @return self
     */
    public function set_learnerperms(int $learnerperms): question
    {
        $this->required_change($learnerperms, $this->learnerperms);
        $this->learnerperms = $learnerperms;
        return $this;
    }

    /**
     * @param string $question
     * @return self
     */
    public function set_question(string $question): question
    {
        $this->required_change($question, $this->question);
        $this->question = $question;
        return $this;
    }

    /**
     * @param int $evaluatorperms
     * @return self
     */
    public function set_evaluatorperms(int $evaluatorperms): question
    {
        $this->required_change($evaluatorperms, $this->evaluatorperms);
        $this->evaluatorperms = $evaluatorperms;
        return $this;
    }

    /**
     * @param int $reviewerperms
     * @return self
     */
    public function set_reviewerperms(int $reviewerperms): question
    {
        $this->required_change($reviewerperms, $this->reviewerperms);
        $this->reviewerperms = $reviewerperms;
        return $this;
    }

    /**
     * @param float $weight
     * @return self
     */
    public function set_weight(float $weight): question
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @param \mod_assessment\model\role $role
     * @param int $permission e.g. question::PERM_CANANSWER etc.
     * @return bool
     * @throws coding_exception
     */
    public function check_permission(\mod_assessment\model\role $role, int $permission)
    {
        switch ($role->value()) {
            case role::LEARNER:
                return (int)$this->learnerperms & $permission;
            case role::EVALUATOR:
                return (int)$this->evaluatorperms & $permission;
            case role::REVIEWER:
                return (int)$this->reviewerperms & $permission;
            default:
                throw new coding_exception('Invalid role: ' . $role->value());
        }
    }

    /**
     * Safely removes a question for an assessment version
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

        $versionquestion = version_question::instance(['questionid' => $this->id, 'versionid' => $version->id], MUST_EXIST);
        $versionquestion->delete();

        // Recalculate sort order.
        $others = version_question::instances([
            'versionid' => $version->get_id(),
            'stageid' => $versionquestion->get_stageid(),
            'parentid' => $versionquestion->get_parentid()
        ]);
        foreach ($others as $otherversionq) {
            $otherversionq->calculate_sortorder()->save();
        }

        // Delete if not associated with any other versions.
        if (!$DB->record_exists(version_question::TABLE, ['questionid' => $this->id])) {
            $this->delete();
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
        return $DB->count_records(version_question::TABLE, ['questionid' => $this->id]) > 1;
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

    public function get_answer($attemptid, $role): ?answer
    {
        return answer::instance([
            'attemptid' => $attemptid,
            'questionid' => $this->id,
            'role' => $role
        ]);
    }

    abstract public function encode_value($value, form $form);

    abstract public function get_default();

    /**
     * @return string
     */
    abstract public function get_displayname(): string;

    abstract public function get_element();

    /**
     * @return string
     */
    abstract public function get_type(): string;

    /**
     * @return bool
     */
    abstract public function is_gradable(): bool;

    /**
     * @return bool
     */
    abstract public function is_question(): bool;

    abstract public function report_display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string;

    public function encode_config($config, $requireupdate = false): question
    {
        return $this->set_config(json_encode($config, $requireupdate));
    }

    /**
     * @param $config
     * @param bool $requireupdate
     * @return self
     */
    public function set_config($config, $requireupdate = false): question
    {

        if ($requireupdate) {
            $this->required_change($config, $this->config);
        }
        $this->config = $config;

        return $this;
    }

    public function export_for_form(): array
    {
        $formdata = [
            'id' => $this->get_id(),
            'question' => $this->question,
            'type' => $this->get_type(),
            'weight' => $this->weight,
            'learnerperms' => $this->learnerperms,
            'evaluatorperms' => $this->evaluatorperms,
            'reviewerperms' => $this->reviewerperms,
        ];

        $config = $this->get_config();
        foreach ((array)$config as $field => $value) {
            $formdata["config_$field"] = $value;
        }

        if ($formdata['weight'] > 0) {
            $formdata['weight'] = ['enable' => true, 'value' => (float)$formdata['weight']];
        }

        return $formdata;
    }
}
