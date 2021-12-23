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
 * Description
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  &copy; 2014 Nine Lanterns Pty Ltd  {@link http://www.ninelanterns.com.au}
 * @author     james.ballard
 * @version    1.0
 */

require_once( '../../config.php' );

global $DB, $COURSE, $USER, $PAGE, $SITE;

$reviewid = required_param('reviewid', PARAM_INT);
$post = optional_param('post', 0, PARAM_INT);

$strtitle = get_string('deletereview', 'block_rate_course');

$review = $DB->get_record('rate_course_review', array('id' => $reviewid));
$course = $DB->get_record('course', array('id' => $review->course));

// Totara: Add the ability to redirect user out of here if this $id is a non-course.
$hook = new \block_rate_course\hook\delete_review($course->id);
$hook->execute();

require_login($course);
require_capability('block/rate_course:delete', context_course::instance($course->id));

$context = context_course::instance($course->id);
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->add_body_class('block_rate_course');
$PAGE->set_url('/blocks/rate_course/delete_review.php');
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title($strtitle);
$PAGE->navbar->add($strtitle);

$block = block_instance('rate_course');
$output = $PAGE->get_renderer('block_rate_course');

$result = $block->delete_review($review->id);


redirect(new moodle_url('/course/view.php', array('id' => $course->id)), get_string('reviewdeleted', 'block_rate_course'));


