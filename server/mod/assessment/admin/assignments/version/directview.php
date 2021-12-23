<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 *
 * DEVIOTIS2
 * - refactored to assign roles generically (i.e. evaluators AND reviewers not just the former).
 */

use mod_assessment\helper\role;

require_once(__DIR__ . '/../../../../../config.php');

global $OUTPUT, $PAGE;

$assessmentid = required_param('id', PARAM_INT);
$assessment = mod_assessment\model\assessment::instance(['id' => $assessmentid], MUST_EXIST);

$versionid = required_param('versionid', PARAM_INT);
$version = mod_assessment\model\version::instance(['id' => $versionid, 'assessmentid' => $assessment->id], MUST_EXIST);

$role = required_param('role', PARAM_ALPHANUMEXT);

$sid = optional_param('sid', '0', PARAM_INT);
$debug = optional_param('debug', 0, PARAM_INT);
$format = optional_param('format', '', PARAM_ALPHANUM);

list($course, $cm) = get_course_and_cm_from_instance($assessment->id, 'assessment');
$context = context_module::instance($cm->id);

require_login($course, null, $cm);
require_capability('mod/assessment:editinstance', $context);

$urlparams = ['id' => $assessment->id, 'versionid' => $versionid, 'role' => $role];
$PAGE->set_url(new moodle_url('/mod/assessment/admin/assignments/version/directview.php', $urlparams));
$returnurl = new moodle_url('/mod/assessment/admin/assignments/version/rules.php', $urlparams);
$uploadurl = new moodle_url('/mod/assessment/admin/assignments/version/upload.php', $urlparams);

$renderer = $PAGE->get_renderer('assessment');

$report = reportbuilder::create_embedded('assessment_version_assignment_direct');
if ($format != '') {
    $report->export_data($format);
    die;
}

echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);
echo $renderer->content_info($version);

$roletypes = [
    role::EVALUATOR => 'evaluator',
    role::REVIEWER => 'reviewer',
];
$prefix = (isset($roletypes[$role]) ? $roletypes[$role] : '');
echo $renderer->navtabs($prefix . 'rules', $assessment->id, $version->id);
echo html_writer::link($returnurl, get_string('returntorules', 'assessment'), ['class' => 'btn btn-default']);
echo html_writer::link($uploadurl, get_string('assignrolesviafileupload', 'assessment'), ['class' => 'btn btn-primary']);

echo render_report($report, $sid);

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
