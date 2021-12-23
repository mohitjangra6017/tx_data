<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\question\datetime\form\element;

use core_date;
use Exception;
use mod_assessment\model\answer;
use mod_assessment\model\attempt;
use mod_assessment\model\question;
use mod_assessment\model\question_element;
use mod_assessment\model\role;
use mod_assessment\model\version;
use mod_assessment\question\element;
use renderer_base;

class datetime extends \totara_form\form\element\datetime implements question_element
{
    use element;

    public function export_for_template_role(renderer_base $output, role $questionrole): array
    {
        $versionid = $this->get_model()->get_current_data('versionid')['versionid'];
        $version = version::instance(['id' => $versionid], MUST_EXIST);
        $attempt = $this->get_attempt($version);
        $question = $this->get_question();
        $userrole = $this->get_model()->get_current_data('role')['role'];

        $context = $this->export_default_template_params($question, $attempt, $questionrole->value(), $userrole);
        $context = array_merge($context, (array)$question->get_config());
        $context['rvalue'] = $this->get_role_answer($question, $attempt, $questionrole);
        $context['question_template'] = 'assquestion_datetime/element_datetime';
        $context['id'] = $this->get_id();
        $context['name'] = $this->get_name();

        foreach (core_date::get_list_of_timezones() as $value => $label) {
            $context['timezones'][] = ['value' => $value, 'label' => $label];
        }

        return $context;
    }

    public function export_for_template(renderer_base $output): array
    {
        $this->get_model()->require_finalised();
        $result = [
            'form_item_template' => 'mod_assessment/question',
            'name__' . $this->get_name() => true,
            'name' => $this->get_name(),
            'id' => $this->get_id(),
            'label' => format_string($this->label),
            'question_number' => $this->get_question_number(),
        ];

        foreach (role::get_roles() as $roleid => $rolelabel) {
            $result['roleoptions'][] = $this->export_for_template_role($output, new role($roleid));
        }

        return $result;
    }

    public function get_role_answer(question $question, attempt $attempt, role $role): ?string
    {
        $answer = answer::instance([
            'attemptid' => $attempt->id,
            'questionid' => $question->id,
            'role' => $role->value()
        ]);

        $userRole = $this->get_role();

        if ($userRole === $role) {
            $answerValue = $answer ? json_decode($answer->value) : $this->get_field_value();
        } else {
            $answerValue = $answer ? json_decode($answer->value) : '';
        }

        return $answerValue ? $this->convert_to_iso($answerValue, $this->tz) : null;
    }

    public function get_data(): array
    {
        $name = $this->get_name();
        $model = $this->get_model();

        if ($this->is_frozen()) {
            $current = $model->get_current_data($name);
            if (isset($current[$name])) {
                return $current;
            }
            return array($name => null);
        }

        $data = $this->get_model()->get_raw_post_data($name);
        if ($data === null) {
            // No value in _POST or invalid value format, most likely disabled element.
            $value = $model->get_current_data($name);
            return array($name => $this->convert_to_timestamp($value, 99));
        }

        return array($name => $this->convert_to_timestamp($data, 99));
    }

    public function get_field_value()
    {
        return null;
    }

    protected function convert_to_iso($time, $tz): string
    {
        if (is_array($time) or is_array($tz)) {
            return '';
        }

        if ($time === null) {
            return '';
        }

        $dateformat = get_string('datepickerlongyearparseformat', 'totara_core');
        if ($this->get_question()->get_config()->showtime ?? false) {
            $dateformat .= ' h:s a';
        }

        $datetime = new \DateTime('@' . (int)$time);
        $datetime->setTimezone(core_date::get_user_timezone_object($tz));
        return $datetime->format($dateformat);
    }

    protected function convert_to_timestamp($isodate, $tz): ?int
    {
        if (is_array($isodate) or is_array($tz)) {
            return null;
        }

        if ($isodate === '') {
            return null;
        }

        $dateformat = get_string('datepickerlongyearparseformat', 'totara_core');
        if ($this->get_question()->get_config()->showtime ?? false) {
            $dateformat .= ' h:s a';
        }

        try {
            $datetime = \DateTime::createFromFormat($dateformat, $isodate);
            return $datetime->getTimestamp();
        } catch (Exception $e) {
            return null;
        }
    }
}
