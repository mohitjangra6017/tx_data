<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

use mod_assessment\model;

require_once(__DIR__ . '/../../../config.php');

global $OUTPUT, $PAGE;

$versionid = required_param('versionid', PARAM_INT);
$version = model\version::instance(['id' => $versionid], MUST_EXIST);
$assessment = model\assessment::instance(['id' => $version->assessmentid], MUST_EXIST);

$stageid = optional_param('id', false, PARAM_INT);
if ($stageid) {
    $stage = model\stage::instance(['id' => $stageid], MUST_EXIST);
} else {
    $stage = new model\stage();
}

list($course, $cm) = get_course_and_cm_from_instance($assessment->id, 'assessment');

$context = context_module::instance($cm->id);
require_login($course, null, $cm);
require_capability('mod/assessment:editinstance', $context);
$PAGE->set_url(new moodle_url('/mod/assessment/admin/editstage.php', ['versionid' => $version->id, 'id' => $stage->id]));

/** @var mod_assessment_renderer $renderer */
$renderer = $PAGE->get_renderer('assessment');
$subhead = isset($stage->id) ? get_string('head:editstage', 'assessment') : get_string('head:addstage', 'assessment');

$versionstage = model\version_stage::instance(['stageid' => $stageid, 'versionid' => $version->id], IGNORE_MISSING);

$mform = new mod_assessment\form\editstage(null, ['stage' => $stage]);

$formdata = clone $stage;
$formdata->locked = $versionstage ? $versionstage->get_locked() : false;
$formdata->versionid = $version->id;
$mform->set_data($formdata);

$returnurl = new moodle_url('/mod/assessment/admin/content.php', ['id' => $assessment->id, 'versionid' => $version->id]);
if ($mform->is_cancelled()) {
    redirect($returnurl);
} elseif ($data = $mform->get_data()) {
    $stage->set_name($data->name);
    $stage->set_newpage($data->newpage);
    $stage->save();

    if (!$versionstage) {
        $versionstage = new model\version_stage();
        $versionstage->set_stageid($stage->id);
        $versionstage->set_versionid($version->id);
        $versionstage->calculate_sortorder();
    }
    $versionstage->set_locked($data->locked)->save();

    if ($stage->needs_remap()) {
        $versionstage->set_stageid($stage->get_id())->save();

        // Remap stage questions.
        $versionquestions = model\version_question::instances(['stageid' => $stageid, 'versionid' => $version->id]);
        foreach ($versionquestions as $versionquestion) {
            $versionquestion->set_stageid($stage->id)->save();
        }
    }

    $returnurl->param('stageid', $stage->id);
    redirect($returnurl);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);
echo $renderer->content_info($version);
echo $renderer->navtabs('content', $assessment->id, $version->id);

echo html_writer::tag('h3', $subhead);
echo $mform->render();

echo $OUTPUT->footer();
