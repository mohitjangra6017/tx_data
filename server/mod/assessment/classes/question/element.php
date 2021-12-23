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

namespace mod_assessment\question;

use mod_assessment\factory\assessment_question_factory;
use mod_assessment\helper;
use mod_assessment\model\assessment;
use mod_assessment\model\attempt;
use mod_assessment\model\question;
use mod_assessment\model\question_element;
use mod_assessment\model\question_perms;
use mod_assessment\model\role;
use mod_assessment\model\stage_completion;
use mod_assessment\model\version;
use mod_assessment\model\version_question;

trait element
{

    /**
     * @param question $question
     * @param attempt $attempt
     * @param int $questionrole
     * @param int $userrole
     * @return array
     */
    public function export_default_template_params(question $question, attempt $attempt, int $questionrole, int $userrole): array
    {
        global $USER;

        $versionq = version_question::instance([
            'questionid' => $question->id,
            'versionid' => $attempt->versionid
        ], MUST_EXIST);

        if ($versionq->get_parentid()) {
            $parentq = assessment_question_factory::fetch(['id' => $versionq->get_parentid()]);
            $params = $this->export_default_template_params($parentq, $attempt, $questionrole, $userrole);
            $params['isquestion'] = $question->is_question();
            return $params;
        }

        $stagecompletion = stage_completion::make([
            'attemptid' => $attempt->id,
            'stageid' => $versionq->stageid,
            'role' => $userrole
        ]);

        $questionrole_stagecompletion = stage_completion::make([
            'attemptid' => $attempt->id,
            'stageid' => $versionq->stageid,
            'role' => $questionrole
        ]);

        $params = [];
        $params['question_number'] = $this->get_question_number();

        // Get permissions.
        $params['required'] = $question->check_permission(new role($questionrole), question_perms::IS_REQUIRED);
        if ($questionrole == $userrole) {
            $params['frozen'] = $stagecompletion->is_complete();
            $params['visible'] = $question->check_permission(new role($userrole), question_perms::CAN_ANSWER);
        } else {
            $params['frozen'] = true;
            $params['visible'] = $question->check_permission(new role($userrole), question_perms::CAN_VIEW_OTHER) &&
                $question->check_permission(new role($questionrole), question_perms::CAN_ANSWER);
        }

        $params['preview'] = $attempt->is_preview(); // Used for templates to display differently on preview if necessary.
        $params['isquestion'] = $question->is_question();

        $evaluator_answer = $question->get_answer($attempt->id, helper\role::EVALUATOR);
        $evaluator = ($evaluator_answer ? $evaluator_answer->get_user() : null);
        if (empty($evaluator) && !$params['preview'] && $attempt->is_single_evaluator()) {
            if ($evaluators = $attempt->get_evaluators()) {
                $evaluator = array_pop($evaluators);
            }
        }
        $str_evaluator = get_string('roleevaluator', 'assessment');
        $evaluator_name = ($evaluator ? fullname($evaluator) : $str_evaluator);

        $reviewer_answer = $question->get_answer($attempt->id, helper\role::REVIEWER);
        $reviewer = ($reviewer_answer ? $reviewer_answer->get_user() : null);
        if (empty($reviewer) && !$params['preview'] && $attempt->is_single_reviewer()) {
            if ($reviewers = $attempt->get_reviewers()) {
                $reviewer = array_pop($reviewers);
            }
        }
        $str_reviewer = get_string('rolereviewer', 'assessment');
        $reviewer_name = ($reviewer ? fullname($reviewer) : $str_reviewer);

        // Checking if user can view answer after submission, if so only show the version of the question
        // element for the role(s) who submitted a value for it.
        $params['visiblesubmitted'] = false;
        $questionrole_answer = $question->get_answer($attempt->id, $questionrole);
        $params['roleanswer'] = ($questionrole_answer && isset($questionrole_answer->value) ? $questionrole_answer->value : '');
        $params['stagecompleted'] = $questionrole_stagecompletion->is_complete();

        if ($question->check_permission(new role($userrole), question_perms::CAN_VIEW_SUBMITTED)) {
            if ($questionrole_answer && strlen($questionrole_answer->value) > 0) {
                $params['visiblesubmitted'] = $params['stagecompleted'];
            }
            // Non-answerable questions don't need a submitted answer but the question's stage must
            // have been submitted by role for whom the question pertains
            elseif (!$question->is_question()) {
                $params['visiblesubmitted'] = $params['stagecompleted'];
            }
            $params['visible'] |= $params['visiblesubmitted'];
        }

        $learner_name = fullname($attempt->get_user());

        // Get role labels.
        $params['role'] = $questionrole;
        if ($questionrole == $userrole) {
            $params['rolelabel'] = get_string('youranswer', 'assessment');

            if ($userrole == helper\role::EVALUATOR && $evaluator) {
                if (!empty($evaluator_answer) && $evaluator_answer->userid != $USER->id) {
                    $params['rolelabel'] = get_string('xuseranswer', 'assessment', $evaluator_name);
                }
            }
        } else {
            switch ($questionrole) {
                case helper\role::LEARNER:
                    $user = $learner_name;
                    break;
                case helper\role::EVALUATOR:
                    $user = $evaluator_name;
                    break;
                case helper\role::REVIEWER:
                    $user = $reviewer_name;
                    break;
            }
            $params['rolelabel'] = get_string('xuseranswer', 'assessment', $user);
        }

        return $params;
    }

    public function get_assessment(): ?assessment
    {
        return assessment::instance(['id' => $this->get_version()->assessmentid], MUST_EXIST);
    }

    public function get_attempt($version): ?attempt
    {
        $attemptid = $this->get_model()->get_current_data('attemptid')['attemptid'];
        if ($attemptid) {
            return attempt::instance(['id' => $attemptid], MUST_EXIST);
        }

        return attempt::instance_preview($version);
    }

    public function get_question(): ?question
    {
        $id = substr($this->get_name(), 2);
        return question::instance(['id' => $id], MUST_EXIST);
    }

    public function get_question_number(): ?int
    {
        $versionq = version_question::instance([
            'questionid' => $this->get_question()->get_id(),
            'versionid' => $this->get_version()->get_id()
        ]);

        return $versionq->sortorder;
    }

    public function get_role()
    {
        return $this->get_model()->get_current_data('role')['role'];
    }

    public function get_version(): ?version
    {
        $versionid = $this->get_model()->get_current_data('versionid')['versionid'];
        return version::instance(['id' => $versionid], MUST_EXIST);
    }

}
