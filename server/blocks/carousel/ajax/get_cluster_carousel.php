<?php

/**
 * Comment
 *
 * @package    block
 * @subpackage kineo_carousel
 * @copyright  &copy; 2020 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

use container_course\course;

define('AJAX_SCRIPT', true);

require_once('../../../config.php');
require_once($CFG->dirroot . '/blocks/carousel/lib.php');
require_once($CFG->dirroot . '/blocks/carousel/curatedlib.php');

require_login();

global $DB, $PAGE;

$renderer = $PAGE->get_renderer('block_carousel');

$blockinstanceid = required_param('blockinstanceid', PARAM_INT);
$courseids = required_param('courseids', PARAM_TEXT);
$cardcount = required_param('cardcount', PARAM_INT);

$block_instance = $DB->get_record('block_instances', ['id' => $blockinstanceid]);

$courseids_array = explode(',', $courseids);

list($course_insql, $course_inparams) = $DB->get_in_or_equal($courseids_array, SQL_PARAMS_NAMED);
list($v_sql, $v_params) = totara_visibility_where(null, 'c.id', 'c.visible', 'c.audiencevisible', 'c');

$sql = "SELECT DISTINCT c.* 
          FROM {course} c 
         WHERE c.id $course_insql
          AND {$v_sql}
          AND c.format != :courseformat
          AND c.containertype = :containertype";

$params = [
    'visible' => 1,
    'audiencevisible1' => 1,
    'audiencevisible2' => 2,
    'courseformat' => 'site',
    'containertype' => course::get_type()
];
$params += $v_params;

$courses = $DB->get_records_sql($sql, $course_inparams + $params);

$block = block_instance('carousel', $block_instance);

$data = $block->base_template_context();
$carouseltype = new \block_carousel\carouseltype\cluster($block->config, $block->instance);
$data = $carouseltype->build_course_slide_context($courses, get_string('type:'.\block_carousel\constants::BKC_CLUSTER), $data);
         
$data['cardcount'] = $cardcount;
$data['template'] = '_red_nested_contents';

echo $renderer->render_cluster_contents($data);