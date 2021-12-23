<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

use mod_assessment\form\version_roles_verify_form;
use mod_assessment\model\role;
use mod_assessment\model\user_identifier;
use mod_assessment\processor\version_assignments_import_processor;

require_once(__DIR__ . '/../../../../../config.php');

global $CFG, $OUTPUT, $PAGE, $SESSION;
require_once($CFG->libdir . '/csvlib.class.php');

$assessmentid = required_param('id', PARAM_INT);
$assessment = mod_assessment\model\assessment::instance(['id' => $assessmentid], MUST_EXIST);

$versionid = required_param('versionid', PARAM_INT);
$version = mod_assessment\model\version::instance(['id' => $versionid, 'assessmentid' => $assessment->id], MUST_EXIST);

$role = required_param('role', PARAM_ALPHANUMEXT);
$role = new role($role);

$importid = required_param('importid', PARAM_INT);

$sid = optional_param('sid', '0', PARAM_INT);
$debug = optional_param('debug', 0, PARAM_INT);
$format = optional_param('format', '', PARAM_ALPHANUM);

list($course, $cm) = get_course_and_cm_from_instance($assessment->id, 'assessment');
$context = context_module::instance($cm->id);

require_login($course, null, $cm);
require_capability('mod/assessment:editinstance', $context);

$urlparams = ['id' => $assessment->id, 'versionid' => $versionid, 'role' => $role->value(), 'importid' => $importid];
$PAGE->set_url(new moodle_url('/mod/assessment/admin/assignments/version/confirm.php', $urlparams));
$returnurl = new moodle_url('/mod/assessment/admin/assignments/version/directview.php', $urlparams);

/** @var mod_assessment_renderer $renderer */
$renderer = $PAGE->get_renderer('assessment');

$report = reportbuilder::create_embedded('assessment_version_assignment_verify');
if ($format != '') {
    $report->export_data($format);
    die;
}

$formdata = array_merge($urlparams, (array)$SESSION->assessment_version_assignment_import);
$form = new version_roles_verify_form(null, ['role' => $role]);
$form->set_data($formdata);
if ($form->is_cancelled()) {
    $processor = new version_assignments_import_processor(
        $importid,
        new user_identifier($SESSION->assessment_version_assignment_import['learneridfield']),
        new user_identifier($SESSION->assessment_version_assignment_import['useridfield']),
        $SESSION->assessment_version_assignment_import['replaceassignments'],
        $SESSION->assessment_version_assignment_import['autoenrol']
    );
    $processor->cancel_import();
    redirect($returnurl);
}

if ($formdata = $form->get_data()) {
    $processor = new version_assignments_import_processor(
        $importid,
        new user_identifier($formdata->learneridfield),
        new user_identifier($formdata->useridfield),
        $formdata->replaceassignments,
        $formdata->autoenrol
    );

    if (isset($formdata->submitbutton)) {
        $processor->execute();
        redirect($returnurl);
    }

    $processor->preprocess();
}

echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);
echo $renderer->content_info($version);

echo render_report($report, $sid);
echo $form->render();

echo $OUTPUT->footer();

function render_report($report, $sid) {
    global $PAGE;

    ob_start();
    /** @var totara_reportbuilder_renderer $output */
    $output = $PAGE->get_renderer('totara_reportbuilder');
    echo $output->heading($report->fullname . ': ' . $output->result_count_info($report), 3);
    $report->display_search();
    $report->display_sidebar_search();
    list($reporthtml, $debughtml) = $output->report_html($report);
    echo $debughtml;
    echo $output->showhide_button($report);
    echo $reporthtml;
    $output->export_select($report, $sid);

    return ob_get_clean();
}
