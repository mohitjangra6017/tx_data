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
 * Rate this course
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  2009 Jenny Gray
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was Rewritten for Moodle 2.X By Atar + Plus LTD for Comverse LTD.
 * @copyright &copy; 2011 Comverse LTD.
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
global $COURSE, $DB, $PAGE, $CFG, $SITE, $USER, $OUTPUT;

require_once('../../config.php');
require_once($CFG->dirroot.'/blocks/rate_course/forms/recommend_course.php');
require_once($CFG->dirroot.'/blocks/rate_course/block_rate_course.php');


$courseid = required_param('courseid', PARAM_INT);
$useridto = optional_param('useridto', 0, PARAM_INT);
$external = optional_param('external', 0, PARAM_BOOL);

// Load the course.
$course = $DB->get_record('course', array('id' => $courseid));

// Totara: Add the ability to redirect user out of here if this $id is a non-course.
$hook = new \block_rate_course\hook\recommend($course->id);
$hook->execute();

require_login($course->id);

$strtitle = get_string('recommendcourse', 'block_rate_course');

$context = context_course::instance($course->id);
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->add_body_class('block_rate_course');
$PAGE->set_url('/blocks/rate_course/rate.php', array('courseid'=>$course->id));
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title($strtitle);
$PAGE->navbar->add($strtitle);


$mform = new block_rate_course_recommend(null, array('courseid' => $course->id));

$existing = block_rate_course::check_already_recommended($USER->id, $course->id, $useridto);

if ($mform->is_cancelled() or $existing) {
    redirect(new moodle_url('/course/view.php?id='.$course->id));
} else if (($fromform = $mform->get_data())) {
    block_rate_course::save_recommendation($fromform);
    redirect(new moodle_url('/course/view.php?id='.$course->id));
} else if (!empty($external)) {
    $data = new stdClass();
    $data->userid = $USER->id;
    $data->courseid = (int)$course->id;
    $data->useridto = required_param('useridto', PARAM_INT);
    block_rate_course::save_recommendation($data);
    redirect(new moodle_url('/course/view.php?id='.$course->id));
}

echo $OUTPUT->header($strtitle);
echo $OUTPUT->heading($strtitle);
$mform->display();
echo $OUTPUT->footer($course);
