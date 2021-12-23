<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

use mod_assessment\form\page;
use mod_assessment\helper\role;
use mod_assessment\model;

require_once(__DIR__ . '/../../../config.php');

global $PAGE, $OUTPUT;

$versionid = required_param('versionid', PARAM_INT);
$version = model\version::instance(['id' => $versionid], MUST_EXIST);
$assessment = model\assessment::instance(['id' => $version->assessmentid], MUST_EXIST);

$role = optional_param('role', role::LEARNER, PARAM_INT);
$stageid = optional_param('stageid', null, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);

$attempt = model\attempt::instance_preview($version);

list($course, $cm) = get_course_and_cm_from_instance($assessment->id, 'assessment');
$context = context_module::instance($cm->id);
require_login($course, null, $cm);
require_capability('mod/assessment:editinstance', $context);

$stages = model\stage::instances_from_version($version);
if ($stageid) {
    $stage = model\stage::instance(['id' => $stageid], MUST_EXIST);
} elseif (!$version->is_multistage()) {
    // Set the stage if only one exists.
    $stage = current($stages);
}

$pagecontent = '';
$pageparams = ['page' => $page, 'role' => $role, 'stageid' => $stageid, 'versionid' => $version->id];

$PAGE->set_context($context);
$PAGE->set_cm($cm);
$PAGE->set_url(new moodle_url('/mod/assessment/admin/preview.php', $pageparams));
$renderer = $PAGE->get_renderer('assessment');

// Determine where we are.
if (isset($stage)) {
    $location = 'stage';

    $versionstage = model\version_stage::instance(['stageid' => $stage->id, 'versionid' => $version->id], MUST_EXIST);
    $questions = model\question::instances_from_versionstage($versionstage, $stage->newpage, $page * $stage->newpage);

    // Pass attempt to can_view to only grab questions that are visible.
    $questions = array_filter($questions, function (model\question $question) use ($role, $attempt) {
        return $question->can_view($role, $attempt);
    });

    $questiondata = [];
    foreach ($questions as $question) {
        $value = $question->get_default();
        $questiondata["q_{$question->id}"] = $question->get_default();
    }
    $questiondata = array_filter($questiondata);
    $form = new page(
        ['attemptid' => $attempt->id] + $pageparams + $questiondata,
        ['attempt' => $attempt, 'role' => $role, 'questions' => $questions, 'islocked' => false]
    );

    // Process the form.
    if ($version->is_multistage()) {
        $backurl = clone $PAGE->url;
        $backurl->param('page', null);
        $backurl->param('stageid', null);
    } else {
        $backurl = new moodle_url('/mod/assessment/admin/content.php', ['id' => $assessment->id, 'versionid' => $version->id]);
    }

    if ($form->is_cancelled()) {
        redirect($backurl);
    } elseif ($data = $form->get_data()) {
        if (isset($data->nextpage)) {
            $nextpage = clone $PAGE->url;
            $nextpage->param('page', $page + 1);
            redirect($nextpage);
        }
        if (isset($data->submit)) {
            // Return to stages?
            $stageurl = clone $PAGE->url;
            $stageurl->param('page', null);
            $stageurl->param('stageid', null);
            redirect($stageurl);
        }
    }

    $stagecompletion = new model\stage_completion();
    $pagecontent .= $renderer->assessment_page($form, $stagecompletion);

} else {
    $location = 'top';

    $stages = model\stage::instances_from_version($version);
    $previewuser = model\attempt::get_preview_user();

    $pagecontent .= $renderer->participants($previewuser, [$previewuser], [$previewuser]); // DEVIOTIS2
    $pagecontent .= $renderer->stages($attempt, $stages, $version, $course, $role);
}

echo $OUTPUT->header();
echo $renderer->preview_options($assessment, $version, $role, $location);
echo $OUTPUT->heading($assessment->name);
echo $pagecontent;
echo $OUTPUT->footer();
