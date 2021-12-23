<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\question\editor\form\element;

use mod_assessment\model\answer;
use mod_assessment\model\question_element;
use mod_assessment\model\role;
use mod_assessment\model\version;
use mod_assessment\question\element;
use renderer_base;

class editor extends \totara_form\form\element\editor implements question_element
{
    use element;

    /** @var bool */
    protected bool $loadamd = false;

    /**
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array
    {
        $result = parent::export_for_template($output);
        $result['form_item_template'] = 'mod_assessment/question';
        $result['question_number'] = $this->get_question_number();

        foreach (role::get_roles() as $roleid => $rolelabel) {
            $result['roleoptions'][] = $this->export_for_template_role($output, new role($roleid));
        }

        if (!$this->loadamd) {
            $result['amdmodule'] = '';
        }

        return $result;
    }

    public function get_role_answer($question, $attempt, role $role)
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

        // We only care about the text portion here.
        if (is_array($answerValue)) {
            $answerValue = $answerValue['text'];
        }

        return $answerValue;
    }

    public function export_for_template_role(renderer_base $output, role $questionrole): array
    {

        $versionid = $this->get_model()->get_current_data('versionid')['versionid'];
        $version = version::instance(['id' => $versionid], MUST_EXIST);
        $attempt = $this->get_attempt($version);
        $question = $this->get_question();
        $userrole = $this->get_model()->get_current_data('role')['role'];

        $context = parent::export_for_template($output);
        $context = array_merge($context, $this->export_default_template_params($question, $attempt, $questionrole->value(), $userrole));
        $context['rvalue'] = $this->get_role_answer($question, $attempt, $questionrole);
        $context['question_template'] = 'assquestion_editor/element_editor';

        if (!$context['frozen'] && $context['visible']) {
            $this->loadamd = true;
        }

        return $context;
    }
}
