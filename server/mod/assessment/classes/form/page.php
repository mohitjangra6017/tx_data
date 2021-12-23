<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\form;

defined('MOODLE_INTERNAL') || die();

use mod_assessment\factory\assessment_question_factory;
use mod_assessment\helper;
use mod_assessment\model;
use mod_assessment\model\answer;
use mod_assessment\model\attempt;
use mod_assessment\model\question;
use mod_assessment\model\question_perms;
use mod_assessment\model\role;
use mod_assessment\model\version;
use mod_assessment\question\meta\form\collection\meta;
use renderer_base;
use totara_form\form;
use totara_form\form\element;
use totara_form\form\group;

class page extends form
{

    public function get_submitted_data(): array
    {
        return $this->model->get_data();
    }

    protected function definition()
    {
        // Add hidden params.
        $this->model->add(new element\hidden('id', PARAM_INT));
        $this->model->add(new element\hidden('attemptid', PARAM_INT));
        $this->model->add(new element\hidden('page', PARAM_INT));
        $this->model->add(new element\hidden('role', PARAM_INT));
        $this->model->add(new element\hidden('stageid', PARAM_INT));
        $this->model->add(new element\hidden('userid', PARAM_INT));
        $this->model->add(new element\hidden('versionid', PARAM_INT));

        if (empty($this->parameters['questions'])) {
            $this->model->add(new element\static_html('notice', null, get_string('noactions', 'assessment')));
            $actionbtns = $this->model->add(new group\buttons('actionbuttonsgroup'));
            $actionbtns->add(new element\action_button(
                    'submit',
                    get_string('continue', 'assessment'),
                    element\action_button::TYPE_SUBMIT)
            );
            return;
        }

        $islocked = $this->parameters['islocked'];
        // Add questions.
        foreach ($this->parameters['questions'] as $question) {
            $this->add_question($question, $this->model, $islocked);
        }

        $btnsubmit = null;
        if ($this->is_last_page()) {
            if (!$islocked && $this->is_attempt_active()) {
                $btntxt = ($this->is_role_read_only() ? get_string('continue') : get_string('submit', 'assessment'));
                $btnsubmit = new element\action_button(
                    'submit',
                    $btntxt,
                    element\action_button::TYPE_SUBMIT
                );
            }
        } else {
            $key = ($islocked || !$this->is_attempt_active() ? 'nextpage' : 'submitandnextpage');
            $btnsubmit = new element\action_button(
                'nextpage',
                get_string($key, 'assessment'),
                element\action_button::TYPE_SUBMIT
            );
        }

        if ($this->get_version()->is_multistage()) {
            $strcancel = get_string('backtostages', 'assessment');
        } else {
            $strcancel = get_string('backtosummary', 'assessment');
        }

        // Add page controls.
        $actionbtns = $this->model->add(new group\buttons('actionbuttonsgroup'));
        $actionbtns->add(new element\action_button('cancel', $strcancel, element\action_button::TYPE_CANCEL));
        // no need to show save progress if page is locked/inactive or a read-only role is viewing the page
        if (!$islocked && $this->is_attempt_active() && !$this->is_role_read_only()) {
            $actionbtns->add(new element\action_button(
                'save',
                get_string('saveprogress', 'assessment'),
                element\action_button::TYPE_SUBMIT
            ));
        }
        if (!empty($btnsubmit)) {
            $actionbtns->add($btnsubmit);
        }
    }

    public function export_for_template(renderer_base $output): array
    {
        $export = parent::export_for_template($output);
        if (!empty($export['errors'])) {
            $export['errors'][0] = ['message' => get_string('submissionerror', 'assessment')];
        }

        return $export;
    }

    /**
     * @param question[] $questions
     * @param $data
     */
    public function save_answers(array $questions, $data)
    {
        global $USER;

        if (is_array($data)) {
            $data = (object)$data;
        }

        foreach ($questions as $question) {
            $role = $this->get_role();
            if (!$question->check_permission($role, question_perms::CAN_ANSWER)) {
                continue;
            }
            $qid = 'q_' . $question->id;

            if ($question instanceof \mod_assessment\question\meta\model\question) {
                $children = assessment_question_factory::fetch_from_parentid($question->id, $this->get_version()->get_id());
                $this->save_answers($children, $data->$qid);
                continue;
            }

            $answer = answer::make([
                'attemptid' => $this->get_attempt()->id,
                'questionid' => $question->id,
                'role' => $role->value()
            ]);

            $answer->set_userid($USER->id);
            $answer->set_value($question->encode_value($data->$qid, $this));
            $answer->save();

            if ($question->type == 'file') {
                $this->update_file_area($qid, $this->parameters['context'], $answer->id);
            }
        }
    }

    public function get_assessment(): ?model\assessment
    {
        return model\assessment::instance(['id' => $this->get_version()->assessmentid], MUST_EXIST);
    }

    /**
     * @return attempt
     */
    public function get_attempt(): attempt
    {
        return $this->parameters['attempt'];
    }

    /**
     * @return role
     */
    public function get_role(): role
    {
        return new role($this->parameters['role']);
    }

    /**
     * @return version
     */
    public function get_version(): version
    {
        return version::instance(['id' => $this->get_attempt()->versionid], MUST_EXIST);
    }

    /**
     *
     * @return bool
     */
    public function is_role_read_only(): bool
    {
        $role = $this->get_role();
        if (helper\role::is_read_only_role($role)) {
            return true;
        }

        /** @var question $question */
        foreach ($this->parameters['questions'] as $question) {
            if ($question->check_permission($role, model\question_perms::CAN_ANSWER)) {
                return false;
            }
        }

        return true;
    }

    public function is_attempt_active(): bool
    {
        $attempt = $this->get_attempt();
        return $attempt->is_active();
    }

    public function is_last_page(): bool
    {
        global $DB;

        $versionid = $this->model->get_current_data('versionid')['versionid'];
        $stageid = $this->model->get_current_data('stageid')['stageid'];

        switch ($this->model->get_current_data('role')['role']) {
            case helper\role::LEARNER:
                $permsql = 'learnerperms > 0';
                break;
            case helper\role::EVALUATOR:
                $permsql = 'evaluatorperms > 0';
                break;
            case helper\role::REVIEWER:
                $permsql = 'reviewerperms > 0';
                break;
            default:
                $permsql = "1=1";
        }

        // Get VISIBLE questions for role in version stage.
        $sql = "SELECT question.*
                  FROM {assessment_question} question
                  JOIN {assessment_version_question} vq ON vq.questionid = question.id
                 WHERE vq.stageid = :stageid
                       AND vq.versionid = :versionid
                       AND {$permsql}
              ORDER BY vq.sortorder";
        $questions = $DB->get_records_sql($sql, ['stageid' => $stageid, 'versionid' => $versionid]);
        if (empty($questions)) {
            return true;    // If there are no questions, it must be the last page!
        }

        // Filter out any questions that can't be viewed by current role
        $role = $this->model->get_current_data('role')['role'];
        $attemptid = $this->model->get_current_data('attemptid')['attemptid'];
        $attempt = attempt::instance(['id' => $attemptid]);
        foreach ($questions as $id => $rec) {
            $qm = question::class_from_type($rec->type);
            $qm->set_data($rec);
            if (!$qm->can_view($role, $attempt)) {
                unset($questions[$id]);
            }
        }

        return end($questions)->id == end($this->parameters['questions'])->id;
    }

    protected function add_question($question, $model, bool $islocked)
    {
        $element = $model->add($question->get_element());
        $role = new role($this->parameters['role']);
        if (!$islocked
            && $question->check_permission($role, model\question_perms::IS_REQUIRED)
            && method_exists($element, 'set_attribute')
            && array_key_exists('required', $element->get_attributes())
        ) {
            $element->set_attribute('required', true);
        }

        // Meta questions need children added here.  Can't add to self!
        if ($element instanceof meta) {
            foreach ($element->get_children($question->get_id()) as $child) {
                $this->add_question($child, $element, $islocked);
            }
        }
    }
}
