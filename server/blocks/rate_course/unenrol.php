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
 * Processes a request to follow another user's feed.
 *
 * @package    block
 * @subpackage event_feed
 * @copyright  &copy; 2014 Nine Lanterns Pty Ltd  {@link http://www.ninelanterns.com.au}
 * @author     james.ballard
 * @version    1.0
 */

require_once('../../config.php');

global $CFG, $DB, $SITE, $PAGE, $USER;

require_once( $CFG->dirroot.'/blocks/rate_course/classes/enrol_self.php' );

$courseid = required_param('courseid', PARAM_INT);
$redirect = required_param('redirecturl', PARAM_RAW);

// Totara: Add the ability to redirect user out of here if this $id is a non-course.
$hook = new \block_rate_course\hook\unenrol($courseid);
$hook->execute();

require_login($courseid);

$strtitle = get_string('unenrol', 'block_rate_course');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->add_body_class('block_rate_course');
$PAGE->set_url('/blocks/rate_course/unenrol.php', array('courseid' => $courseid));
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title($strtitle);
$PAGE->navbar->add($strtitle);

$instances = $DB->get_records('enrol', array('courseid' => $courseid, 'enrol' => 'self'));

if (empty($instances)) {
    redirect(new moodle_url($redirect), get_string('enrolmentnotfound','block_rate_course'));
}

$enrol = new enrol_self_block_rate_course();

foreach ($instances as $instance) {
    $enrol->unenrol_user($instance, $USER->id);
}


redirect(new moodle_url($redirect));
