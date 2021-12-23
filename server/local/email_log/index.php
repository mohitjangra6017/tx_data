<?php
/**
 * Program Courset Report
 *
 * @package   local_program_report
 * @author    Jo Jones <jo.jones@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

use totara_reportbuilder\event\report_viewed;

global $CFG, $DB, $OUTPUT, $PAGE;

require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/totara/core/js/lib/setup.php');
require_once($CFG->dirroot . '/totara/reportbuilder/lib.php');
require_once($CFG->dirroot . '/totara/reportbuilder/export_form.php');

require_login();

$debug = optional_param('debug', false, PARAM_INT);
$format = optional_param('format', 'html', PARAM_ALPHANUM);

$url = new moodle_url('/local/email_log/index.php');

$title = get_string('page:title', 'local_email_log');
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_title($title);

$report = reportbuilder::create_embedded('email_log');
$report->include_js();
$PAGE->set_button($report->edit_button() . $PAGE->button);
$renderer = $PAGE->get_renderer('totara_reportbuilder');

if ($format != 'html') {
    $report->export_data($format);
    die;
}

report_viewed::create_from_report($report)->trigger();
[$reporthtml, $debughtml] = $renderer->report_html($report, $debug);

echo $OUTPUT->header();
echo $OUTPUT->heading($title);
echo $debughtml;
$report->display_restrictions();
$report->display_search();
$report->display_sidebar_search();
echo $reporthtml;
$renderer->export_select($report);
echo $OUTPUT->footer();
