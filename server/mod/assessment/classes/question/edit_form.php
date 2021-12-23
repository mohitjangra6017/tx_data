<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package mod_assessment
 * @subpackage question
 */

namespace mod_assessment\question;

defined('MOODLE_INTERNAL') || die();

use coding_exception;
use context_module;
use html_table;
use html_table_row;
use mod_assessment\helper;
use mod_assessment\model\assessment;
use mod_assessment\model\question;
use mod_assessment\model\question_perms;
use mod_assessment\model\role;
use mod_assessment\model\stage;
use mod_assessment\model\version;
use moodleform;
use renderer_base;
use stdClass;

global $CFG;
require_once($CFG->libdir . '/formslib.php');

abstract class edit_form extends moodleform
{

    /** @var string[] */
    public array $freezefields;

    /** @var question $question */
    public question $question;

    /** @var version $version */
    public version $version;

    abstract public static function get_type();

    /**
     * @return array
     */
    public static function get_roles(): array
    {
        return [
            helper\role::LEARNER => 'learner',
            helper\role::EVALUATOR => 'evaluator',
            helper\role::REVIEWER => 'reviewer'
        ];
    }

    /**
     * @param question|null $question
     * @param bool $formsubmit
     * @return question_perms[]
     * @throws coding_exception
     */
    public static function get_permission_data(question $question = null, $formsubmit = false): array
    {
        if (!$question) {
            $question = question::class_from_type(static::get_type());
        }

        $perms = [];

        foreach (self::get_roles() as $rolecode => $role) {
            $cananswer = optional_param(
                $role . '_cananswer',
                $formsubmit ? 0 : $question->check_permission(new role($rolecode), question_perms::CAN_ANSWER),
                PARAM_BOOL
            );
            $requireanswer = optional_param(
                $role . '_requireanswer',
                $formsubmit ? 0 : $question->check_permission(new role($rolecode), question_perms::IS_REQUIRED),
                PARAM_BOOL
            );
            $canviewother = optional_param(
                $role . '_canviewother',
                $formsubmit ? 0 : $question->check_permission(new role($rolecode), question_perms::CAN_VIEW_OTHER),
                PARAM_BOOL
            );
            $canviewsubmitted = optional_param(
                $role . '_canviewsubmitted',
                $formsubmit ? 0 : $question->check_permission(new role($rolecode), question_perms::CAN_VIEW_SUBMITTED),
                PARAM_BOOL
            );

            $questionperms = new question_perms($cananswer, $requireanswer, $canviewother, $canviewsubmitted);
            $perms[$role] = $questionperms;
        }

        return $perms;
    }

    protected function definition()
    {
        $this->add_js();
        $this->_form;

        $this->freezefields = [];

        $this->add_hidden_data();
        $this->add_current_stage();

        $this->_form->addElement('text', 'question', get_string('question', 'mod_assessment'));
        $this->_form->addRule('question', get_string('required'), 'required');
        $this->_form->setType('question', PARAM_TEXT);

        if ($this->get_required_data('question')->is_gradable()) {
            $this->add_grade_fields();
        }

        $this->add_question_fields();

        if (!$this->get_parent()) {
            $this->add_permissions_table($this->get_required_data('question')->is_question());
        }

        if (!$this->get_version()->is_draft()) {
            $this->_form->freeze($this->freezefields);
        }

        $this->add_action_buttons(true, get_string('save', 'admin'));
    }

    public function get_config_data(): stdClass
    {
        $config = new stdClass();
        $data = $this->get_data();
        if ($data) {
            foreach ($data as $field => $value) {
                if (strpos($field, 'config_') === 0) {
                    $fieldname = substr($field, 7);
                    $config->$fieldname = $value;
                }
            }
        }

        return $config;
    }

    public function get_context(): context_module
    {
        if (!isset($this->context)) {
            $assessment = assessment::instance(['id' => $this->get_version()->assessmentid], MUST_EXIST);
            $this->context = context_module::instance($assessment->get_cmid());
        }

        return $this->context;
    }

    public function get_stage(): stage
    {
        if (!isset($this->stage)) {
            $this->stage = $this->get_required_data('stage');
        }
        return $this->stage;
    }

    /**
     * @return question
     */
    public function get_question(): question
    {
        if (!isset($this->question)) {
            $this->question = $this->get_required_data('question');
        }

        return $this->question;
    }

    public function get_parent(): ?question
    {
        return $this->_customdata['parent'] ?? null;
    }

    /**
     * @return version
     */
    public function get_version(): version
    {
        if (!isset($this->version)) {
            $this->version = $this->get_required_data('version');
        }

        return $this->version;
    }

    public function validate_integer($integer, $allownegative = true, $allowzero = true): bool
    {
        if (!is_numeric($integer)) {
            return false;
        }

        if (intval($integer) != floatval($integer)) {
            return false;
        }

        if (!$allownegative && $integer < 0) {
            return false;
        }

        if (!$allowzero && $integer == 0) {
            return false;
        }

        return true;
    }

    public function get_data(): ?object
    {
        $data = parent::get_data();
        if ($data && !$this->get_parent()) {
            $perms = self::get_permission_data($this->get_question(), true);

            $data->learnerperms = $perms['learner']->value();
            $data->evaluatorperms = $perms['evaluator']->value();
            $data->reviewerperms = $perms['reviewer']->value();
        }

        return $data;
    }

    public function validation($data, $files): array
    {
        $errors = parent::validation($data, $files);

        // Question field length validation.
        if (isset($data['question']) && strlen($data['question']) > 255) {
            $errors['question'] = get_string('err_maxlength', 'form', ['format' => 255]);
        }

        // Permissions validation.
        if (!$this->get_parent()) {
            $hasperm = false;
            $perms = self::get_permission_data(null, true);
            foreach ($perms as $roleperms) {
                if ($roleperms->can_answer()) {
                    $hasperm = true;
                    break;
                }

                if (!$this->question->is_question() && $roleperms->can_viewsubmitted()) {
                    $hasperm = true;
                    break;
                }
            }
            if (!$hasperm) {
                $errors['questionperms'] = get_string('error_noonecanview', 'assessment');
            }
        }

        return $errors;
    }

    abstract protected function add_js();

    abstract protected function add_question_fields();

    protected function add_grade_fields()
    {
        $mform = $this->_form;

        $weightgroup = [];
        $weightgroup[] = $mform->createElement('text', 'value', null);
        $weightgroup[] = $mform->createElement(
            'advcheckbox',
            'enable',
            null,
            get_string('question_includegrade', 'mod_assessment')
        );
        $mform->addGroup($weightgroup, 'weight', get_string('question_gradeweight', 'mod_assessment'));
        $mform->disabledIf('weight[value]', 'weight[enable]');
        $mform->setType('weight[value]', PARAM_RAW_TRIMMED);
        $this->freezefields[] = 'weight';
    }

    protected function add_hidden_data()
    {
        $mform = $this->_form;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'stageid');
        $mform->setType('stageid', PARAM_INT);

        $mform->addElement('hidden', 'type');
        $mform->setType('type', PARAM_TEXT);

        $mform->addElement('hidden', 'versionid');
        $mform->setType('versionid', PARAM_INT);

        if (!$this->get_question()->is_gradable()) {
            $mform->addElement('hidden', 'weight');
            $mform->setType('weight', PARAM_FLOAT);
            $mform->setDefault('weight', 0);
        }

        if ($this->get_parent()) {
            $mform->addElement('hidden', 'parentid');
            $mform->setType('parentid', PARAM_INT);

            $mform->addElement('hidden', 'learnerperms');
            $mform->setType('learnerperms', PARAM_INT);

            $mform->addElement('hidden', 'evaluatorperms');
            $mform->setType('evaluatorperms', PARAM_INT);

            $mform->addElement('hidden', 'reviewerperms');
            $mform->setType('reviewerperms', PARAM_INT);
        }
    }

    protected function get_required_data(string $index)
    {
        if (!isset($this->_customdata[$index])) {
            throw new coding_exception('Coding error: ' . $index . ' must be given to the question edit form');
        }
        return $this->_customdata[$index];
    }

    protected function add_current_stage()
    {
        $mform = $this->_form;
        $stage = $this->get_required_data('stage');

        $mform->addElement('static', 'stage', get_string('stage', 'assessment'), $stage->name);
    }

    /**
     * @param bool $isquestion
     * @global renderer_base $OUTPUT
     */
    protected function add_permissions_table($isquestion = true)
    {

        $mform = $this->_form;

        $perms = self::get_permission_data($this->get_question());
        $roles = self::get_roles();

        foreach ($roles as $rolecode => $role) {
            $mform->addElement("header", "header_{$role}", helper\role::get_string($rolecode));
            $mform->setExpanded("header_{$role}");

            $mform->addElement('static', 'questionperms', get_string('questionpermissions', 'assessment'));
            $mform->addHelpButton('questionperms', 'questionpermissions', 'assessment');

            if (!helper\role::is_read_only_role(new role($rolecode))) {
                $mform->addElement(
                    'advcheckbox',
                    "{$role}_cananswer",
                    $isquestion ? get_string('cananswer', 'assessment') : get_string('canview', 'assessment'),
                    null,
                    null,
                    question_perms::CAN_ANSWER
                );
                if ($perms[$role]->can_answer()) {
                    $mform->setDefault("{$role}_cananswer", question_perms::CAN_ANSWER);
                }
                if (!$this->get_version()->is_draft()) {
                    $mform->freeze("{$role}_cananswer");
                }
            }

            $mform->addElement(
                'advcheckbox',
                "{$role}_canviewsubmitted",
                get_string('canviewsubmitted', 'assessment'),
                null,
                null,
                question_perms::CAN_VIEW_SUBMITTED
            );
            if (!$this->get_version()->is_draft()) {
                $mform->freeze("{$role}_canviewsubmitted");
            }

            if ($perms[$role]->can_viewsubmitted()) {
                $mform->setDefault("{$role}_canviewsubmitted", true);
            }


            if ($isquestion) {
                if (!helper\role::is_read_only_role(new role($rolecode))) {
                    $mform->addElement(
                        'advcheckbox',
                        "{$role}_requireanswer",
                        get_string('requireanswer', 'assessment'),
                        null,
                        null,
                        question_perms::IS_REQUIRED
                    );
                    if ($perms[$role]->is_required()) {
                        $mform->setDefault("{$role}_requireanswer", true);
                    }
                    if (!$this->get_version()->is_draft()) {
                        $mform->freeze("{$role}_requireanswer");
                    }
                }

                $mform->addElement(
                    'advcheckbox',
                    "{$role}_canviewother",
                    get_string('canviewother', 'assessment'),
                    null,
                    null,
                    question_perms::CAN_VIEW_OTHER
                );

                if ($perms[$role]->can_viewother()) {
                    $mform->setDefault("{$role}_canviewother", true);
                }

                if (!$this->get_version()->is_draft()) {
                    $mform->freeze("{$role}_canviewother");
                }
            }

            $mform->disabledIf("{$role}_requireanswer", "{$role}_cananswer");

            $mform->disabledIf("{$role}_canviewsubmitted", "{$role}_cananswer", 'checked');
            $mform->disabledIf("{$role}_canviewsubmitted", "{$role}_canviewother", 'checked');

            $mform->disabledIf("{$role}_cananswer", "{$role}_canviewsubmitted", 'checked');
            $mform->disabledIf("{$role}_requireanswer", "{$role}_canviewsubmitted", 'checked');
            $mform->disabledIf("{$role}_canviewother", "{$role}_canviewsubmitted", 'checked');
        }

    }

    protected function validate_grade_fields($data): array
    {
        $errors = [];
        if ($data['weight']['enable']) {
            if (!$this->validate_integer($data['weight']['value'], false, false)) {
                $errors['weight'] = get_string('error:positiveint', 'mod_assessment');
            }
        }
        return $errors;
    }

    protected function freeze_field($field)
    {
        $this->freezefields[$field] = $field;
    }
}
