<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

use core\notification;
use mod_assessment\form\version_roles_add_file_form;
use mod_assessment\model\role;
use mod_assessment\model\user_identifier;
use mod_assessment\processor\version_assignments_csv_processor;
use mod_assessment\processor\version_assignments_import_processor;

require_once(__DIR__ . '/../../../../../config.php');

global $OUTPUT, $PAGE, $SESSION;

$assessmentid = required_param('id', PARAM_INT);
$assessment = mod_assessment\model\assessment::instance(['id' => $assessmentid], MUST_EXIST);

$versionid = required_param('versionid', PARAM_INT);
$version = mod_assessment\model\version::instance(['id' => $versionid, 'assessmentid' => $assessment->id], MUST_EXIST);

$role = required_param('role', PARAM_ALPHANUMEXT);
$role = new role($role);

list($course, $cm) = get_course_and_cm_from_instance($assessment->id, 'assessment');
$context = context_module::instance($cm->id);

require_login($course, null, $cm);
require_capability('mod/assessment:editinstance', $context);

$urlparams = ['id' => $assessment->id, 'versionid' => $versionid, 'role' => $role->value()];
$PAGE->set_url(new moodle_url('/mod/assessment/admin/assignments/version/upload.php', $urlparams));
$returnurl = new moodle_url('/mod/assessment/admin/assignments/version/directview.php', $urlparams);

$form = new version_roles_add_file_form(null, ['role' => $role]);
$form->set_data($urlparams);
if ($form->is_cancelled()) {
    redirect($returnurl);
}

if ($formdata = $form->get_data()) {
    $import = new csv_import_reader(csv_import_reader::get_new_iid('mod_assessment_version_assignment'), 'mod_assessment_version_assignment');
    $import->load_csv_content($form->get_file_content('file'), $formdata->encoding, $formdata->delimiter);

    $csvprocessor = new version_assignments_csv_processor($import, $version, $role);
    if (!$csvprocessor->validate_columns()) {
        $import->cleanup();
        redirect($PAGE->url, implode('<br>', $csvprocessor->get_errors()), null, notification::ERROR);
    }
    $csvprocessor->execute();
    if ($csvprocessor->get_errors()) {
        $import->cleanup();
        redirect($PAGE->url, implode('<br>', $csvprocessor->get_errors()), null, notification::ERROR);
    }

    $processor = new version_assignments_import_processor(
        $csvprocessor->get_importid(),
        new user_identifier($formdata->learneridfield),
        new user_identifier($formdata->useridfield),
        $formdata->replaceassignments,
        $formdata->autoenrol
    );
    $processor->preprocess();

    $SESSION->assessment_version_assignment_import = [
        'autoenrol' => $formdata->autoenrol,
        'replaceassignments' => $formdata->replaceassignments,
        'learneridfield' => $formdata->learneridfield,
        'useridfield' => $formdata->useridfield,
    ];
    redirect(new moodle_url('/mod/assessment/admin/assignments/version/confirm.php', array_merge($urlparams, ['importid' => $csvprocessor->get_importid()])));
}

$renderer = $PAGE->get_renderer('assessment');

echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);
echo $renderer->content_info($version);
$form->display();

echo $OUTPUT->footer();
