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

use core\notification;
use mod_assessment\event\attempt_created;
use mod_assessment\event\stage_completed;
use mod_assessment\form\page;
use mod_assessment\helper\role;
use mod_assessment\model;
use totara_form\file_area;

require_once(__DIR__ . '/../../config.php');

global $OUTPUT, $PAGE, $USER;

$assessmentid = optional_param('assessmentid', null, PARAM_INT);
$attemptid = optional_param('attemptid', null, PARAM_INT);
$learnerid = optional_param('userid', null, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$stageid = optional_param('stageid', null, PARAM_INT);
$viewingrole = optional_param('role', null, PARAM_INT);

$viewinguserid = $USER->id;

// If we are viewing as another role (e.g. evaluator), ensure we fetch the active attempt for the learner and don't create a new one for ourselves.
if (has_capability('mod/assessment:viewasanotherrole', context_system::instance()) && !empty($viewingrole)) {
    if (empty($attemptid) && !empty($learnerid)) {
        $viewinguserid = $learnerid;
    }
}

require_login();

if (!$assessmentid && !$attemptid) {
    throw new Exception(get_string('error:noattemptselected', 'mod_assessment'));
} elseif ($assessmentid) {
    $assessment = model\assessment::instance(['id' => $assessmentid], MUST_EXIST);

    $version = model\version::active_instance($assessment);
    if (!$version) {
        throw new Exception(get_string('error_inactive', 'assessment'));
    }

    // Try to grab the active attempt.
    try {
        $attempt = model\attempt::instance_active($version, $viewinguserid);
    } catch (Exception $ex) {
        // Check user login before creating an attempt.
        list($course, $cm) = get_course_and_cm_from_cmid($assessment->get_cmid(), 'assessment');
        require_login($course, null, $cm);

        if (mod_assessment\helper\attempt::can_make_new_attempt($assessment, $viewinguserid)) {
            $attempt = new model\attempt();
            $attempt->set_status($attempt::STATUS_NOTSTARTED);
            $attempt->set_userid($viewinguserid);
            $attempt->set_versionid($version->id);
            $attempt->save();

            $context = context_module::instance($cm->id);
            attempt_created::create_from_attempt($attempt, $context)->trigger();

            // We need to refresh the role assignments for this user
            if (!$version->singleevaluator || !$version->singlereviewer) {
                // @todo: if we're logging need to pass a progress trace
                mod_assessment\processor\role_assignments::update_role_assignments($assessment, $attempt->userid);
                $attempt = model\attempt::instance(['id' => $attempt->id]); // reload the attempt with the new role users
            }
        } else {
            $pageurl = new moodle_url('/mod/assessment/view.php', ['userid' => $learnerid, 'page' => $page, 'stageid' => $stageid]);
            $PAGE->set_url($pageurl);
            $renderer = $PAGE->get_renderer('assessment');

            echo $OUTPUT->header();
            echo $OUTPUT->heading($assessment->name);
            echo $renderer->nice_error($assessment, get_string('error_noavailableattempts', 'assessment'));
            echo $OUTPUT->footer();
            exit();
        }
    }
} else {
    $attempt = model\attempt::instance(['id' => $attemptid], MUST_EXIST);
    $version = model\version::instance(['id' => $attempt->versionid], MUST_EXIST);
    $assessment = model\assessment::instance(['id' => $version->assessmentid], MUST_EXIST);
}

list($course, $cm) = get_course_and_cm_from_cmid($assessment->get_cmid(), 'assessment');
$context = context_module::instance($cm->id);

if ($context->is_user_access_prevented()) {
    throw new Exception(get_string('error:noaccess', 'mod_assessment'));
}

$learnerid = $learnerid ?? $USER->id;

// Check which role current user is actually assigned to.
try {
    $validroles = mod_assessment\helper\role::get_attempt_roles($attempt, $USER->id); // throws exception if no valid role
} catch (Exception $e) {
    if (has_capability('mod/assessment:viewasanotherrole', context_system::instance()) && !empty($viewingrole)) {
        $validroles[] = $viewingrole;
    } else {
        throw $e;
    }
}
$role = $validroles[0]; // Default to first valid role.

// Override with requested viewing role, if valid.
if ($viewingrole && in_array($viewingrole, $validroles)) {
    $role = $viewingrole;
}

// Set page context.
$role == mod_assessment\helper\role::LEARNER ? require_login($course, null, $cm) : $PAGE->set_cm($cm);
$urlparams = ['attemptid' => $attemptid, 'userid' => $learnerid, 'page' => $page, 'stageid' => $stageid];
$PAGE->set_url(new moodle_url('/mod/assessment/attempt.php', $urlparams));

$renderer = $PAGE->get_renderer('assessment');

// IOTIS2 Start
// If the reviewer and evaluator are the same people, need to be able to switch role
$roleswitcher = '';
if (count($validroles) > 1) {
    $roleswitcher = $renderer->role_switcher_menu($validroles, $role);
}

$is_evaluator_valid = $attempt->is_evaluator_valid(); // Check if *any* existing, assigned evaluators are still valid.
if (!$is_evaluator_valid) {
    if ($version->singleevaluator) {
        $params = ['role' => role::EVALUATOR, 'attemptid' => $attempt->id];
        $chooseevaluatorurl = new moodle_url('/mod/assessment/chooseroleuser.php', $params);
        redirect($chooseevaluatorurl);
    } else {
        notification::add(get_string('noevaluatorsassigned', 'assessment'));
    }
}
$is_reviewer_assigned = $attempt->is_reviewer_assigned();
$is_reviewer_valid = $attempt->is_reviewer_valid(); // Check if *any* existing, assigned reviewers are still valid.
if (!$is_reviewer_valid || !$is_reviewer_assigned) {
    if ($version->singlereviewer) {
        // If assessment version is set to only a single reviewer then further checks need to be made
        // to determine if a reviewer actually needs be assigned.
        if ($version->has_question_permissions_for_reviewer()) {
            if ($version->has_reviewer_rules()) {
                $params = ['role' => role::REVIEWER, 'attemptid' => $attempt->id];
                $choosereviewerurl = new moodle_url('/mod/assessment/chooseroleuser.php', $params);
                redirect($choosereviewerurl);
            } else { // Shouldn't happen (since this scenario is prevented at activation), but let's handle it anyway.
                throw new Exception(get_string('error_noreviewerrules', 'assessment'));
            }
        }
    }
}
// IOTIS2 End

// Set the stage - Ha!
$pagecontent = '';
$stages = model\stage::instances_from_version($version);
if ($stageid) {
    $stage = model\stage::instance(['id' => $stageid], MUST_EXIST);
} elseif (!$version->is_multistage()) {
    // Set the stage if only one exists.
    $stage = current($stages);
}

if (isset($stage)) {
    $PAGE->requires->js_call_amd('mod_assessment/attempt', 'initialize');

    // Start attempt.
    if ($attempt->status == $attempt::STATUS_NOTSTARTED) {
        $attempt->set_status($attempt::STATUS_INPROGRESS);
        $attempt->set_timestarted(time());
        $attempt->save();
    }

    // Start stage for role.
    $completion = model\stage_completion::make([
        'attemptid' => $attempt->id,
        'stageid' => $stage->id,
        'role' => $role
    ]);

    if (!$completion->is_started()) {
        $completion->set_timestarted(time());
        $completion->set_userid($USER->id);
        $completion->save();
    }

    $versionstage = model\version_stage::instance(
        ['stageid' => $stage->id, 'versionid' => $version->id],
        MUST_EXIST
    );
    $questions = model\question::instances_from_versionstage(
        $versionstage,
        $stage->newpage,
        $page * $stage->newpage
    );
    // Pass attempt to can_view, filter out any that current role can't view.
    $questions = array_filter($questions, function (model\question $question) use ($role, $attempt) {
        return $question->can_view($role, $attempt);
    });

    $questiondata = [];
    foreach ($questions as $question) {
        $answer = model\answer::instance(
            [
                'attemptid' => $attempt->id,
                'questionid' => $question->id,
                'role' => $role
            ]
        );

        if ($question->get_type() == 'file') {
            $questiondata["q_{$question->id}"] = new file_area(
                $context,
                'mod_assessment',
                'answer',
                (isset($answer->id) ? $answer->id : null)
            );
        } elseif ($answer) {
            $questiondata["q_{$question->id}"] = json_decode($answer->value);
        } else {
            $questiondata["q_{$question->id}"] = $question->get_default();
        }
    }

    $pageparams = [
        'id' => $cm->id,
        'attemptid' => $attempt->id,
        'role' => $role,
        'stageid' => $stage->id,
        'page' => $page,
        'userid' => $attempt->userid,
        'versionid' => $version->id
    ];

    $pageparams = $pageparams + array_filter($questiondata);
    $form = new page(
        $pageparams,
        ['context' => $context, 'attempt' => $attempt, 'role' => $role, 'questions' => $questions, 'islocked' => $completion->is_complete()]
    );

    // Process the form.
    if ($version->is_multistage()) {
        $backurl = clone $PAGE->url;
        $backurl->param('page', null);
        $backurl->param('stageid', null);
        $backurl->param('role', $role);
    } else {
        $backurl = new moodle_url('/mod/assessment/view.php', ['id' => $assessment->get_cmid(), 'userid' => $learnerid]);
    }

    if (!empty($viewingrole)) {
        $backurl->param('viewasrole', $viewingrole);
    }
    if ($attempt->is_archived()) {
        $backurl->param('archived', 1);
    }

    if ($form->is_cancelled()) {
        redirect($backurl);
    }

    $formdata = $form->get_submitted_data();
    $formdata = (isset($formdata['submit']) && $formdata['submit'] ? $form->get_data() : $formdata);
    if ($formdata) {
        $data = (object)$formdata;
        // Only save the answers if the stage isn't complete and not viewing as read-only role.
        if (!$completion->is_complete() && !role::is_read_only_role(new model\role($role))) {
            $form->save_answers($form->get_parameters()['questions'], $data);
        }

        if (!empty($data->save)) {
            redirect($backurl);
        }

        if (!empty($data->nextpage)) {
            $nextpage = clone $PAGE->url;
            $nextpage->param('page', $page + 1);
            $nextpage->param('role', $role);
            redirect($nextpage);
        }

        if (!empty($data->submit)) {
            // Complete stage for role.
            if (!role::is_read_only_role(new model\role($role))) {
                $completion->set_timecompleted(time())->save();
                stage_completed::create_from_completion($completion, $context)->trigger();
            }
            redirect($backurl);
        }
    }

    if ($attempt->is_archived()) {
        $fmt = get_string('strftimedate', 'langconfig');
        $pagecontent .= $OUTPUT->notification(get_string('attemptarchivedon', 'assessment', userdate($attempt->get_timearchived(), $fmt)), 'info');
    }
    $pagecontent .= $renderer->assessment_page($form, $completion, $viewingrole);
} else {
    $limit = 30;
    if ($attempt->is_archived()) {
        $fmt = get_string('strftimedate', 'langconfig');
        $pagecontent .= $OUTPUT->notification(get_string('attemptarchivedon', 'assessment', userdate($attempt->get_timearchived(), $fmt)), 'info');
    }
    $pagecontent .= $renderer->participants($attempt->get_user(), $attempt->get_evaluators($limit), $attempt->get_reviewers($limit), $attempt);
    $pagecontent .= $renderer->stages($attempt, $stages, $version, $course, $role);
}

$PAGE->set_title($assessment->name);

echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);

echo $roleswitcher;

echo $pagecontent;

echo $OUTPUT->footer();
