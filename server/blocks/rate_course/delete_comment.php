<?php

require_once( '../../config.php' );

global $DB, $COURSE, $USER, $PAGE, $SITE;

$commentid = required_param('commentid', PARAM_INT);
$post = optional_param('post', 0, PARAM_INT);

$strtitle = get_string('deletecomment', 'block_rate_course');

$comment = $DB->get_record('rate_course_review_comments', array('id' => $commentid));
$review = $DB->get_record('rate_course_review', array('id' => $comment->review_id));
$course = $DB->get_record('course', array('id' => $review->course));

// Totara: Add the ability to redirect user out of here if this $id is a non-course.
$hook = new \block_rate_course\hook\delete_comment($course->id);
$hook->execute();

$context = context_course::instance($course->id);

require_login($course);
require_capability('block/rate_course:delete_comment', context_course::instance($course->id));

$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->add_body_class('block_rate_course');
$PAGE->set_url('/blocks/rate_course/delete_comment.php');
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title($strtitle);
$PAGE->navbar->add($strtitle);

$block = block_instance('rate_course');
$output = $PAGE->get_renderer('block_rate_course');

$result = $block->delete_comment($comment->id);


redirect(new moodle_url('/course/view.php', array('id' => $course->id)), get_string('commentdeleted', 'block_rate_course'));


