<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

use mod_assessment\form\chooseassessment;

require_once(__DIR__ . '/../../../config.php');

global $CFG, $OUTPUT, $PAGE;

require_once($CFG->dirroot . '/totara/reportbuilder/lib.php');

$reportid = required_param('id', PARAM_INT);
$context = context_system::instance();
$PAGE->set_context($context);

$report = reportbuilder::create($reportid);

// Restrict access.
require_login();
if (!$report->is_capable($reportid)) {
    print_error('nopermission', 'totara_reportbuilder');
}

$PAGE->set_url(new moodle_url('/mod/assessment/rb_sources/chooseassessment.php', ['id' => $reportid]));
$PAGE->navbar->add(get_string('reports', 'totara_core'), new moodle_url('/my/reports.php'));
$PAGE->navbar->add($report->fullname);

$form = new chooseassessment(['id' => $reportid]);
if ($data = $form->get_data()) {
    redirect(new moodle_url('/totara/reportbuilder/report.php', ['id' => $reportid, 'assessmentid' => $data->assessmentid]));
}

echo $OUTPUT->header();
echo $OUTPUT->heading($report->fullname);
echo $form->render();
echo $OUTPUT->footer();

