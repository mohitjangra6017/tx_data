<?php

/**
 * Comment
 *
 * @package    block
 * @subpackage course_recommendations
 * @copyright  &copy; 2016 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */
global $DB, $USER, $CFG, $PAGE;

require_once('../../config.php');
require_once($CFG->dirroot. '/blocks/course_recommendations/lib.php');

require_login();

$courseid = required_param('courseid', PARAM_INT);
$course = get_course($courseid);

$hook = new \block_course_recommendations\hook\disable($courseid);
$hook->execute();

$PAGE->set_url('/blocks/course_recommendations/disable.php');
$PAGE->set_pagelayout('popup');
$PAGE->set_context(context_system::instance());
$returnurl = get_local_referer(true, $CFG->wwwroot);

disable_recommendation($courseid);
redirect($returnurl, get_string('disable_successful','block_course_recommendations', $course->fullname), 10);
