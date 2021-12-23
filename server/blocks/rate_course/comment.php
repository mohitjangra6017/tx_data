<?php
global $COURSE, $DB, $PAGE, $OUTPUT, $USER, $CFG, $SITE;

require_once( '../../config.php' );
require_once($CFG->dirroot.'/blocks/rate_course/forms/comment_review.php');

$reviewid = required_param('reviewid', PARAM_INT);
$external = optional_param('external', 0, PARAM_BOOL);

// Load the course.
$review = $DB->get_record('rate_course_review', array('id' => $reviewid));
$course = $DB->get_record('course', array('id' => $review->course));

// Totara: Add the ability to redirect user out of here if this $id is a non-course.
$hook = new \block_rate_course\hook\comment($course->id);
$hook->execute();
require_login($review->course);

$strtitle = get_string('replyreview', 'block_rate_course');

$context = context_course::instance($review->course);
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->add_body_class('block_rate_course');
$PAGE->set_url('/blocks/rate_course/comment.php', array('reviewid'=>$review->id));
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title($strtitle);
$PAGE->navbar->add($strtitle);
$PAGE->requires->jquery();

require_capability('block/rate_course:comment', $context);

$block = block_instance('rate_course');
$mform = new block_rate_course_comment(null, array('reviewid' => $review->id));

$existing = $block->check_already_commented($review->id);

if ($mform->is_cancelled() or $existing) {
    redirect(new moodle_url('/course/view.php?id='.$review->course));
} else if ($fromform = $mform->get_data()) {
    $block->save_comment($fromform);
    redirect(new moodle_url('/course/view.php?id='.$review->course));
} else if (!empty($external)) {
    $data = new stdClass();
    $data->user_id = $USER->id;
    $data->review_id = $review->id;
    $data->comment = required_param('comment', PARAM_TEXT);
    $block->save_comment($data);
    redirect(new moodle_url('/course/view.php?id='.$review->course));
}

echo $OUTPUT->header($strtitle);
echo $OUTPUT->heading($strtitle);
$mform->display();
echo $OUTPUT->footer($course);
