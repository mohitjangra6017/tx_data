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
require_once($CFG->dirroot . '/totara/hierarchy/prefix/goal/lib.php');
require_once('edit_form.php');

// Check if Goals are enabled.
goal::check_feature_enabled();

//
// Setup / loading data.
//

// Get paramters.
// Scale id; 0 if creating a new scale.
$id = optional_param('id', 0, PARAM_INT);
$prefix = required_param('prefix', PARAM_ALPHA);

// Cache user capabilities.
$sitecontext = context_system::instance();

// Set up the page.
admin_externalpage_setup($prefix.'manage');

if ($id == 0) {
    // Creating new goal scale.
    require_capability('totara/hierarchy:creategoalscale', $sitecontext);

    $scale = new stdClass();
    $scale->id = 0;
    $scale->sortorder = $DB->get_field('goal_framework', 'MAX(sortorder) + 1', array());
    if (!$scale->sortorder) {
        $scale->sortorder = 1;
    }
} else {
    // Editing existing goal scale.
    require_capability('totara/hierarchy:updategoalscale', $sitecontext);

    if (!$scale = $DB->get_record('goal_scale', array('id' => $id))) {
        print_error('incorrectgoalscaleid', 'totara_hierarchy');
    }
}


//
// Handle form data.
//
$scale->description = isset($scale->description) ? $scale->description : '';
$scale->descriptionformat = FORMAT_HTML;
$scale = file_prepare_standard_editor($scale, 'description', $TEXTAREA_OPTIONS, $TEXTAREA_OPTIONS['context'],
                                          'totara_hierarchy', 'goal_scale', $scale->id);
$mform = new edit_scale_form(
    null, // Method (default).
    array( // Customdata.
      'scaleid' => $id
      )
    );
$mform->set_data($scale);

if ($mform->is_cancelled()) {
    // If cancelled.
    redirect("$CFG->wwwroot/totara/hierarchy/framework/index.php?prefix=goal");

} else if ($scalenew = $mform->get_data()) {
    // Update data.

    $scalenew->timemodified = time();
    $scalenew->usermodified = $USER->id;
    $scalenew->description = '';

    // New scale.
    if (empty($scalenew->id)) {
        unset($scalenew->id);
        $transaction = $DB->start_delegated_transaction();
        $scalenew->id = $DB->insert_record('goal_scale', $scalenew);
        $scalenew = file_postupdate_standard_editor($scalenew, 'description', $TEXTAREA_OPTIONS, $TEXTAREA_OPTIONS['context'],
            'totara_hierarchy', 'goal_scale', $scalenew->id);
        $DB->set_field('goal_scale', 'description', $scalenew->description, array('id' => $scalenew->id));
        $scalevalues = explode("\n", trim($scalenew->scalevalues));
        unset($scalenew->scalevalues);
        $sortorder = 1;
        $scaleidlist = array();
        foreach ($scalevalues as $scaleval) {
            if (strlen(trim($scaleval)) != 0) {
                $scalevalrec = new stdClass();
                $scalevalrec->scaleid = $scalenew->id;
                $scalevalrec->name = trim($scaleval);
                $scalevalrec->sortorder = $sortorder;
                $scalevalrec->timemodified = time();
                $scalevalrec->usermodified = $USER->id;
                $scalevalrec->proficient = ($sortorder == 1) ? 1 : 0;
                $result = $DB->insert_record('goal_scale_values', $scalevalrec);
                $scaleidlist[] = $result;
                $sortorder++;
            }
        }
        // Set the default scale value to the least competent one, and the.
        // Proficient scale value to the most competent one.
        if (count($scaleidlist)) {
            $scalenew->defaultid = $scaleidlist[count($scaleidlist)-1];
            $scalenew->proficient = $scaleidlist[0];
            $DB->update_record('goal_scale', $scalenew);
        }
        $transaction->allow_commit();

        $scalenew = $DB->get_record('goal_scale', array('id' => $scalenew->id));
        \hierarchy_goal\event\scale_created::create_from_instance($scalenew)->trigger();

        $notification_text = 'scaleaddedgoal';
    } else {
        // Existing scale.
        $scalenew = file_postupdate_standard_editor($scalenew, 'description', $TEXTAREA_OPTIONS, $TEXTAREA_OPTIONS['context'],
            'totara_hierarchy', 'goal_scale', $scalenew->id);
        $DB->update_record('goal_scale', $scalenew);

        $scalenew = $DB->get_record('goal_scale', array('id' => $scalenew->id));
        \hierarchy_goal\event\scale_updated::create_from_instance($scalenew)->trigger();

        $notification_text = 'scaleupdatedgoal';
    }

    $notification_url = new moodle_url('/totara/hierarchy/prefix/goal/scale/view.php', ['prefix' => 'goal', 'id' => $scalenew->id]);
    \core\notification::success(get_string($notification_text, 'totara_hierarchy', $scalenew->name));
    redirect($notification_url);
}

// Print Page.
$PAGE->navbar->add(get_string("goalframeworks", 'totara_hierarchy'),
    new moodle_url('/totara/hierarchy/framework/index.php', array('prefix' => 'goal')));
if ($id == 0) { // Add.
    $PAGE->navbar->add(get_string('scalescustomcreate'));
    $heading = get_string('scalescustomcreate');
} else {        // Edit.
    $PAGE->navbar->add(get_string('editscale', 'grades', format_string($scale->name)));
    $heading = get_string('editscale', 'grades');
}

echo $OUTPUT->header();
echo $OUTPUT->heading($heading);
$mform->display();

echo $OUTPUT->footer();
