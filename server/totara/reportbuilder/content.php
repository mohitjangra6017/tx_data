<?php // $Id$
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Simon Coggins <simon.coggins@totaralms.com>
 * @package totara
 * @subpackage reportbuilder
 */

define('REPORTBUIDLER_MANAGE_REPORTS_PAGE', true);
define('REPORT_BUILDER_IGNORE_PAGE_PARAMETERS', true); // We are setting up report here, do not accept source params.

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/totara/reportbuilder/lib.php');
require_once($CFG->dirroot . '/totara/reportbuilder/report_forms.php');

$id = required_param('id', PARAM_INT); // report builder id

$rawreport = $DB->get_record('report_builder', array('id' => $id), '*', MUST_EXIST);

$adminpage = $rawreport->embedded ? 'rbmanageembeddedreports' : 'rbmanagereports';
admin_externalpage_setup($adminpage);

$output = $PAGE->get_renderer('totara_reportbuilder');

$returnurl = new moodle_url('/totara/reportbuilder/content.php', array('id' => $id));

$config = (new rb_config())->set_nocache(true);
$report = reportbuilder::create($id, $config, false); // No access control for managing of reports here.

// form definition
$mform = new report_builder_edit_content_form(null, compact('id', 'report'));

// form results check
if ($mform->is_cancelled()) {
    redirect($returnurl);
}
if ($fromform = $mform->get_data()) {

    if (empty($fromform->submitbutton)) {
        \core\notification::error(get_string('error:unknownbuttonclicked', 'totara_reportbuilder'));
        redirect($returnurl);
    }
    reportbuilder_update_content($id, $report, $fromform);
    reportbuilder_set_status($id);

    $config = (new rb_config())->set_nocache(true);
    $report = reportbuilder::create($id, $config, false); // No access control for managing of reports here.

    \totara_reportbuilder\event\report_updated::create_from_report($report, 'content')->trigger();
    \core\notification::success(get_string('reportupdated', 'totara_reportbuilder'));
    redirect($returnurl);
}

echo $output->header();

echo $output->container_start('reportbuilder-navlinks');
echo $output->view_all_reports_link($report->embedded);
echo $output->container_end();
echo $output->edit_report_heading($report);

if ($report->get_cache_status() > 0) {
    echo $output->cache_pending_notification($id);
}

$currenttab = 'content';
require('tabs.php');

// display the form
$mform->display();

echo $output->footer();


/**
 * Update the report content settings with data from the submitted form
 *
 * @param integer $id Report ID to update
 * @param reportbuilder $report Report builder object that is being updated
 * @param object $fromform Moodle form object containing the new content data
 *
 * @return boolean True if the content settings could be updated successfully
 */
function reportbuilder_update_content($id, $report, $fromform) {
    global $DB;
    $transaction = $DB->start_delegated_transaction();

    // first check if there are any content restrictions at all
    $contentenabled = isset($fromform->contentenabled) ? $fromform->contentenabled : REPORT_BUILDER_CONTENT_MODE_NONE;

    // update content enabled setting
    $todb = new stdClass();
    $todb->id = $id;
    $todb->contentmode = $contentenabled;
    $todb->timemodified = time();
    if (isset($fromform->globalrestriction)) {
        $todb->globalrestriction = $fromform->globalrestriction;
    }
    $DB->update_record('report_builder', $todb);

    $contentoptions = isset($report->contentoptions) ?
        $report->contentoptions : array();

    // pass form data to content class for processing
    foreach ($contentoptions as $option) {
        $classname = $report->src->resolve_content_classname($option->classname);
        if (!$classname) {
            print_error('contentclassnotexist', 'totara_reportbuilder', '', $option->classname);
        }

        $obj = new $classname();

        if (!method_exists($obj, 'form_process')) {
            throw new coding_exception("The form_process() method is not defined on the content class '{$classname}'");
        }

        $obj->form_process($id, $fromform);
    }
    $transaction->allow_commit();
    return true;
}
