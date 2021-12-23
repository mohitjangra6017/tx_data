<?php

/**
 * Comment
 *
 * @package    local
 * @subpackage courses
 * @copyright  &copy; 2017 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

define('AJAX_SCRIPT', true);

require_once('../../../config.php');
require_once($CFG->dirroot . '/blocks/carousel/lib.php');
require_once($CFG->dirroot . '/blocks/carousel/curatedlib.php');

require_login();

global $DB, $PAGE;

$blockinstanceid = required_param('blockinstanceid', PARAM_INT);
$carousel_type = required_param('carouseltype', PARAM_TEXT);
$curatedid = optional_param('curatedid', 0, PARAM_INT);

$param = ['id' => $curatedid];
if (empty($curatedid) && $carousel_type == \block_carousel\constants::BKC_CURATED) {
    $param = ['blockinstanceid' => $blockinstanceid];
    $existing_courses = block_carousel\get_curated_courses($blockinstanceid);
    $existing_tags = block_carousel\get_curated_tags($blockinstanceid);
} else {
    $existing_courses = $DB->get_records('block_carousel_curated_course', ['curatedid' => $curatedid]);
    $existing_tags = $DB->get_records('block_carousel_curated_tags', ['curatedid' => $curatedid]);
}

$curated = $DB->get_record('block_carousel_curated', $param);

$renderer = $PAGE->get_renderer('block_carousel');

$courses = \block_carousel\get_carousel_curated_course();
$tags = \block_carousel\get_tags();

$data = new stdClass();
$data->curatedid = !empty($curated->id) ? $curated->id : 0;
$data->clustername = !empty($curated->name) ? $curated->name : null;
$data->blockinstanceid = $blockinstanceid;
$data->currentimage = \block_carousel\get_cluster_slide_image_url($blockinstanceid, $curated, false);

// Build data for template
foreach ($courses as $course) {
    if (array_keys(array_combine(array_keys($existing_courses), array_column($existing_courses, 'courseid')),$course->id)) {
        $course->selected = true;
    }
    $data->courses[] = $course;
}
foreach ($tags as $tag) {
    if (array_keys(array_combine(array_keys($existing_tags), array_column($existing_tags, 'tagid')),$tag->id)) {
        $tag->selected = true;
    }
    $data->tags[] = $tag;
}

$output = $renderer->render_curated_filters($data);

echo json_encode(array('html' => $output));