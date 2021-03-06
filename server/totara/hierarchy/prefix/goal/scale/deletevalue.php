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
 * @author Simon Coggins <simon.coggins@totaralms.com>
 * @package totara
 * @subpackage totara_hierarchy
 */

require_once(__DIR__ . '/../../../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/totara/hierarchy/lib.php');
require_once('lib.php');
require_once($CFG->dirroot . '/totara/hierarchy/prefix/goal/lib.php');

// Check if Goals are enabled.
goal::check_feature_enabled();

//
// Setup / loading data.
//

// Get params.
$id = required_param('id', PARAM_INT);
$prefix = required_param('prefix', PARAM_ALPHA);
// Delete confirmation hash.
$delete = optional_param('delete', '', PARAM_ALPHANUM);

// Cache user capabilities.
$sitecontext = context_system::instance();

// Permissions.
require_capability('totara/hierarchy:delete'.$prefix.'scale', $sitecontext);

// Set up the page.
admin_externalpage_setup($prefix.'manage');

if (!$value = $DB->get_record('goal_scale_values', array('id' => $id))) {
    print_error('incorrectgoalscalevalueid', 'totara_hierarchy');
}

$scale = $DB->get_record('goal_scale', array('id' => $value->scaleid));

//
// Display page.
//

$returnparams = array('id' => $value->scaleid, 'prefix' => 'goal');
$returnurl = new moodle_url('/totara/hierarchy/prefix/goal/scale/view.php', $returnparams);
$deleteparams = array('id' => $value->id, 'delete' => md5($value->timemodified), 'sesskey' => $USER->sesskey, 'prefix' => 'goal');
$deleteurl = new moodle_url('/totara/hierarchy/prefix/goal/scale/deletevalue.php', $deleteparams);

// Can't delete if the scale is in use.
if (goal_scale_is_used($value->scaleid)) {
    \core\notification::error(get_string('error:nodeletescalevalueinuse', 'totara_hierarchy'));
    redirect($returnurl);
}

if ($value->id == $scale->defaultid) {
    \core\notification::error(get_string('error:nodeletegoalscalevaluedefault', 'totara_hierarchy'));
    redirect($returnurl);
}

if (!$delete) {
    echo $OUTPUT->header();
    $strdelete = get_string('deletecheckscalevalue', 'totara_hierarchy');

    echo $OUTPUT->confirm($strdelete . html_writer::empty_tag('br') . html_writer::empty_tag('br')
        . format_string($value->name), $deleteurl, $returnurl);

    echo $OUTPUT->footer();
    exit;
}


//
// Delete goal scale.
//

if ($delete != md5($value->timemodified)) {
    \core\notification::error(get_string('error:checkvariable', 'totara_hierarchy'));
    redirect($returnurl);
}

if (!confirm_sesskey()) {
    \core\notification::error(get_string('confirmsesskeybad', 'error'));
    redirect($returnurl);
}

$DB->delete_records('goal_scale_values', array('id' => $value->id));

\hierarchy_goal\event\scale_value_deleted::create_from_instance($value)->trigger();

\core\notification::success(get_string('deletedgoalscalevalue', 'totara_hierarchy', format_string($value->name)));
redirect($returnurl);
