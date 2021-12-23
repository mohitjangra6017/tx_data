<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

require_once(__DIR__ . '/../../../config.php');

global $PAGE, $OUTPUT;

use mod_assessment\model;
use mod_assessment\processor\version_clone_processor;

$assessmentid = required_param('id', PARAM_INT);
$assessment = model\assessment::instance(['id' => $assessmentid], MUST_EXIST);
$versions = mod_assessment\model\version::instances_from_assessment($assessment);

$versionid = optional_param('versionid', null, PARAM_INT);
if ($versionid) {
    $version = model\version::instance(['id' => $versionid], MUST_EXIST);
} else {
    $version = model\version::instance_for_edit($assessment);
}

list($course, $cm) = get_course_and_cm_from_instance($assessment->id, 'assessment');
$context = context_module::instance($cm->id);

require_login($course, null, $cm);
require_capability('mod/assessment:editinstance', $context);

$PAGE->set_url('/mod/assessment/admin/versions.php', ['id' => $assessment->id, 'versionid' => $version->id]);
$renderer = $PAGE->get_renderer('assessment');

// Process new version.
if (optional_param('newversion', false, PARAM_BOOL)) {
    require_sesskey();
    $newversion = version_clone_processor::clone_from_version($version);
    $redirect = clone $PAGE->url;
    $redirect->param('versionid', $newversion->id);
    redirect($redirect);
}

// Render page.
echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);
echo $renderer->content_info($version);
echo $renderer->navtabs('versions', $assessment->id, $version->id);
echo html_writer::tag('h3', get_string('versions', 'assessment'));
echo $renderer->version_table($versions);
echo $OUTPUT->footer();
