<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

use mod_assessment\model\stage;

require_once(__DIR__ . '/../../../config.php');

global $PAGE, $OUTPUT;

$stageid = required_param('id', PARAM_INT);
$versionid = required_param('versionid', PARAM_INT);
$confirm = optional_param('confirm', false, PARAM_BOOL);

$stage = stage::instance(['id' => $stageid], MUST_EXIST);
$version = mod_assessment\model\version::instance(['id' => $versionid], MUST_EXIST);
$assessment = mod_assessment\model\assessment::instance(['id' => $version->assessmentid], MUST_EXIST);

list($course, $cm) = get_course_and_cm_from_instance($assessment->id, 'assessment');

$context = context_module::instance($cm->id);
require_login($course, null, $cm);
require_capability('mod/assessment:editinstance', $context);
$PAGE->set_url(new moodle_url('/mod/assessment/admin/deletestage.php', ['versionid' => $version->id, 'id' => $stage->id]));
$renderer = $PAGE->get_renderer('assessment');
$returnurl = new moodle_url('/mod/assessment/admin/content.php', ['id' => $assessment->id, 'versionid' => $version->id]);

if ($confirm and confirm_sesskey()) {
    $stage->delete_version($version);
    redirect($returnurl);
}

$message = get_string('confirmstagedelete', 'assessment', format_string($stage->name));
$nourl = $returnurl;
$yesurl = new moodle_url($PAGE->url, ['confirm' => true, 'sesskey' => sesskey()]);

echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);
echo $renderer->content_info($version);
echo $renderer->navtabs('content', $assessment->id, $version->id);
echo $renderer->confirm($message, $yesurl, $nourl);
echo $OUTPUT->footer();
