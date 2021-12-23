<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\question\meta\form\collection;

use mod_assessment\factory\assessment_question_factory;
use mod_assessment\model\answer;
use mod_assessment\model\question_element;
use mod_assessment\model\role;
use mod_assessment\model\version;
use mod_assessment\question\element;
use renderer_base;
use totara_form\form\group\collection;

defined('MOODLE_INTERNAL') || die();

class meta extends collection implements question_element
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
        $result['label'] = format_string($this->get_question()->question);
        $result['question_number'] = $this->get_question_number();

        foreach (role::get_roles() as $roleid => $rolename) {
            $result['roleoptions'][] = $this->export_for_template_role($output, new role($roleid));
        }

        return $result;
    }

    /**
     * @return array
     */
    public function get_data(): array
    {
        $itemdata = [];
        foreach ($this->get_items() as $item) {
            $itemdata += $item->get_data();
        }
        return [$this->get_name() => $itemdata];
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

        // Oof, this is ugly and fragile.  Can't assign role indexes though, because mustache gets mad.
        $roleindex = 0;
        foreach (role::get_roles() as $roleid => $rolename) {
            if ($roleid == $questionrole->value()) {
                break;
            }
            $roleindex++;
        }

        // Update items to directly return roleoption.
        foreach ($this->get_items() as $index => $item) {
            if (!$item instanceof question_element) {
                continue;
            }

            $context['items'][$index] = array_merge($context['items'][$index], $context['items'][$index]['roleoptions'][$roleindex]);
        }
        $context['question_template'] = 'assquestion_meta/element_meta';

        return $context;
    }

    public function get_field_value()
    {
        return null;
    }

    public function get_role_answer($question, $attempt, $role): ?string
    {
        $answer = answer::instance([
            'attemptid' => $attempt->id,
            'questionid' => $question->id,
            'role' => $role
        ]);

        $userRole = $this->get_role();

        if ($userRole === $role) {
            $answerValue = $answer ? json_decode($answer->value) : $this->get_field_value();
        } else {
            $answerValue = $answer ? json_decode($answer->value) : '';
        }

        return $answerValue;
    }

    public function get_children($questionid): array
    {
        return assessment_question_factory::fetch_from_parentid($questionid, $this->get_version()->get_id());
    }

}
