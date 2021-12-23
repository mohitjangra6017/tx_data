<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage assquestion_rating
 */

namespace mod_assessment\question\rating\form;

use mod_assessment\question\edit_form;

defined('MOODLE_INTERNAL') || die();

class edit extends edit_form
{

    public function get_data(): ?object
    {
        $data = parent::get_data();
        if ($data) {
            if ($this->get_version()->is_draft()) {
                $data->weight = $data->weight['enable'] && $data->weight['value'] > 0 ? $data->weight['value'] : 0;
            }
        }

        return $data;
    }

    public static function get_type(): string
    {
        return 'rating';
    }

    public function set_data($default_values)
    {
        $default_values['config_default'] = [
            'enable' => !empty($default_values['config_default']->value),
            'value' => $default_values['config_default']->value ?? null
        ];

        parent::set_data($default_values);
    }

    public function validation($data, $files): array
    {
        $errors = parent::validation($data, $files);

        $errors += $this->validate_grade_fields($data);

        if ($data['config_default']['enable']) {
            $default = $data['config_default']['value'];
            if ($default < $data['config_minval'] || $default > $data['config_maxval']) {
                $errors['config_default'] = get_string('error:outsiderange', 'assquestion_rating');
            }
        }

        if ($data['config_minval'] >= $data['config_maxval']) {
            $errors['config_minval'] = get_string('error_badrange', 'assquestion_rating');
        }

        if ($this->validate_value($data['config_maxval'])) {
            $errors['config_maxval'] = get_string('error:badvalue', 'assquestion_rating');
        }

        if ($this->validate_value($data['config_minval'])) {
            $errors['config_minval'] = get_string('error:badvalue', 'assquestion_rating');
        }

        return $errors;
    }

    protected function add_js()
    {
        // No JS required.
    }

    protected function add_question_fields()
    {
        $this->_form->addElement('header', 'head_responses', get_string('availableresponses', 'assquestion_rating'));

        $this->_form->addElement('text', 'config_minval', get_string('minval', 'assquestion_rating'));
        $this->_form->addHelpButton('config_minval', 'minval', 'assquestion_rating');
        $this->_form->addRule('config_minval', get_string('required'), 'required');
        $this->_form->setType('config_minval', PARAM_RAW_TRIMMED);
        $this->freezefields[] = 'config_minval';

        $this->_form->addElement('text', 'config_maxval', get_string('maxval', 'assquestion_rating'));
        $this->_form->addHelpButton('config_maxval', 'maxval', 'assquestion_rating');
        $this->_form->addRule('config_maxval', get_string('required'), 'required');
        $this->_form->setType('config_maxval', PARAM_RAW_TRIMMED);
        $this->freezefields[] = 'config_maxval';

        $defaultgroup = [];
        $defaultgroup[] = $this->_form->createElement('text', 'value');
        $defaultgroup[] = $this->_form->createElement('advcheckbox', 'enable', null, get_string('usedefault', 'assquestion_rating'));
        $this->_form->addGroup($defaultgroup, 'config_default', get_string('default', 'assquestion_rating'));
        $this->_form->disabledIf('config_default[value]', 'config_default[enable]');
        $this->_form->setType('config_default[value]', PARAM_INT);
    }

    public function validate_value($value): bool
    {
        return !is_numeric($value) || $value < 0 || intval($value) != floatval($value);
    }
}
