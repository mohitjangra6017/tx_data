<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

use mod_assessment\form;
use mod_assessment\model;

require_once(__DIR__ . '/../../../config.php');

global $PAGE, $OUTPUT;

$assessmentid = required_param('id', PARAM_INT);
$versionid = optional_param('versionid', null, PARAM_INT);
$stageid = optional_param('stageid', null, PARAM_INT);

$assessment = model\assessment::instance(['id' => $assessmentid], MUST_EXIST);

if ($versionid) {
    $version = model\version::instance(['id' => $versionid], MUST_EXIST);
} else {
    $version = mod_assessment\model\version::instance_for_edit($assessment);
}

if ($stageid) {
    $stage = model\stage::instance(['id' => $stageid], MUST_EXIST);
}

list($course, $cm) = get_course_and_cm_from_instance($assessment->id, 'assessment');

require_login($course, null, $cm);
require_capability('mod/assessment:editinstance', context_module::instance($cm->id));
$PAGE->set_url(new moodle_url('/mod/assessment/admin/content.php', ['id' => $assessment->id, 'versionid' => $version->id]));
$renderer = $PAGE->get_renderer('assessment');

$stages = model\stage::instances_from_version($version);
if (!$stages) {
    redirect(new moodle_url('/mod/assessment/admin/editstage.php', ['versionid' => $version->id]));
} elseif (!isset($stage)) {
    $stage = current($stages);
}

$versionstage = model\version_stage::instance(['stageid' => $stage->id, 'versionid' => $version->id], MUST_EXIST);
$questions = model\question::instances_from_versionstage($versionstage);

$mform = new form\choosequestion(null, ['version' => $version]);
$mform->set_data(['id' => $assessmentid, 'stageid' => $stage->id, 'versionid' => $version->id]);
if ($data = $mform->get_data()) {
    redirect(new moodle_url(
        '/mod/assessment/admin/editquestion.php',
        ['versionid' => $data->versionid, 'stageid' => $data->stageid, 'type' => $data->datatype]
    ));
}

$settings_mform = new form\assessmentsettings(null, ['version' => $version]);
$settings_mform->set_data(['id' => $assessmentid, 'versionid' => $version->id, 'hidegrade' => $assessment->hidegrade]);
if ($data = $settings_mform->get_data()) {
    // Save the activity settings
    $assessment->set_hidegrade($data->hidegrade);
    $assessment->save();
}

echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);

echo $renderer->content_info($version);
echo $renderer->navtabs('content', $assessment->id, $version->id);

echo html_writer::start_div('assessmentsettingscontainer');
echo $settings_mform->render();
echo html_writer::end_div();

echo $renderer->stage_table($stages, $version);

echo html_writer::tag('h3', get_string('stagecontent', 'assessment', format_string($stage->name)));
echo html_writer::start_div('assessmentstagecontainer');
echo $mform->render();
echo $renderer->stage_questions($questions, $version);
echo html_writer::end_div();

echo $OUTPUT->footer();
