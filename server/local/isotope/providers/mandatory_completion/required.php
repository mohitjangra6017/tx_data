<?php
/*
 * @author Ben Lobo <ben@benlobo.co.uk>
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/totara/program/lib.php');
require_once($CFG->dirroot . '/totara/job/classes/job_assignment.php');
require_once($CFG->dirroot . '/totara/hierarchy/prefix/organisation/lib.php');

require_login();

$userId = optional_param('userid', $USER->id, PARAM_INT); // Show required learning for this user

$PAGE->set_context(context_system::instance());

// Check if programs and certifications are disabled.
if (totara_core\advanced_feature::is_disabled('programs') &&
    totara_core\advanced_feature::is_disabled('certifications')) {
    print_error('programsandcertificationsdisabled', 'totara_program');
}

$PAGE->set_url('/local/isotope/providers/mandatory_completion/required.php', array('userid' => $userId));
$PAGE->set_pagelayout('report');

//
/// Permission checks
//

$canViewRequiredLearning = false;
$usersJobAssignment = \totara_job\job_assignment::get_first($userId, false);
$reportersJobAssignment = \totara_job\job_assignment::get_first($USER->id, false);

if ($reportersJobAssignment && isset($reportersJobAssignment->organisationid)) {
    $organisation = new \organisation();
    $reportersOrganisationChildren = $organisation->get_item_descendants($reportersJobAssignment->organisationid);
    if ($usersJobAssignment && isset($usersJobAssignment->organisationid) && isset($reportersOrganisationChildren[$usersJobAssignment->organisationid])) {
        $canViewRequiredLearning = true;
    }
}

if (!$canViewRequiredLearning) {
    print_error('error:nopermissions', 'totara_program');
}

// Helper class for displaying a user's required learning.
$requiredLearningHelper = new \isotopeprovider_mandatory_completion\RequiredLearning($userId);

//
// Display program list
//

$heading = get_string('requiredlearning', 'totara_program');
$pagetitle = format_string(get_string('requiredlearning', 'totara_program'));

$requiredLearningHelper->progAddRequiredLearningBaseNavlinks();

$PAGE->set_title($heading);
$PAGE->set_heading($pagetitle);

echo $OUTPUT->header();

// Required learning page content
echo $OUTPUT->container_start('', 'required-learning');

echo prog_display_user_message_box($userId);

echo $OUTPUT->heading($heading);

echo $OUTPUT->container_start('', 'required-learning-description');

$user = $DB->get_record('user', array('id' => $userId));
$userfullname = fullname($user);
$requiredlearninginstructions = html_writer::start_tag('div', array('class' => 'instructional_text')) . get_string('requiredlearninginstructionsuser', 'totara_program', $userfullname) . html_writer::end_tag('div');

echo $requiredlearninginstructions;

echo html_writer::start_tag('div', array('style' => 'clear: both;')) . html_writer::end_tag('div');
echo $OUTPUT->container_end();

if (totara_core\advanced_feature::is_enabled('programs')) {
    echo $OUTPUT->container_start('', 'required-learning-list');
    echo $OUTPUT->heading(get_string('programs', 'totara_program'), 3);

    $requiredlearninghtml = $requiredLearningHelper->progDisplayRequiredPrograms();

    if (empty($requiredlearninghtml)) {
        echo get_string('norequiredlearning', 'totara_program');
    } else {
        echo $requiredlearninghtml;
    }

    echo $OUTPUT->container_end();
}

if (totara_core\advanced_feature::is_enabled('certifications')) {
    echo $OUTPUT->container_start('', 'certification-learning-list');
    echo $OUTPUT->heading(get_string('certifications', 'totara_program'), 3);

    $certificationhtml = $requiredLearningHelper->progDisplayCertificationPrograms();

    if (empty($certificationhtml)) {
        echo get_string('nocertificationlearning', 'totara_program');
    } else {
        echo $certificationhtml;
    }

    echo $OUTPUT->container_end();
}

echo $OUTPUT->container_end();
echo $OUTPUT->footer();
