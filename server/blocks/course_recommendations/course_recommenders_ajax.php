<?php

/**
 * Comment
 *
 * @package    block
 * @subpackage course_recommendations
 * @copyright  &copy; 2017 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */
use container_course\course;
use core_container\factory;

global $DB, $USER, $PAGE, $CFG;

define('AJAX_SCRIPT', true);
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/course_recommendations/lib.php');

$courseid = required_param('courseid', PARAM_INT);
require_login();

if (!factory::from_id($courseid)->is_typeof(course::get_type())) {
    return false;
}

$PAGE->set_url('/blocks/course_recommendations/course_recommenders_ajax.php');
$PAGE->set_context(context_system::instance());

$out = '';
$course_recommenders = get_course_recommenders($USER->id, $courseid);

foreach ($course_recommenders as $recommender) {
    $out .= html_writer::tag('p', fullname($recommender, true));
}

$data['heading'] = get_string('course_recommenders', 'block_course_recommendations');
$data['text'] = html_writer::div($out, 'recommenders');
echo json_encode($data);
