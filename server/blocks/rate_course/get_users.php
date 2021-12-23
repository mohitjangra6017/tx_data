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
 * Returns a user list that can be utilised dynamically with Select2 form.
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  &copy; 2014 Nine Lanterns Pty Ltd  {@link http://www.ninelanterns.com.au}
 * @author     james.ballard
 * @version    1.0
 */

require_once( '../../config.php' );

global $DB, $USER, $PAGE, $SITE;

$courseid = required_param('courseid', PARAM_INT);
$query = optional_param('q', '', PARAM_TEXT);
$limit = optional_param('page_limit', 10, PARAM_INT);

// Totara: Add the ability to redirect user out of here if this $id is a non-course.
$hook = new \block_rate_course\hook\get_users($courseid);
$hook->execute();

require_login($courseid);

$course = $DB->get_record('course', array('id' => $courseid));

$strtitle = get_string('likereview', 'block_rate_course');

$context = context_course::instance($course->id);
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->add_body_class('block_rate_course');
$PAGE->set_url('/blocks/rate_course/get_users.php');
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('incourse');

$block = block_instance('rate_course');

$users = $block->get_recommendee_list($course->id, $query, $limit);

echo json_encode($users);


