<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

use mod_assessment\factory\assessment_question_factory;
use mod_assessment\factory\question_form_factory;
use mod_assessment\model;

require_once(__DIR__ . '/../../../config.php');
global $OUTPUT, $PAGE;

$stageid = required_param('stageid', PARAM_INT);
$stage = model\stage::instance(['id' => $stageid], MUST_EXIST);

$versionid = required_param('versionid', PARAM_INT);
$version = model\version::instance(['id' => $versionid], MUST_EXIST);
$assessment = model\assessment::instance(['id' => $version->assessmentid], MUST_EXIST);
$parent = null;

if ($questionid = optional_param('id', null, PARAM_INT)) {
    /** @var model\question $question */
    $question = assessment_question_factory::fetch(['id' => $questionid]);
    $parent = assessment_question_factory::fetch_parent($question->get_id(), $version->get_id());
} else {
    // Set up new question.
    $type = required_param('type', PARAM_TEXT);
    $question = model\question::class_from_type($type);
    $question->type = $question->get_type();

    if ($parentid = optional_param('parentid', null, PARAM_INT)) {
        $parent = model\question::instance(['id' => $parentid], MUST_EXIST);
    }
}

list($course, $cm) = get_course_and_cm_from_instance($assessment->id, 'assessment');
$context = context_module::instance($cm->id);
require_login($course, null, $cm);
require_capability('mod/assessment:editinstance', $context);
$PAGE->set_url(new moodle_url('/mod/assessment/admin/editquestion.php', ['versionid' => $version->id, 'stageid' => $stage->id, 'type' => $question->type, 'id' => $questionid]));
if ($parent) {
    $PAGE->url->param('parentid', $parent->get_id());
}

/** @var mod_assessment_renderer $renderer */
$renderer = $PAGE->get_renderer('assessment');

$returnurl = $parent ?
    new moodle_url('/mod/assessment/admin/editquestion.php', ['id' => $parent->get_id(), 'stageid' => $stage->get_id(), 'versionid' => $version->get_id()]) :
    new moodle_url('/mod/assessment/admin/content.php', ['id' => $assessment->id, 'versionid' => $version->id, 'stageid' => $stageid]);

// Add additional data needed for form.
$question->stageid = $stage->id;
$question->versionid = $version->id;

$formdata = $question->export_for_form();
$formdata['stageid'] = $stage->get_id();
$formdata['versionid'] = $version->get_id();
if ($parent) {
    $formdata['parentid'] = $parent->get_id();
    $formdata['learnerperms'] = $parent->learnerperms;
    $formdata['evaluatorperms'] = $parent->evaluatorperms;
    $formdata['reviewerperms'] = $parent->reviewerperms;
}

$mform = question_form_factory::create($stage, $question, $version, $parent);
$mform->set_data($formdata);

// Process form.
if ($mform->is_cancelled()) {
    redirect($returnurl);
}

if ($data = $mform->get_data()) {
    if ($version->is_draft() || !$question->is_question()) {
        $question->set_evaluatorperms($data->evaluatorperms);
        $question->set_reviewerperms($data->reviewerperms);
        $question->set_learnerperms($data->learnerperms);
        $question->set_weight($data->weight);
    }
    $question->set_question($data->question);
    $question->encode_config($mform->get_config_data());
    $question->save();

    if ($question->needs_remap()) {
        $versionquestion = model\version_question::instance(['questionid' => $questionid, 'versionid' => $version->id], MUST_EXIST);
        $versionquestion->set_questionid($question->id)->save();
    } else {
        // Create version stage mapping if it doesn't exist.
        $versionquestion = model\version_question::instance(['questionid' => $question->id, 'versionid' => $version->id]);
        if (!$versionquestion) {
            $versionquestion = new model\version_question();
            $versionquestion->set_parentid($parent ? $parent->get_id() : null);
            $versionquestion->set_questionid($question->id);
            $versionquestion->set_stageid($stage->id);
            $versionquestion->set_versionid($version->id);
            $versionquestion->calculate_sortorder();
            $versionquestion->save();
        }
    }

    if ('meta' == $data->type && isset($data->addquestion)) {
        $params = [
            'parentid' => $question->get_id(),
            'stageid' => $stage->get_id(),
            'type' => $data->newtype,
            'versionid' => $version->get_id(),
        ];
        $newquestionurl = new moodle_url('/mod/assessment/admin/editquestion.php', $params);
        redirect($newquestionurl);
    }

    redirect($returnurl);
}

$subhead = $question->exists() ?
    get_string('head:editquestion', 'assessment', $question->get_displayname()) :
    get_string('head:addquestion', 'assessment', $question->get_displayname());

// Render page.
echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);
echo $renderer->content_info($version);
echo $renderer->navtabs('content', $assessment->id, $version->id);

echo html_writer::tag('h3', $subhead);
$mform->display();

echo $OUTPUT->footer();
