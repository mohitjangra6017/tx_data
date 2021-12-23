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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_assessment_mod_form extends moodleform_mod
{

    protected function definition()
    {
        $mform = $this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('name'), array('size' => '64'));
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->setType('name', PARAM_TEXT);

        $default = get_config('assessment', 'defaulthidegradeinoverview');
        $mform->addElement('hidden', 'hidegrade', $default);
        $mform->setType('hidegrade', PARAM_INT);
        $mform->setDefault('hidegrade', $default);

        $this->standard_intro_elements();
        $this->custom_grading_coursemodule_elements();

        // Extra restrictions on attempts.
        $mform->addElement('header', 'security', get_string('extraattemptrestrictions', 'assessment'));
        $mform->addElement('advcheckbox', 'extraattempts', get_string('allowextraattempts', 'assessment'));

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }

    /**
     * Add the standard grading elements, then customize for assessment
     *
     * @todo: create multiple grade methods
     * @todo: get grade from default instead of hard-coded 0
     * @todo: get default attempts from global settings
     */
    protected function custom_grading_coursemodule_elements()
    {
        $mform = $this->_form;
        $this->standard_grading_coursemodule_elements();

        $grade = 0;
        $mform->removeElement('grade');
        $mform->addElement('hidden', 'grade', $grade);
        $mform->setType('grade', PARAM_FLOAT);

        // Number of attempts.
        $attemptoptions = [mod_assessment\helper\attempt::UNLIMITED => get_string('unlimited')] + range(0, 10);
        unset($attemptoptions[0]);
        $mform->addElement('select', 'attempts', get_string('attemptsallowed', 'quiz'), $attemptoptions);

        // Grading method.
        $mform->addElement('hidden', 'grademethod');
        $mform->setDefault('grademethod', mod_assessment\helper\grade::METHOD_HIGHEST);
        $mform->setType('grademethod', PARAM_INT);
        $mform->addElement(
            'static',
            'grademethodstatic',
            get_string('grademethod', 'assessment'),
            get_string('gradehighest', 'assessment')
        );
        $mform->addHelpButton('grademethodstatic', 'grademethod', 'assessment');

    }

    public function add_completion_rules(): array
    {
        $mform = $this->_form;

        $items = ['completionstatus'];

        $group = array();
        $group[] = $mform->createElement('advcheckbox', 'requirecomplete', null, get_string('requirecomplete', 'assessment'));
        $group[] = $mform->createElement('advcheckbox', 'requirepass', null, get_string('requirepass', 'assessment'));

        $mform->disabledIf('requirepass', 'requirecomplete');
        $mform->addGroup($group, 'completionstatus', get_string('completionstatus', 'assessment'), ' &nbsp; ', false);
        $mform->addHelpButton('completionstatus', 'completionstatus', 'assessment');

        return $items;
    }

    /**
     * Called during validation. Indicates whether any module-specific completion rules are selected
     *
     * @param array $data
     * @return bool
     */
    public function completion_rule_enabled($data): bool
    {
        return !empty($data['requirecomplete']) || !empty($data['requirepass']);
    }

    public function get_data(): ?object
    {
        if ($data = parent::get_data()) {
            // Convert the checkbox data to set statuses.
            if (isset($data->requirepass) && $data->requirepass) {
                $data->statusrequired = mod_assessment\model\assessment::STATUS_PASSED;
            } elseif (isset($data->requirecomplete) && $data->requirecomplete) {
                $data->statusrequired = mod_assessment\model\assessment::STATUS_COMPLETE;
            }
        }
        return $data;
    }

    public function set_data($data)
    {
        // Convert status required to checkbox data.
        if (!empty($data->statusrequired)) {
            switch ($data->statusrequired) {
                case mod_assessment\model\assessment::STATUS_PASSED:
                    $data->requirepass = true;
                case mod_assessment\model\assessment::STATUS_COMPLETE:
                    $data->requirecomplete = true;
                    break;
            }
        }
        parent::set_data($data);
    }

    public function validation($data, $files): array
    {
        $errors = parent::validation($data, $files);
        if ((empty($data['gradepass']) || $data['gradepass'] <= 0) && !empty($data['requirepass'])) {
            $errors['gradepass'] = get_string('error_gradepassrequired', 'assessment');
        }
        return $errors;
    }
}
