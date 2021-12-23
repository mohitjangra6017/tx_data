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

require_once( '../../config.php' );

global $CFG, $DB, $SITE, $PAGE;

require_once($CFG->dirroot.'/blocks/rate_course/classes/enrol_self.php');
require_once ($CFG->dirroot.'/totara/core/totara.php');

$courseid = required_param('courseid', PARAM_INT);
$redirect = required_param('redirecturl', PARAM_RAW);
$enrolids = required_param('enrolids', PARAM_RAW);

// Totara: Add the ability to redirect user out of here if this $id is a non-course.
$hook = new \block_rate_course\hook\enrol($courseid);
$hook->execute();

require_login();
if (!totara_course_is_viewable($courseid)) {
    redirect($CFG->wwwroot);
}

$strtitle = get_string('enrol', 'block_rate_course');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->add_body_class('block_rate_course');
$PAGE->set_url('/blocks/rate_course/enrol.php', array('courseid' => $courseid));
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title($strtitle);
$PAGE->navbar->add($strtitle);

$startdate = required_param('startdate', PARAM_RAW);
$start = DateTime::createFromFormat('d/m/Y', $startdate);

$enrol = new enrol_self_block_rate_course();

$enrolids = json_decode($enrolids);

foreach ($enrolids as $enrolid) {
    $instance = $DB->get_record('enrol', array('id' => $enrolid));

    $data = new stdClass();
    $data->id = $courseid;
    $data->instance = $instance->id;
    $data->startdate = $start->format('U');

    $enrol->enrol_self($instance, $data);
}

redirect(new moodle_url($redirect));
