<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Cohort related management functions, this file needs to be included manually.
 *
 * @package    core_cohort
 * @copyright  2010 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../config.php');
require_once($CFG->dirroot.'/cohort/locallib.php');

$id = required_param('id', PARAM_INT);
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);

require_login();

$cohort = $DB->get_record('cohort', array('id' => $id), '*');
if (!$cohort) {
    $url = new moodle_url('/cohort/index.php');
    redirect($url, get_string('error:badcohortid','totara_cohort'), null, \core\notification::ERROR);
}

$context = context::instance_by_id($cohort->contextid, MUST_EXIST);
$PAGE->set_context($context);
$baseurl = new moodle_url('/cohort/assign.php', ['id' => $id, 'contextid' => $context->id]);
if ($context->contextlevel == CONTEXT_SYSTEM) {
    admin_externalpage_setup('cohorts', '', [], $baseurl);
} else {
    $PAGE->set_url($baseurl);
    $PAGE->set_heading($COURSE->fullname);
    $PAGE->set_pagelayout('admin');
}
$PAGE->set_title(get_string('assigncohorts', 'cohort'));

require_capability('moodle/cohort:assign', $context);

if ($returnurl) {
    $returnurl = new moodle_url($returnurl);
} else {
    $returnurl = new moodle_url('/cohort/index.php', array('contextid' => $cohort->contextid));
}

if (!empty($cohort->component)) {
    // We can not manually edit cohorts that were created by external systems, sorry.
    redirect($returnurl);
}

if (optional_param('cancel', false, PARAM_BOOL)) {
    redirect($returnurl);
}

if ($context->contextlevel == CONTEXT_COURSECAT) {
    navigation_node::override_active_url(new moodle_url('/cohort/index.php', array('contextid' => $context->id)));
} else {
    navigation_node::override_active_url(new moodle_url('/cohort/index.php'));
}
totara_cohort_navlinks($cohort->id, format_string($cohort->name), get_string('assign', 'cohort'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('assignto', 'cohort', format_string($cohort->name)));
echo cohort_print_tabs('editmembers', $cohort->id, $cohort->cohorttype, $cohort);
echo $OUTPUT->notification(get_string('removeuserwarning', 'core_cohort'));

// Get the user_selector we will need.
$potentialuserselector = new cohort_candidate_selector('addselect', array('cohortid'=>$cohort->id, 'accesscontext'=>$context));
$potentialuserselector->enabletenantrestrictions = true;
$existinguserselector = new cohort_existing_selector('removeselect', array('cohortid'=>$cohort->id, 'accesscontext'=>$context));

// Get current roles assigned to this cohort.
$currentcohortroles = totara_get_cohort_roles($cohort->id);

// Process incoming user assignments to the cohort

if (optional_param('add', false, PARAM_BOOL) && confirm_sesskey()) {
    $userstoassign = $potentialuserselector->get_selected_users();
    if (!empty($userstoassign)) {

        $newids = array();
        foreach ($userstoassign as $adduser) {
            cohort_add_member($cohort->id, $adduser->id);
            $newids[$adduser->id] = $adduser->id;
        }
        // Assign roles to new users.
        totara_set_role_assignments_cohort($currentcohortroles, $cohort->id, array_keys($newids));
        // Notify users.
        totara_cohort_notify_add_users($cohort->id, $newids);

        // Create learning plans.
        $config = \totara_cohort\learning_plan_config::get_config($cohort->id);
        if ($config->auto_create_new()) {
            \totara_cohort\learning_plan_helper::create_plans($config);
        }

        $potentialuserselector->invalidate_selected_users();
        $existinguserselector->invalidate_selected_users();
    }
}

// Process removing user assignments to the cohort
if (optional_param('remove', false, PARAM_BOOL) && confirm_sesskey()) {
    $userstoremove = $existinguserselector->get_selected_users();
    if (!empty($userstoremove)) {
        $delids = array();
        foreach ($userstoremove as $removeuser) {
            cohort_remove_member($cohort->id, $removeuser->id);
            $delids[$removeuser->id] = $removeuser->id;
        }
        // Unassign roles to users deleted.
        totara_unset_role_assignments_cohort($currentcohortroles, $cohort->id, array_keys($delids));
        // Notify users.
        totara_cohort_notify_del_users($cohort->id, $delids);

        $potentialuserselector->invalidate_selected_users();
        $existinguserselector->invalidate_selected_users();
    }
}

// Print the form.
?>
<form id="assignform" method="post" action="<?php echo $PAGE->url ?>"><div>
  <input type="hidden" name="sesskey" value="<?php echo sesskey() ?>" />
  <input type="hidden" name="returnurl" value="<?php echo $returnurl->out_as_local_url() ?>" />
  <?php // TL-7840: removed table ?>
  <div class="row-fluid user-multiselect">
    <div class="span5">
      <label for="removeselect"><?php print_string('currentusers', 'cohort'); ?></label>
      <?php $existinguserselector->display() ?>
    </div>
    <div class="span2 controls">
      <input name="add" id="add" type="submit" value="<?php echo $OUTPUT->larrow().'&nbsp;'.s(get_string('add')); ?>" title="<?php p(get_string('add')); ?>" />
      <input name="remove" id="remove" type="submit" value="<?php echo s(get_string('remove')).'&nbsp;'.$OUTPUT->rarrow(); ?>" title="<?php p(get_string('remove')); ?>" />
    </div>
    <div class="span5">
      <label for="addselect"><?php print_string('potusers', 'cohort'); ?></label>
      <?php $potentialuserselector->display() ?>
    </div>
  </div>
  <input type="submit" name="cancel" value="<?php p(get_string('backtocohorts', 'cohort')); ?>" />
</div></form>

<?php

echo $OUTPUT->footer();
