<?php
/**
 * Program Courset Report
 *
 * @package   local_program_report
 * @author    Jo Jones <jo.jones@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

use local_program_report\Factory\ProgramReportFactory;
use local_program_report\Form\ProgramSelectorForm;

require_once(dirname(dirname(__DIR__)) . '/config.php');

global $CFG, $DB, $PAGE, $OUTPUT;

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/totara/core/js/lib/setup.php');
require_once($CFG->dirroot . '/totara/reportbuilder/lib.php');

require_login();
require_capability('local/program_report:viewreport', context_system::instance());

$debug = optional_param('debug', false, PARAM_INT);
$format = optional_param('format', '', PARAM_ALPHANUM);
$programId = optional_param('id', 0, PARAM_ALPHANUM);

// Page definition.
$url = new moodle_url('/local/program_report/index.php');
if ($programId) {
    $url->param('id', $programId);
}

$title = get_string('page:title', 'local_program_report');
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_title($title);

if ($programId) {

    $transaction = $DB->start_delegated_transaction();

    $report = ProgramReportFactory::create($programId);

    if (!reportbuilder::is_capable($report->_id)) {
        print_error('nopermission', 'totara_reportbuilder');
    }

    $report->include_js();

    if ($format != '') {
        $report->export_data($format);

        try {
            $e = new Exception();
            $transaction->rollback($e);
        } catch (Exception $e) {

        }
        die;
    }

    $PAGE->set_button($report->edit_button());
}

// Page display.
echo $OUTPUT->header();
echo $OUTPUT->heading($title);

$form = new ProgramSelectorForm($url, ['programs' => $DB->get_records_menu('prog', [], 'fullname', 'id, fullname')]);
$form->display();

if ($programId) {
    $report->display_search();
    $report->display_sidebar_search();

    // Print saved search buttons if appropriate.
    echo $report->display_saved_search_options();

    if ($debug) {
        $report->debug();
    }

    $report->display_table();

    $PAGE->get_renderer('totara_reportbuilder')->export_select($report);

    try {
        $e = new Exception();
        $transaction->rollback($e);
    } catch (Exception $e) {

    }
}

echo $OUTPUT->footer();