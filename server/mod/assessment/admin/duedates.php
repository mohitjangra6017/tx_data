<?php
/**
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010-2020 Kineo Group Inc.
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
 */

/**
 * Edit assessment due date
 *
 * @package     mod_assessment
 * @author      Russell England <Russell.England@kineo.com>
 * @copyright   2020 Kineo Group Inc. <http://www.kineo.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

use core\notification;
use mod_assessment\form;
use mod_assessment\model;
use mod_assessment\helper;
use mod_assessment\processor;

require_once(__DIR__ . '/../../../config.php');

global $PAGE, $OUTPUT;

$assessmentid = required_param('id', PARAM_INT);
$versionid = optional_param('versionid', null, PARAM_INT);

$assessment = model\assessment::instance(['id' => $assessmentid], MUST_EXIST);

if ($versionid) {
    $version = model\version::instance(['id' => $versionid], MUST_EXIST);
} else {
    $version = mod_assessment\model\version::instance_for_edit($assessment);
}

list($course, $cm) = get_course_and_cm_from_instance($assessment->id, 'assessment');

$context = context_module::instance($cm->id);
require_login($course, null, $cm);
require_capability('mod/assessment:editinstance', $context);

$PAGE->set_url(new moodle_url('/mod/assessment/admin/duedates.php', ['id' => $assessment->id, 'versionid' => $version->id]));

$renderer = $PAGE->get_renderer('assessment');

$mform = new form\assessment_due_form(null, ['version' => $version]);

$data = new stdClass();
$data->id = $assessmentid;
$data->versionid = $version->id;
$data->timedue = $assessment->timedue;
$data->duetype = $assessment->duetype;
$data->duefieldid = $assessment->duefieldid;

$period = helper\assessment_due_helper::get_period_from_days($assessment->duedays);
$data->dueqty = $period['dueqty'];
$data->dueperiod = $period['dueperiod'];

$mform->set_data($data);

if ($formdata = $mform->get_data()) {
    // Convert the qty and period into days.
    $duedays = helper\assessment_due_helper::get_days_from_period($formdata->dueqty, $formdata->dueperiod);

    $datechanged = false;
    $datechanged = $datechanged || (int)$data->timedue !== (int)$formdata->timedue;
    $datechanged = $datechanged || (int)$assessment->duedays !== (int)$duedays;
    $datechanged = $datechanged || (int)$data->duetype !== (int)$formdata->duetype;
    $datechanged = $datechanged || (int)$data->duefieldid !== (int)$formdata->duefieldid;

    // Save the due date.
    $assessment->set_timedue($formdata->timedue);
    $assessment->set_duedays($duedays);
    $assessment->set_duetype($formdata->duetype);
    $assessment->set_duefieldid($formdata->duefieldid);
    $assessment->save();

    $message = get_string('duedates:saved', 'assessment');

    if ($datechanged) {
        // Update time due for users.
        processor\assessment_due_processor::update_users($assessment);

        $message .= ' ' . get_string('duedates:usersupdated', 'assessment');
    }
    notification::success($message);

}

echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);

echo $renderer->navtabs('duedates', $assessment->id, $version->id);

echo html_writer::start_div('assessmentduedatecontainer');

echo $mform->render();

echo html_writer::end_div();

echo $OUTPUT->footer();
