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
global $DB, $USER, $CFG, $OUTPUT, $PAGE;

require_once('../../config.php');
require_once($CFG->dirroot. '/blocks/course_recommendations/lib.php');

$courseid = required_param('courseid', PARAM_INT);

$hook = new \block_course_recommendations\hook\recommenders($courseid);
$hook->execute();

require_login();

$PAGE->set_url('/blocks/course_recommendations/course_recommenders_ajax.php');
$PAGE->set_pagelayout('popup');
$PAGE->set_context(context_system::instance());

$title = get_string('course_recommenders', 'block_course_recommendations');;
$PAGE->set_title($title);

echo $OUTPUT->header($title);
$course_recommenders = get_course_recommenders($USER->id, $courseid);

$out = '';
if($course_recommenders) {
    foreach ($course_recommenders as $recommender) {
        $out .= html_writer::tag('p', fullname($recommender, true));
    }
}
echo html_writer::div($out,'recommenders');
echo $OUTPUT->footer();
