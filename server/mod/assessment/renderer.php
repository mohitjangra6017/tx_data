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

defined('MOODLE_INTERNAL') || die();

use mod_assessment\form\page;
use mod_assessment\helper;
use mod_assessment\helper\role;
use mod_assessment\model\assessment;
use mod_assessment\model\attempt;
use mod_assessment\model\question_perms;
use mod_assessment\model\stage;
use mod_assessment\model\stage_completion;
use mod_assessment\model\version;
use mod_assessment\model\version_question;
use mod_assessment\controller\dashboard_controller;
use mod_assessment\processor\completion;

class mod_assessment_renderer extends core_renderer
{

    /**
     * @param $form
     * @param stage_completion $stagecompletion
     * @param int $viewasrole
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function assessment_page(page $form, stage_completion $stagecompletion, $viewasrole = 0): string
    {
        // Top controls/info.
        $role = $form->get_role();
        $user = $form->get_attempt()->get_user();
        $rolename = role::get_string($role->value());
        $a = (object)['learnername' => fullname($user), 'rolename' => $rolename];

        $backurl = new moodle_url('/mod/assessment/view.php',
            ['id' => $form->get_assessment()->get_cmid(), 'userid' => $form->get_attempt()->userid]);
        if ($viewasrole > 0) {
            $backurl->param('viewasrole', $viewasrole);
        }
        if ($form->get_attempt()->is_archived()) {
            $backurl->param('archived', 1);
        }

        $rendercontext = [
            'backurl' => $backurl,
            'isevaluator' => ($role->value() == role::EVALUATOR),
            'isreviewer' => ($role->value() == role::REVIEWER),
            'status' => $stagecompletion->get_status(),
            'strback' => get_string('backtosummary', 'assessment'),
            'strstatus' => get_string('status', 'assessment'),
            'strviewinguser' => get_string('viewinguserasrole', 'assessment', $a),
        ];

        $output = '';
        $output .= html_writer::start_div('assessment-page');
        $output .= $this->render_from_template('mod_assessment/inprogresscontrols', $rendercontext);
        $output .= $form->render();
        $output .= html_writer::end_div();

        return $output;
    }

    /**
     *
     * @param assessment $assessment
     * @param attempt[] $attempts
     * @param int $role
     * @param int $viewasrole
     * @return string
     */
    public function attempts_table(assessment $assessment, array $attempts, int $role, $viewasrole = 0): string
    {

        $table = new html_table();
        $table->head = [
            get_string('attempt', 'assessment'),
            get_string('roleevaluator', 'assessment'),
            get_string('rolereviewer', 'assessment'),
            get_string('status', 'assessment'),
        ];

        if (!$assessment->hidegrade) {
            $table->head[] = get_string('grade', 'assessment');
        }

        $table->head[] = get_string('review', 'assessment');

        foreach ($attempts as $attempt) {
            $buttons = [];
            if (!in_array($attempt->status, [$attempt::STATUS_INPROGRESS, $attempt::STATUS_NOTSTARTED])) {
                $reviewurl = new moodle_url('/mod/assessment/attempt.php', ['attemptid' => $attempt->id]);
                if (!empty($viewasrole)) {
                    $reviewurl->param('role', $viewasrole);
                }
                $buttons[] = html_writer::link(
                    $reviewurl,
                    get_string('review', 'assessment'),
                    ['class' => 'btn btn-primary link-as-button']
                );
            }
            // Allow reviewer and evaluator to mark as reviewed
            if (!$attempt->is_archived() && in_array($role, [role::EVALUATOR, role::REVIEWER]) && $attempt->needs_review()) {
                $buttons[] = html_writer::link(
                    '#',
                    get_string('markasreviewed', 'assessment'),
                    [
                        'class' => 'btn btn-primary link-as-button',
                        'data-action' => 'markreviewed',
                        'data-attemptid' => $attempt->id,
                    ]
                );
            }

            if ($attempt->is_archived()) { // No controls for archived attempts.
                $fmt = get_string('strftimedate', 'langconfig');
                $buttons[] = '&nbsp;' . html_writer::span("(" . get_string('attemptarchivedon', 'assessment', userdate($attempt->get_timearchived(), $fmt)) . ")");
            }

            $row = new html_table_row();
            $row->cells[] = $attempt->attempt;
            $limit = 10; // Only get the first 10 names, as there may be many assigned users
            $truncatelen = 200;
            $names_list = '';
            if ($names = helper\attempt::get_evaluator_fullnames($attempt, $limit)) {
                $names_list = join(', ', $names);
                $names_list = shorten_text($names_list, $truncatelen);
                $total = $attempt->get_evaluators_count();
                $diff = $total - count($names);
                $names_list .= ($diff > 0 ? ' ' . get_string('plusxmore', 'assessment', $diff) : '');
            }
            $row->cells[] = $names_list;
            $names_list = '';
            if ($names = helper\attempt::get_reviewer_fullnames($attempt, $limit)) {
                $names_list = join(', ', $names);
                $names_list = shorten_text($names_list, $truncatelen);
                $total = $attempt->get_reviewers_count();
                $diff = $total - count($names);
                $names_list .= ($diff > 0 ? ' ' . get_string('plusxmore', 'assessment', $diff) : '');
            }
            $row->cells[] = $names_list;
            $row->cells[] = helper\attempt::status($attempt->get_status());
            if (!$assessment->hidegrade) {
                $row->cells[] = number_format($attempt->grade, 2) . ' %';
            }
            $row->cells[] = implode(' ', $buttons);

            $table->data[] = $row;
        }

        return $this->render($table);
    }

    /**
     * @param version $version
     * @return bool|string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function content_info(mod_assessment\model\version $version)
    {
        $rendercontext = [
            'hasaction' => false,
            'haspreview' => !empty(stage::instances_from_version($version)),
            'status' => get_string($version->get_status(), 'assessment'),
            'strpreview' => get_string('preview', 'assessment'),
            'strstatus' => get_string('status', 'assessment'),
            'strversion' => get_string('version', 'assessment'),
            'urlpreview' => new moodle_url('/mod/assessment/admin/preview.php', ['versionid' => $version->id]),
            'version' => $version->version,
        ];

        if ($version->is_draft()) {
            $rendercontext['hasaction'] = true;
            $rendercontext['straction'] = get_string('activate', 'assessment');
            $rendercontext['urlaction'] = new moodle_url('/mod/assessment/admin/activate.php', ['versionid' => $version->id]);
        } elseif ($version->is_active()) {
            $rendercontext['hasaction'] = true;
            $rendercontext['straction'] = get_string('deactivate', 'assessment');
            $rendercontext['urlaction'] = new moodle_url('/mod/assessment/admin/deactivate.php', ['versionid' => $version->id]);
        }

        return $this->render_from_template('mod_assessment/content_info', $rendercontext);
    }

    /**
     * @param attempt $attempt
     * @param stage_completion $completion
     * @param stage $stage
     * @param int $role
     * @param array $perms
     * @param bool $locked
     * @return string
     * @global moodle_page $PAGE
     */
    public function launch_stage(attempt $attempt, stage_completion $completion, stage $stage, int $role, array $perms, $locked = false): string
    {
        global $PAGE;

        $cananswer = $perms[$role] & question_perms::CAN_ANSWER;

        if ($attempt->is_preview()) {
            $launchstr = get_string('preview', 'assessment');
            $btnclass = 'btn-primary';
        } elseif ($locked) {
            $launchstr = get_string('locked', 'assessment');
            $btnclass = 'btn-default disabled';
        } elseif (!$cananswer || $completion->is_complete()) {
            $launchstr = get_string('viewactivity', 'assessment');
            $btnclass = 'btn-default';
        } else {
            $launchstr = get_string('accessactivity', 'assessment');
            $btnclass = 'btn-primary';
        }

        $launchurl = clone $PAGE->url;
        $launchurl->param('stageid', $stage->id);
        $launchurl->param('role', $role);
        $launchurl->param('attemptid', $attempt->id);
        $launchlink = html_writer::link($launchurl, $launchstr, ['class' => "btn {$btnclass}"]);
        return html_writer::div($launchlink, 'launch');
    }

    /**
     * @param string $currenttab
     * @param int $assessmentid
     * @param int $versionid
     * @return bool|string
     */
    public function navtabs(string $currenttab, int $assessmentid, int $versionid)
    {
        $inactive = [];
        $linkparams = ['id' => $assessmentid, 'versionid' => $versionid];

        $contenturl = new moodle_url('/mod/assessment/admin/content.php', $linkparams);
        $evalrulesurl = new moodle_url('/mod/assessment/admin/assignments/version/rules.php', $linkparams);
        $evalrulesurl->param('role', role::EVALUATOR);
        $revrulesurl = new moodle_url('/mod/assessment/admin/assignments/version/rules.php', $linkparams);
        $revrulesurl->param('role', role::REVIEWER);
        $duedatesurl = new moodle_url('/mod/assessment/admin/duedates.php', $linkparams);
        $versionsurl = new moodle_url('/mod/assessment/admin/versions.php', $linkparams);

        $tabs = [];
        $tabs[] = new tabobject('content', $contenturl, get_string('tab_content', 'assessment'));
        $tabs[] = new tabobject('evaluatorrules', $evalrulesurl, get_string('tab_evaluatorrules', 'assessment'));
        $tabs[] = new tabobject('reviewerrules', $revrulesurl, get_string('tab_reviewerrules', 'assessment'));
        $assessment = assessment::instance(['id' => $assessmentid], MUST_EXIST);
        $version = mod_assessment\model\version::instance_for_edit($assessment);
        // Don't display if draft as page does not display correctly and data cannot be set
        if (!$version->is_draft()) {
            $tabs[] = new tabobject('duedates', $duedatesurl, get_string('tab_duedates', 'assessment'));
        }
        $tabs[] = new tabobject('versions', $versionsurl, get_string('tab_versions', 'assessment'));
        return print_tabs([$tabs], $currenttab, $inactive, [], true);
    }

    /**
     * @param string $currenttab
     * @return bool|string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function dashboard_navtabs(string $currenttab)
    {
        $inactive = [];

        foreach (dashboard_controller::TYPES as $type) {
            $controller = "mod_assessment\\controller\\{$type}dashboard_controller";
            if (!$controller::has_permissions()) {
                continue;
            }
            $tab_url = new moodle_url('/mod/assessment/dashboard.php', ['type' => $type]);

            // Add any extra params to the dashboard URL, if they exist.
            /* @var moodle_url $url */
            $controllerobj = dashboard_controller::get_active_controller($type);
            if ($url = $controllerobj->get_page()->url) {
                if ($params = $url->params()) {
                    foreach ($params as $k => $v) {
                        if ($k != 'type') {
                            $tab_url->param($k, $v);
                        }
                    }
                }

            }

            $tabs[] = new tabobject($type, $tab_url, get_string('tab_dashboard' . $type, 'assessment'));
        }

        if (empty($tabs)) {
            return '';
        }

        return print_tabs([$tabs], $currenttab, $inactive, [], true);
    }

    /**
     * @param mod_assessment\model\assessment $assessment
     * @param string $error
     * @return string
     */
    public function nice_error(assessment $assessment, string $error): string
    {
        $output = '';
        $output .= html_writer::div($error, 'alert alert-warning');
        $output .= $this->continue_button(new moodle_url('/course/view.php', ['id' => $assessment->course]));

        return $output;
    }

    /**
     * @param object $learner {user}
     * @param object[] $evaluators {user}
     * @param object[] $reviewers {user}
     * @param attempt $attempt
     * @return string
     */
    public function participants(object $learner, $evaluators = null, $reviewers = null, $attempt = null): string
    {
        $output = '';
        $output .= html_writer::tag('h4', get_string('participants', 'assessment'));
        $eval_names_list = get_string('none');
        $eval_names = [];
        if (is_array($evaluators) && !empty($evaluators)) {
            $total = ($attempt ? $attempt->get_evaluators_count() : 0);
            foreach ($evaluators as $evaluator) {
                $eval_names[] = fullname($evaluator);
            }
            $eval_names_list = join(', ', $eval_names);
            $diff = $total - count($evaluators);
            if ($diff > 0) {
                $eval_names_list .= ' ... ' . get_string('plusxmore', 'assessment', $diff);
            }
        }
        $rev_names_list = get_string('none');
        $rev_names = [];
        if (is_array($reviewers) && !empty($reviewers)) {
            $total = ($attempt ? $attempt->get_reviewers_count() : 0);
            foreach ($reviewers as $reviewer) {
                $rev_names[] = fullname($reviewer);
            }
            $rev_names_list = join(', ', $rev_names);
            $diff = $total - count($reviewers);
            if ($diff > 0) {
                $rev_names_list .= ' ... ' . get_string('plusxmore', 'assessment', $diff);
            }
        }
        $output .= html_writer::div(get_string('rolelearner', 'assessment') . ': ' . fullname($learner));
        $output .= html_writer::div(get_string('roleevaluator', 'assessment') . ': ' . $eval_names_list);
        $output .= html_writer::div(get_string('rolereviewer', 'assessment') . ': ' . $rev_names_list);

        return $output;
    }

    /**
     * @param $roles
     * @param $selected
     * @return string
     * @throws coding_exception
     */
    public function role_switcher_menu($roles, $selected): string
    {
        global $PAGE;

        $output = '';

        $strings = \mod_assessment\model\role::get_roles();
        $options = [];
        foreach ($roles as $role) {
            $options[$role] = $strings[$role];
        }

        $url = clone $PAGE->url;
        $url->remove_params(['role']);
        $switch_url = $url->out(false);
        $atts = ["onchange" => "document.location='{$switch_url}&role='+$(this).val();"];

        $output .= html_writer::start_div('role-switcher');
        $output .= html_writer::tag('label', get_string('switchrole', 'assessment'));
        $output .= html_writer::select($options, 'role', $selected, false, $atts);
        $output .= html_writer::end_div();

        return $output;
    }

    /**
     * @param assessment $assessment
     * @param version $version
     * @param int $role
     * @param string $page
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function preview_options(
        assessment $assessment,
        mod_assessment\model\version $version,
        $role = role::LEARNER,
        $page = 'top'
    ): string
    {
        global $PAGE;
        $output = '';

        $rolemenu = [
            role::LEARNER => role::get_string(role::LEARNER),
            role::EVALUATOR => role::get_string(role::EVALUATOR),
            role::REVIEWER => role::get_string(role::REVIEWER),
        ];

        $strrole = $rolemenu[$role];
        $a = (object)['activityname' => $assessment->name, 'role' => $strrole];
        $form = new single_select($PAGE->url, 'role', $rolemenu, $role);

        $output .= html_writer::start_div('box assessment-box-preview');
        $output .= html_writer::tag('h3', get_string('previewingxasx', 'assessment', $a));
        $output .= $this->render($form);
        $output .= html_writer::div(get_string('previewing_help', 'assessment', $a));
        $output .= html_writer::end_div();

        if ($page == 'top' || count(mod_assessment\model\version_stage::instances(['versionid' => $version->id])) <= 1) {
            $returnstr = get_string('backtoeditor', 'assessment');
            $returnurl = new moodle_url(
                '/mod/assessment/admin/content.php',
                ['id' => $assessment->id, 'versionid' => $version->id]
            );
        } else {
            $returnstr = get_string('backtostages', 'assessment');
            $returnurl = clone $PAGE->url;
            $returnurl->param('stageid', null);
        }

        $output .= html_writer::link($returnurl, $returnstr, ['class' => 'btn btn-default']);

        return $output;
    }

    /**
     * @param $questions
     * @param version $version
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function stage_questions($questions, version $version): string
    {
        if (empty($questions)) {
            return '';
        }

        $output = '';
        $qlist = [];
        foreach ($questions as $question) {
            $questionversion = version_question::instance(
                ['questionid' => $question->id, 'versionid' => $version->id],
                MUST_EXIST
            );

            $qparams = ['id' => $question->id, 'stageid' => $questionversion->stageid, 'versionid' => $version->id];

            $editurl = new moodle_url('/mod/assessment/admin/editquestion.php', $qparams);
            $deleteurl = new moodle_url('/mod/assessment/admin/deletequestion.php', $qparams);
            $moveupurl = new moodle_url(
                '/mod/assessment/admin/movequestion.php',
                array_merge(['direction' => 'up', 'sesskey' => sesskey()], $qparams)
            );
            $movedownurl = new moodle_url(
                '/mod/assessment/admin/movequestion.php',
                array_merge(['direction' => 'down', 'sesskey' => sesskey()], $qparams)
            );

            $actions = [];
            $actions[] = $this->action_icon($editurl, new pix_icon('t/edit', get_string('edit')));
            if ($version->is_draft()) {
                $actions[] = $this->action_icon($deleteurl, new pix_icon('t/delete', get_string('delete')));
            }

            if ($questionversion->can_moveup()) {
                $actions[] = $this->action_icon($moveupurl, new pix_icon('t/up', get_string('up')));
            } else {
                $actions[] = $this->flex_icon('spacer');
            }

            if ($questionversion->can_movedown()) {
                $actions[] = $this->action_icon($movedownurl, new pix_icon('t/down', get_string('down')));
            } else {
                $actions[] = $this->flex_icon('spacer');
            }

            $qout = '';
            $qout .= html_writer::span(implode('', $actions), 'actions');
            $qout .= html_writer::start_span('name');
            $qout .= html_writer::tag('strong', $question->question) . '<br>';
            $qout .= html_writer::label($question->get_label(), null);
            $qout .= html_writer::end_span();

            $qlist[] = $qout;
        }
        $output .= html_writer::alist($qlist, ['class' => 'questionlist']);
        return $output;
    }

    /**
     *
     * @param stage[] $stages
     * @param version $version
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function stage_table(array $stages, version $version): string
    {
        $output = '';

        // Table head.
        $output .= html_writer::tag('h4', get_string('stages', 'assessment'));

        // Table.
        $table = new html_table();
        $table->head = [
            get_string('stagename', 'assessment'),
            get_string('rolesmustanswer', 'assessment'),
            get_string('rolescananswer', 'assessment'),
            get_string('rolesonlyview', 'assessment'),
            get_string('rolesonlyviewsubmitted', 'assessment'),
            get_string('options', 'assessment'),
        ];

        foreach ($stages as $stage) {
            $versionstage = mod_assessment\model\version_stage::instance(['stageid' => $stage->id, 'versionid' => $version->id]);
            $stageperms = $versionstage->get_stage_permissions();

            $stageurl = new moodle_url(
                '/mod/assessment/admin/content.php',
                ['id' => $version->assessmentid, 'stageid' => $stage->id, 'versionid' => $version->id]
            );
            $editurl = new moodle_url(
                '/mod/assessment/admin/editstage.php',
                ['id' => $stage->id, 'versionid' => $version->id]
            );
            $deleteurl = new moodle_url(
                '/mod/assessment/admin/deletestage.php',
                ['id' => $stage->id, 'versionid' => $version->id]
            );

            foreach ($stageperms as $role => $stageperm) {
                unset($stageperms[$role]);
                $stageperms[role::get_string($role)] = $stageperm;
            }

            $cananswer = array_filter(
                $stageperms,
                function ($perm) {
                    return $perm & question_perms::CAN_ANSWER;
                }
            );
            $canviewother = array_filter(
                $stageperms,
                function ($perm) {
                    return $perm & question_perms::CAN_VIEW_OTHER;
                }
            );
            $mustanswer = array_filter(
                $stageperms,
                function ($perm) {
                    return $perm & question_perms::IS_REQUIRED;
                }
            );
            // Can view after submission is a special case. Here we want to include it even for non-questions.
            $included_non_questions = true;
            $stageperms = $versionstage->get_stage_permissions($included_non_questions);
            foreach ($stageperms as $role => $stageperm) {
                unset($stageperms[$role]);
                $stageperms[role::get_string($role)] = $stageperm;
            }

            $canviewsubmitted = array_filter(
                $stageperms,
                function ($perm) {
                    return $perm & question_perms::CAN_VIEW_SUBMITTED;
                }
            );

            $options = [];
            $options[] = $this->action_icon($editurl, new pix_icon('t/edit', get_string('edit')));
            if ($version->is_draft()) {
                $options[] = $this->action_icon($deleteurl, new pix_icon('t/delete', get_string('delete')));
            }

            $row = new html_table_row();
            $row->cells[] = html_writer::link($stageurl, $stage->name);
            $row->cells[] = implode(', ', array_keys($mustanswer));
            $row->cells[] = implode(', ', array_keys($cananswer));
            $row->cells[] = implode(', ', array_keys($canviewother));
            $row->cells[] = implode(', ', array_keys($canviewsubmitted));
            $row->cells[] = implode('', $options);

            $table->data[] = $row;
        }

        $output .= $this->render($table);
        if ($version->is_draft()) {
            $newurl = new moodle_url('/mod/assessment/admin/editstage.php', ['versionid' => $version->id]);
            $output .= html_writer::link($newurl, get_string('addstage', 'assessment'), ['class' => 'btn btn-primary']);
        }

        return $output;
    }

    /**
     * @param attempt $attempt
     * @param stage[] $stages
     * @param version $version
     * @param object $course {course}
     * @param int $role
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function stages(mod_assessment\model\attempt $attempt, array $stages, version $version, object $course, int $role): string
    {
        $rendercontext = [
            'strstatus' => get_string('status', 'mod_assessment'),
        ];

        if (in_array($role, [role::EVALUATOR, role::REVIEWER])) {
            $rendercontext['strreturn'] = get_string('backtodashboard', 'assessment');
            $rendercontext['urlreturn'] = new moodle_url('/mod/assessment/dashboard.php');

            if ($attempt->is_archived()) {
                $rendercontext['urlreturn']->param('type', 'archived');
                $rendercontext['urlreturn']->param('archived', '1');
            }
        } else {
            $rendercontext['strreturn'] = get_string('backtocourse', 'assessment');
            $rendercontext['urlreturn'] = new moodle_url('/course/view.php', ['id' => $course->id]);
        }

        $previouscompleted = false;
        $stagecounter = 1;
        foreach ($stages as $stage) {
            $completion = mod_assessment\model\stage_completion::make([
                'attemptid' => $attempt->id,
                'stageid' => $stage->id,
                'role' => $role
            ]);
            $completion->set_attempt($attempt);

            // Get completion role text.
            $versionstage = mod_assessment\model\version_stage::instance(['stageid' => $stage->id, 'versionid' => $version->id]);
            $perms = $versionstage->get_stage_permissions();

            $learneranswer = $perms[role::LEARNER] & question_perms::CAN_ANSWER;
            $evaluatoranswer = $perms[role::EVALUATOR] & question_perms::CAN_ANSWER;
            $strcompletionroles = '';
            if ($role == role::EVALUATOR) {
                if ($learneranswer && $evaluatoranswer) {
                    $strcompletionroles = get_string('reqyouandlearner', 'assessment');
                } elseif ($evaluatoranswer) {
                    $strcompletionroles = get_string('reqyou', 'assessment');
                } elseif ($learneranswer) {
                    $strcompletionroles = get_string('reqlearner', 'assessment');
                }
            } elseif ($role == role::LEARNER) {
                if ($learneranswer && $evaluatoranswer) {
                    $strcompletionroles = get_string('reqyouandevaluator', 'assessment');
                } elseif ($evaluatoranswer) {
                    $strcompletionroles = get_string('reqevaluator', 'assessment');
                } elseif ($learneranswer) {
                    $strcompletionroles = get_string('reqyou', 'assessment');
                }
            }

            $locked = ($versionstage->get_locked() && !$previouscompleted);

            // Get stage status.
            $rendercontext['stages'][] = [
                'btnlaunch' => $this->launch_stage($attempt, $completion, $stage, $role, $perms, $locked),
                'name' => $stage->name,
                'number' => $stagecounter++,
                'status' => $completion->get_status(),
                'strrequired' => $strcompletionroles,
            ];

            $completionchecker = new completion();
            $previouscompleted = $completionchecker->is_stage_complete($attempt, $versionstage);
        }

        return $this->render_from_template('mod_assessment/attemptstages', $rendercontext);
    }

    /**
     * @param $versions
     * @return string
     * @throws coding_exception
     */
    public function version_table($versions): string
    {
        global $PAGE;

        $output = '';
        $headers = [
            get_string('version', 'assessment'),
            get_string('timeopened', 'assessment'),
            get_string('timeclosed', 'assessment')
        ];

        $table = new totara_table('assversions');
        $table->define_columns(['version', 'timeopened', 'timeclosed']);
        $table->define_headers($headers);
        $table->define_baseurl($PAGE->url);
        $table->sortable(true);

        ob_start();
        $table->setup();

        foreach ($versions as $version) {
            $versionurl = clone $PAGE->url;
            $versionurl->param('versionid', $version->id);

            $row = [];
            $row[] = html_writer::link($versionurl, get_string('versionx', 'assessment', $version->version));
            $row[] = $version->timeopened ? userdate($version->timeopened, get_string('strftimedate', 'langconfig')) : '';
            $row[] = $version->timeclosed ? userdate($version->timeclosed, get_string('strftimedate', 'langconfig')) : '';
            $table->add_data($row);
        }

        $table->finish_html();
        $output .= ob_get_contents();
        ob_end_clean();

        $newversionurl = clone $PAGE->url;
        $newversionurl->param('newversion', true);
        $newversionurl->param('sesskey', sesskey());
        $output .= html_writer::link(
            $newversionurl,
            get_string('newversion', 'assessment'),
            ['class' => 'btn btn-default newversion']
        );

        return $output;
    }

    /**
     * DEVIOTIS2
     *
     * @param array|false $warnings
     * @return string
     */
    public function activation_warnings($warnings): string
    {

        $output = '';
        if (empty($warnings) || !is_array($warnings)) {
            return $output;
        }

        $output .= html_writer::start_div('activation-warnings');
        $output .= html_writer::div('<strong>' . get_string('ruleusercountwarning', 'assessment') . '</strong>', 'warning-summary');
        $output .= html_writer::empty_tag('br');
        $output .= html_writer::start_div('warnings-list');
        $output .= html_writer::start_tag('ul');

        foreach ($warnings as $msg) {
            $output .= html_writer::tag('li', $msg);
        }

        $output .= html_writer::end_tag('ul');
        $output .= html_writer::end_div();
        $output .= html_writer::end_div();

        return $output;
    }
}
