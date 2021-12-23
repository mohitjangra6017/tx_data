<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage assquestion_text
 */

namespace mod_assessment\question\text\form\element;

use mod_assessment\model\answer;
use mod_assessment\model\question_element;
use mod_assessment\model\role;
use mod_assessment\model\version;
use mod_assessment\question\element;
use renderer_base;

class text extends \totara_form\form\element\text implements question_element
{
    use element;

    /**
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array
    {
        $result = parent::export_for_template($output);
        $result['form_item_template'] = 'mod_assessment/question';
        $result['question_number'] = $this->get_question_number();

        foreach (role::get_roles() as $roleid => $rolename) {
            $result['roleoptions'][] = $this->export_for_template_role($output, new role($roleid));
        }

        return $result;
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
        $context['question_template'] = 'assquestion_text/element_text';

        return $context;
    }

    public function get_role_answer($question, $attempt, role $role): string
    {
        $answer = answer::instance([
            'attemptid' => $attempt->id,
            'questionid' => $question->id,
            'role' => $role->value(),
        ]);

        $userRole = $this->get_role();

        if ($userRole === $role) {
            $answerValue = $answer ? json_decode($answer->value) : $this->get_field_value();
        } else {
            $answerValue = $answer ? json_decode($answer->value) : '';
        }

        return $answerValue;
    }
}
