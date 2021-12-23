<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage assquestion_rating
 */

namespace mod_assessment\question\rating\form\element;

use mod_assessment\model\answer;
use mod_assessment\model\question_element;
use mod_assessment\model\role;
use mod_assessment\model\version;
use mod_assessment\question\element;
use renderer_base;
use totara_form\form\element\text;

class slider extends text implements question_element
{

    use element;

    public $maxval;
    public $minval;
    public $step;

    public function __construct($name, $label, $minval, $maxval)
    {
        $this->minval = $minval;
        $this->maxval = $maxval;
        $this->step = 1;  // TODO: custom steps?

        parent::__construct($name, $label, PARAM_INT);
    }

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
        $context['rvalue'] = $this->get_role_answer($question, $attempt, $questionrole->value());
        $context['minval'] = $this->minval;
        $context['maxval'] = $this->maxval;
        $context['step'] = $this->step;
        $context['strselected'] = get_string('selected', 'assquestion_rating');
        $context['question_template'] = 'assquestion_rating/element_slider';

        return $context;
    }

    public function get_role_answer($question, $attempt, $role)
    {
        $answer = answer::instance([
            'attemptid' => $attempt->id,
            'questionid' => $question->id,
            'role' => $role
        ]);

        $userRole = $this->get_role();

        if ($answer) {
            return $answer->value;
        } elseif ($this->get_field_value() && ($userRole === $role)) {
            return $this->get_field_value();
        } elseif ($question->get_config()->default->enable) {
            return $question->get_config()->default->value;
        }

        return (int)ceil(($this->maxval / 2));
    }
}
