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
 * Change a users email address
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package core_user
 */

require_once('../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/user/editlib.php');
require_once($CFG->dirroot.'/user/lib.php');

$key = required_param('key', PARAM_ALPHANUM);
$id  = required_param('id', PARAM_INT);

$PAGE->set_url('/user/emailupdate.php', array('id' => $id, 'key' => $key));
$PAGE->set_context(context_system::instance());

$user = $DB->get_record('user', array('id' => $id));
if ($user === false || $user->deleted) {
    print_error('usernotavailable', 'error');
}

$preferences = get_user_preferences(null, null, $user->id);
$a = new stdClass();
$a->fullname = fullname($user, true);
$stremailupdate = get_string('emailupdate', 'auth', $a);

$PAGE->set_title($SITE->fullname . ": $stremailupdate");
$PAGE->set_heading($SITE->fullname . ": $stremailupdate");

if (empty($preferences['newemailattemptsleft'])) {
    redirect("$CFG->wwwroot/user/profile.php?id=$user->id");

} else if ($preferences['newemailattemptsleft'] < 1) {
    cancel_email_update($user->id);

    echo $OUTPUT->header();
    echo $OUTPUT->box(get_string('auth_outofnewemailupdateattempts', 'auth'), 'center');
    echo $OUTPUT->footer();
} else if ($key == $preferences['newemailkey']) {
    $olduser = clone($user);
    cancel_email_update($user->id);
    $user->email = $preferences['newemail'];

    // Detect duplicate before saving.
    if ($DB->record_exists_select('user', "LOWER(email) = LOWER(:email) AND id <> :userid", ['email' => $user->email, 'userid' => $user->id])) {
        redirect(new moodle_url('/user/profile.php', ['id' => $user->id]), get_string('emailnowexists', 'auth'));
    } else {
        // Update user email.
        $authplugin = get_auth_plugin($user->auth);
        $authplugin->user_update($olduser, $user);
        user_update_user($user, false);
        $a->email = $user->email;
        // Updating the user's email bounce/send count here, and there should be no record tracker involve,
        // as the user confirmed to update the email
        $emailbouncecounter = new core_user\email_bounce_counter($user);
        $emailbouncecounter->reset_counts();
        redirect(
                new moodle_url('/user/profile.php', ['id' => $user->id]),
                get_string('emailupdatesuccess', 'auth', $a),
                null,
                \core\output\notification::NOTIFY_SUCCESS
            );
    }

} else {
    $preferences['newemailattemptsleft']--;
    set_user_preference('newemailattemptsleft', $preferences['newemailattemptsleft'], $user->id);
    echo $OUTPUT->header();
    echo $OUTPUT->box(get_string('auth_invalidnewemailkey', 'auth'), 'center');
    echo $OUTPUT->footer();
}
