<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package mod_assessment
 * @subpackage assquestion_multichoice
 */

namespace mod_assessment\question\multichoice\form\element;

use mod_assessment\helper;
use mod_assessment\model\answer;
use mod_assessment\model\attempt;
use mod_assessment\model\question_element;
use mod_assessment\model\role;
use mod_assessment\model\version;
use mod_assessment\question\element;
use mod_assessment\question\multichoice\model\question;

class radios extends \totara_form\form\element\radios implements question_element {

    use element;

    public function export_for_template_role(\renderer_base $output, role $questionrole): array {
        $versionid = $this->get_model()->get_current_data('versionid')['versionid'];
        $version = version::instance(['id' => $versionid], MUST_EXIST);
        $attempt = $this->get_attempt($version);
        $question = $this->get_question();
        $userrole = $this->get_model()->get_current_data('role')['role'];

        $context = parent::export_for_template($output);
        $context = array_merge($context, $this->export_default_template_params($question, $attempt, $questionrole->value(), $userrole));
        $options = $this->get_role_options($question, $attempt, $questionrole);

        $context['roptions'] = $options;
        $context['question_template'] = 'assquestion_multichoice/element_radios';
        return $context;
    }

    /**
     * @param \renderer_base $output
     * @return array
     */
    public function export_for_template(\renderer_base $output) {
        $role = $this->get_model()->get_current_data('role')['role'];

        $result = parent::export_for_template($output);
        $result['form_item_template'] = 'mod_assessment/question';
        $result['question_number'] = $this->get_question_number();

        foreach (role::get_roles() as $roleid => $rolename) {
            $result['roleoptions'][] = $this->export_for_template_role($output, new role($roleid));
        }

        return $result;
    }

    public function get_role_options(question $question, attempt $attempt, role $role) {
        $options = [];

        $answer = answer::instance([
            'attemptid' => $attempt->id,
            'questionid' => $question->id,
            'role' => $role->value(),
        ]);

        $userRole = $this->get_role();

        foreach ($question->get_responses() as $i => $response) {

            if (isset($answer->value)) {
                $currentValue = json_decode($answer->value);
            } else if ($this->get_field_value()) {
                if ($userRole === $role) {
                    $currentValue = $this->get_field_value();
                } else {
                    $currentValue = [];
                }
            } else {
                $currentValue = $response->default;
            }

            $checked = in_array($response->text, (array)$currentValue) ? true : false;

            $options[] = [
                'checked' => $checked,
                'oid' => $this->get_id() . '___rd_' . $i,
                'text' => clean_param($response->text, PARAM_CLEANHTML),
                'value' => $response->text,
            ];
        }
        return $options;
    }
}
