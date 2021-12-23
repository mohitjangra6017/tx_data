<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 *
 * DEVIOTIS2
 * - refactored from chooseevaluators.php to choose uses with roles generically (i.e. evaluators AND reviewers not just the former).
 */

use mod_assessment\form\chooseroleuser;
use mod_assessment\model\role;
use mod_assessment\message\evaluatorselected;
use mod_assessment\model\assessment;
use mod_assessment\model\attempt;
use mod_assessment\model\version;

require_once(__DIR__ . '/../../config.php');

global $CFG, $USER, $PAGE, $OUTPUT, $DB;

require_once($CFG->dirroot . '/totara/core/dialogs/dialog_content_users.class.php');

$attemptid = required_param('attemptid', PARAM_INT);
$role = required_param('role', PARAM_INT);
$selected = optional_param('selected', null, PARAM_INT);

require_login();

// attemptid is provided no need to provide userid
$attempt = attempt::instance(['id' => $attemptid], MUST_EXIST);
$version = version::instance(['id' => $attempt->versionid], MUST_EXIST);
// check current user has permission to assign a role
if ($attempt->userid != $USER->id && !$attempt->is_evaluator_valid($USER->id) && !$attempt->is_reviewer_valid($USER->id)) {
    print_error('accessdenied', 'admin');
    return;
}

$assessment = assessment::instance(['id' => $version->assessmentid], MUST_EXIST);

$cmid = $assessment->get_cmid();
list($course, $cm) = get_course_and_cm_from_cmid($cmid, 'assessment');

$activetab = optional_param('search', null, PARAM_TEXT) ? 'search' : 'browse';

$PAGE->requires->js_call_amd('mod_assessment/chooseroleuser', 'init', [$activetab]);
$PAGE->set_context(context_system::instance()); // Course / module contexts have no idea what to do with evaluators.
$PAGE->set_url(new moodle_url('/mod/assessment/chooseroleuser.php', ['attemptid' => $attemptid, 'role' => $role]));
$renderer = $PAGE->get_renderer('mod_assessment');

$dialog = new mod_assessment\dialog\roleusers(new role($role));
$dialog->set_attempt($attempt);
$dialog->set_version($version);
$dialog->items = $dialog->get_items();
$dialog->urlparams = ['attemptid' => $attemptid, 'selected' => $selected, 'role' => $role];

// If exactly one roleuser - auto-assign.
if (count($dialog->items) == 1) {
    $selected = reset($dialog->items)->id;
}

// Dialog generate markup - minus header modification.
$markup = html_writer::start_div('totara-dialog');
$markup .= html_writer::start_tag('div', array('class' => 'row-fluid'));

// Open select container.
$markup .= html_writer::start_div('div', ['class' => 'select']);

$markup .= html_writer::start_tag('div', array('id' => 'dialog-tabs', 'class' => 'dialog-content-select'));

$markup .= '<ul class="nav nav-tabs tabs dialog-nobind">';
$markup .= '  <li><a href="#browse-tab">' . get_string('browse', 'totara_core') . '</a></li>';
if (!empty($dialog->search_code)) {
    $markup .= '  <li><a href="#search-tab">' . get_string('search') . '</a></li>';
}
$markup .= '</ul>';

// Display treeview.
$markup .= '<div id="browse-tab">';

// Display any custom markup.
if (method_exists($dialog, '_prepend_markup')) {
    $markup .= $dialog->_prepend_markup();
}

$markup .= $dialog->generate_treeview();
$markup .= '</div>';

if (!empty($dialog->search_code)) {
    // Display searchview.
    $markup .= '<div id="search-tab" class="dialog-load-within">';
    $markup .= $dialog->generate_search();
    $markup .= '<div id="search-results"></div>';
    $markup .= '</div>';
}

// Close select container.
$markup .= html_writer::end_div();
$markup .= html_writer::end_div();

// Close container for content.
$markup .= html_writer::end_div();
$markup .= html_writer::end_div();

$roletypes = [
    role::EVALUATOR => 'evaluator',
    role::REVIEWER => 'reviewer',
];
$strselected = html_writer::span(get_string('none'), 'selectedroleuser');
$divselected = html_writer::tag('h4', '(' . get_string('selected', 'assessment') . ": {$strselected})");

$form = new chooseroleuser(['role' => $role, 'attemptid' => $attempt->id, 'activetab' => $activetab, 'selected' => $selected]);
if ($form->is_cancelled()) {
    redirect(new moodle_url('/course/view.php', ['id' => $course->id]));
} elseif ($selected || $data = $form->get_data()) {

    if ($role == role::EVALUATOR) {
        $attempt->set_evaluatorids($selected)->save();
    } elseif ($role == role::REVIEWER) {
        $attempt->set_reviewerids($selected)->save();
    }

    // Send messages to evaluators.
    if ($role == role::EVALUATOR) {
        $evaluators = $attempt->get_evaluators();
        foreach ($evaluators as $evaluator) {
            $learner = $DB->get_record('user', ['id' => $attempt->userid]);
            evaluatorselected::send($evaluator, $learner, $assessment);
        }
        $attempt->set_role_users_notified($role, array_keys($evaluators));
    }

    redirect(new moodle_url('/mod/assessment/attempt.php', ['attemptid' => $attempt->id, 'role' => $role]));
}

echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);
$limit = 30;
echo $renderer->participants($attempt->get_user(), $attempt->get_evaluators($limit), $attempt->get_reviewers($limit), $attempt);
echo html_writer::tag('h3', get_string('choose' . $roletypes[$role], 'assessment'));
echo $divselected;
echo $markup;
echo $form->render();
echo $OUTPUT->footer();
