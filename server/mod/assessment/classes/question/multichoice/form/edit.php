<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package mod_assessment
 * @subpackage assquestion_text
 */

namespace mod_assessment\question\multichoice\form;

use mod_assessment\question\edit_form;
use mod_assessment\question\multichoice;
use mod_assessment\question\multichoice\model\response;
use stdClass;

class edit extends edit_form
{
    protected function add_js(): void
    {
        // JS not required.
    }

    /**
     * @param response $response
     * @return bool
     */
    public static function trim_empty(multichoice\model\response $response): bool
    {
        return trim($response->text) !== '';
    }

    public function add_responses(): void
    {
        $mform = $this->_form;

        $question = $this->get_question();
        $responses = $question->get_responses();

        $mform->addElement('header', 'head_responses', get_string('availableresponses', 'assquestion_multichoice'));

        $repeateloptions = [];
        $repeateloptions['response_text']['type'] = PARAM_TEXT;
        $repeateloptions['response_value']['type'] = PARAM_INT;
        $repeateloptions['response_value']['default'] = 0;
        $repeateloptions['response_penalty']['type'] = PARAM_INT;
        $repeateloptions['response_penalty']['default'] = 0;
        $repeateloptions['response_default']['default'] = false;

        $this->repeat_elements(
            $this->get_response_element(),
            max(3, count($responses)),
            $repeateloptions,
            'response',
            'addresponse',
            1
        );
    }

    /**
     * @return array
     */
    private function get_response_element(): array
    {
        $mform = $this->_form;
        $readonly = !$this->get_version()->is_draft();

        $group = [];
        $el = $mform->createElement('text', 'response_text', get_string('response', 'assquestion_multichoice'));
        $el->updateAttributes(['class' => 'text']);
        $group[] = $el;

        $el = $mform->createElement('text', 'response_value', get_string('responsevalues', 'assquestion_multichoice'));
        $el->updateAttributes(['class' => 'value']);
        if ($readonly) {
            $el->updateAttributes(['readonly' => true]);
        }
        $group[] = $el;

        $el = $mform->createElement('text', 'response_penalty', get_string('responsepenalty', 'assquestion_multichoice'));
        $el->updateAttributes(['class' => 'penalty']);
        if ($readonly) {
            $el->updateAttributes(['readonly' => true]);
        }
        $group[] = $el;

        $el = $mform->createElement('advcheckbox', 'response_default', get_string('defaultselected', 'assquestion_multichoice'));
        $el->updateAttributes(['class' => 'default']);
        $group[] = $el;

        $el = $mform->createElement('html', '<hr>');
        $group[] = $el;

        return $group;
    }

    /**
     * @param array|stdClass $default_values
     */
    public function set_data($default_values): void
    {
        $default_values = (array) $default_values;
        if ($responses = $default_values['config_responses'] ?? null) {
            foreach ($responses as $index => $response) {
                $default_values["response_text[{$index}]"] = $response->text;
                $default_values["response_value[{$index}]"] = $response->value;
                $default_values["response_penalty[{$index}]"] = $response->penalty;
                $default_values["response_default[{$index}]"] = $response->default;
            }
        }

        parent::set_data($default_values);
    }

    /**
     * @return object|null
     */
    public function get_data(): ?object
    {
        if ($data = parent::get_data()) {
            $config_responses = [];
            foreach ($data->response_text as $index => $response_data) {
                $response =
                    new response(
                        $data->response_text[$index],
                        $data->response_value[$index],
                        $data->response_penalty[$index],
                        $data->response_default[$index]
                    );
                $config_responses[] = $response;
            }
            $data->config_responses = $config_responses;
            if ($this->get_version()->is_draft()) {
                $data->weight = $data->weight['enable'] && $data->weight['value'] > 0 ? $data->weight['value'] : 0;
            }
        }

        return $data;
    }

    /**
     * @return string
     */
    public static function get_type(): string
    {
        return 'multichoice';
    }

    /**
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files): array
    {
        $errors = parent::validation($data, $files);

        $errors += $this->validate_grade_fields($data);

        // Validate responses.
        $reperrors = [];
        $valuecount = 0;
        $defaultcount = 0;
        $responses = [];
        foreach ($data['response_text'] as $index => $responsedata) {
            $response =
                new response(
                    $data['response_text'][$index],
                    $data['response_value'][$index],
                    $data['response_penalty'][$index],
                    $data['response_default'][$index]
                );
            $responses[] = $response;
        }
        $responses = array_filter($responses, 'self::trim_empty');

        foreach ($responses as $response) {
            if ($response->value) {
                $valuecount++;
            }

            if ($response->default) {
                $defaultcount++;
            }

            if ($this->validate_points($response->get_raw()['value']) || $this->validate_points($response->get_raw()['penalty'])) {
                $reperrors['badvalue'] = get_string('error:badvalue', 'assquestion_multichoice');
            }
        }

        // Check number of correct answers.
        if (empty($responses)) {
            $reperrors[] = get_string('required');
        } elseif ($valuecount == 0 && $data['weight']['enable']) {
            $reperrors[] = get_string('error:norightanswer', 'assquestion_multichoice');
        } elseif ($valuecount > 1 && $data['config_choicetype'] == multichoice\model\question::CHOICETYPE_SINGLE) {
            $reperrors[] = get_string('error:toomanyanswers', 'assquestion_multichoice');
        }

        // Check for correct/penalty overlap.
        foreach ($responses as $response) {
            if ($response->get_value() && $response->get_penalty()) {
                $reperrors[] = get_string('error:rightandwrong', 'assquestion_multichoice');
                break;
            }
        }

        if ($defaultcount > 1 && $data['config_choicetype'] == multichoice\model\question::CHOICETYPE_SINGLE) {
            $reperrors[] = get_string('error:toomanydefaults', 'assquestion_multichoice');
        }

        if ($data['config_choicetype'] == multichoice\model\question::CHOICETYPE_SINGLE && $response->get_penalty() > 0) {
            $reperrors[] = get_string('error:singlechoice:penalty', 'assquestion_multichoice');
        }


        // Assign errors to return value.
        if (!empty($reperrors)) {
            $errors['question'] = implode('<br>', $reperrors);
        }

        return $errors;
    }

    protected function add_question_fields(): void
    {
        $this->_form->addElement(
            'select',
            'config_choicetype',
            get_string('choicetype', 'assquestion_multichoice'),
            $this->get_choicetype_list()
        );
        $this->_form->addHelpButton('config_choicetype', 'choicetype', 'assquestion_multichoice');
        $this->freeze_field('config_choicetype');

        $this->add_responses();
    }

    /**
     * @param int $points
     * @return bool
     */
    protected function validate_points(int $points): bool
    {
        return !is_numeric($points) || $points < 0 || intval($points) != floatval($points);
    }

    /**
     * @return array
     */
    protected function get_choicetype_list(): array
    {
        return [
            multichoice\model\question::CHOICETYPE_SINGLE => get_string('choicetypesingle', 'assquestion_multichoice'),
            multichoice\model\question::CHOICETYPE_MULTI => get_string('choicetypemulti', 'assquestion_multichoice'),
        ];
    }
}
