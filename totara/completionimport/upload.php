<?php
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
 * @package    totara
 * @subpackage completionimport
 * @author     Russell England <russell.england@catalyst-eu.net>
 */

use totara_core\advanced_feature;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/totara/completionimport/upload_form.php');
require_once($CFG->dirroot . '/totara/completionimport/lib.php');
require_once($CFG->libdir . '/adminlib.php');

$filesource = optional_param('filesource', null, PARAM_INT);
if ($filesource === null) {
    $filesource = get_default_config('totara_completionimport', 'filesource', TCI_SOURCE_UPLOAD);
}

require_login();

$context = context_system::instance();
require_capability('totara/completionimport:import', $context);

$PAGE->set_context($context);

// Create the forms before $OUTPUT.
$coursedata = get_config_data($filesource, 'course');
$coursedata->importname = 'course';
$coursedata->showheader = true;
$coursedata->showdescription = true;
$courseform = new upload_form(null, $coursedata);

$certdata = get_config_data($filesource, 'certification');
$certdata->importname = 'certification';
$certdata->showheader = true;
$certdata->showdescription = true;
$certform = new upload_form(null, $certdata);

$importname = '';
if ($data = $courseform->get_data()) {
    $importname = 'course';
} else if ($data = $certform->get_data()) {
    $importname = 'certification';
}
if (!empty($importname)) {
    $heading = get_string('importing', 'totara_completionimport', $importname);
} else {
    $heading = get_string('pluginheading', 'totara_completionimport');
}

if (!in_array($filesource, array(TCI_SOURCE_EXTERNAL, TCI_SOURCE_UPLOAD))) {
    print_error('error:invalidfilesource', 'totara_completionimport');
} else {
    set_config('filesource', $filesource, 'totara_completionimport');
}

$PAGE->set_heading($heading);
$PAGE->set_title($heading);
$PAGE->set_url('/totara/completionimport/upload.php');
admin_externalpage_setup('totara_completionimport_upload');

echo $OUTPUT->header();
echo $OUTPUT->heading($heading);

if (($filesource == TCI_SOURCE_EXTERNAL) and empty($CFG->completionimportdir)) {
    // To set an external file on the server, the setting $CFG->completionimportdir must be set in the config file.
    echo $OUTPUT->notification(get_string('sourcefile_noconfig', 'totara_completionimport'), 'notifyproblem');

    $importurl = new moodle_url('/totara/completionimport/upload.php', array('filesource' => TCI_SOURCE_UPLOAD));
    echo html_writer::link($importurl, format_string(get_string('uploadvia_form', 'totara_completionimport')));
    echo $OUTPUT->footer();
    exit;
}

if (!empty($importname)) {
    // Lets do it!
    require_sesskey();

    // Save the form settings for next time.
    set_config_data($data, $importname);

    if ($filesource == TCI_SOURCE_EXTERNAL) {
        // File should already be uploaded by FTP.
        if (!is_readable($data->sourcefile)) {
            echo $OUTPUT->notification(get_string('unreadablefile', 'totara_completionimport', $data->sourcefile), 'notifyproblem');
        } else if (!$handle = fopen($data->sourcefile, 'r')) {
            echo $OUTPUT->notification(get_string('erroropeningfile', 'totara_completionimport', $data->sourcefile), 'notifyproblem');
        } else {
            $size = filesize($data->sourcefile);
            $content = fread($handle, $size);
        }

        // Legacy code that moves the file to a temporary location. Doing this for the external file source
        // option only. Not really necessary any more to move (rather than delete) the file but maintaining
        // existing behaviour and functions for now.

        // Get the temporary path.
        if (!($temppath = get_temppath())) {
            echo $OUTPUT->footer();
            exit;
        }
        // Create a temporary file name.
        if (!($tempfilename = tempnam($temppath, get_tempprefix($importname)))) {
            echo $OUTPUT->notification(get_string('cannotcreatetempname', 'totara_completionimport'), 'notifyproblem');
            echo $OUTPUT->footer();
            exit;
        }
        $tempfilename .= '.csv';
        // Move the file to the temporary location.
        if (!move_sourcefile($data->sourcefile, $tempfilename)) {
            echo $OUTPUT->footer();
            unlink($tempfilename);
            exit;
        }

    } else if ($filesource == TCI_SOURCE_UPLOAD) {
        // Uploading via a form.
        if ($importname == 'course') {
            $content = $courseform->get_file_content('course_uploadfile');
        } else if ($importname == 'certification') {
            $content = $certform->get_file_content('certification_uploadfile');
        }
    } else {
        echo $OUTPUT->notification(get_string('invalidfilesource', 'totara_completionimport', $filesource), 'notifyproblem');
        echo $OUTPUT->footer();
        exit;
    }

    $importtime = time();
    if ($importname === 'course') {
        // Importtime is used to filter the import table for this run.
        $errors = \totara_completionimport\csv_import::import($content, $importname, $importtime);
        if (empty($errors)) {
            echo $OUTPUT->notification(get_string('csvimportdone', 'totara_completionimport'), 'notifysuccess');
        } else {
            echo $OUTPUT->notification(get_string('importerror_' . $importname, 'totara_completionimport'), 'notifyproblem');
        }
    } else if ($importname === 'certification') {
        // Run basic sanity check
        //$errors = \totara_completionimport\csv_import::sanity_check_csv($content, $importname);

        // Do initial import (sanity check and import data into import table)
        $errors = \totara_completionimport\csv_import::basic_import($content, $importname, $importtime);

        if (empty($errors)) {

            // Run adhoc task to process imported data
            $adhoctask = new \totara_completionimport\task\import_certification_completions_task();
            $adhoctask->set_custom_data(['importname' => $importname, 'importtime' => $importtime]);

            \core\task\manager::queue_adhoc_task($adhoctask);

            echo $OUTPUT->notification(get_string('certificationcsvdone', 'totara_completionimport'), 'notifysuccess');
        } else {
            echo $OUTPUT->notification(get_string('importerror_' . $importname, 'totara_completionimport'), 'notifyproblem');
        }
    }

    $data = get_import_results_data($importname, $importtime);

    $viewurl = new moodle_url('/totara/completionimport/viewreport.php',
                ['importname' => $importname, 'timecreated' => $importtime, 'importuserid' => $USER->id, 'clearfilters' => 1]);

    $data->reportlink = [
        'text' => format_string(get_string('report_' . $importname, 'totara_completionimport')),
        'link' => $viewurl
    ];

    // Add errors to data for rendering
    $data->errors = $errors;

    $import_results_output = \totara_completionimport\output\import_results::create_from_import($data, $importname);
    $results_template_data = $import_results_output->get_template_data();

    echo $OUTPUT->render_from_template('totara_completionimport/completionimport_import_results', $results_template_data);

    echo $OUTPUT->footer();
    exit;
}

// Display upload course heading + fields to import.
$courseform->display();

// Display upload certification heading + fields to import.
if (advanced_feature::is_enabled('certifications')) {
    $certform->display();
}

if ($filesource == TCI_SOURCE_EXTERNAL) {
    $importurl = new moodle_url('/totara/completionimport/upload.php', array('filesource' => TCI_SOURCE_UPLOAD));
    echo html_writer::link($importurl, format_string(get_string('uploadvia_form', 'totara_completionimport')));
} else {
    $importurl = new moodle_url('/totara/completionimport/upload.php', array('filesource' => TCI_SOURCE_EXTERNAL));
    echo html_writer::link($importurl, format_string(get_string('uploadvia_directory', 'totara_completionimport')));
}

echo $OUTPUT->footer();
