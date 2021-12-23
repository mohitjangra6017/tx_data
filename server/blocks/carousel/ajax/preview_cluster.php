<?php

/**
 * Comment
 *
 * @package    package
 * @subpackage sub_package
 * @copyright  &copy; 2020 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

define('AJAX_SCRIPT', true);

require_once('../../../config.php');
require_once($CFG->dirroot . '/blocks/carousel/lib.php');
require_once($CFG->dirroot . '/blocks/carousel/curatedlib.php');

require_login();

global $DB, $PAGE;

$renderer = $PAGE->get_renderer('block_carousel');

$carouseltype = required_param('carouseltype', PARAM_TEXT);
$blockinstanceid = required_param('blockinstanceid', PARAM_INT);

$form_courseids = optional_param_array('courseids', null, PARAM_RAW);
$form_tagids = optional_param_array('tagids', null, PARAM_RAW);
$clustername = optional_param('clustername', null, PARAM_TEXT);
$curatedid = optional_param('curatedid', 0, PARAM_TEXT);

// Template data
$data = new stdClass();
$data->showedit = false;

// if carousel type is cluster
// then get all clusters
if ($carouseltype == \block_carousel\constants::BKC_CLUSTER) {
    $data->showedit = true; 
    $clusters = $DB->get_records('block_carousel_curated', ['blockinstanceid' => $blockinstanceid]);
    
    foreach ($clusters as $cluster) {
        // Build cluster data
        $cluster_data = new stdClass();
        $cluster_data->clusterid = $cluster->id;
        $cluster_data->clustername = $cluster->name; 
        if ($curatedid == $cluster->id) {
            // Mark this panel as expanded
            $cluster_data->panel_expanded = true;
            // Further to that
            // the user is editing this cluster
            // so show real time preview 
            $cluster_courses = \block_carousel\get_carousel_curated_course($form_courseids, $form_tagids);

            if (!empty($cluster_courses)) {
                foreach ($cluster_courses as $course) {
                    $cluster_data->courses[] = $course;
                }
                $data->clusters[] = $cluster_data;
            }
            continue;
        }

        [$v_sql, $params] = totara_visibility_where(null, 'c.id', 'c.visible', 'c.audiencevisible', 'c');
        $sql = "SELECT bccc.* 
                FROM {block_carousel_curated_course} AS bccc
                JOIN {course} AS c ON c.id = bccc.courseid
                WHERE {$v_sql}
                AND c.containertype = :containertype
                AND bccc.curatedid = :curatedid";

        $params['containertype'] = \container_course\course::get_type();
        $params['curatedid'] = $cluster->id;
        $curated_courses = $DB->get_records_sql($sql, $params);

        $courseids = [];
        
        foreach ($curated_courses as $course) {
            $courseids[] = $course->courseid;
        }
        

        $curated_tags = $DB->get_records('block_carousel_curated_tags', ['curatedid' => $cluster->id]);
        $tagids = [];
        
        foreach ($curated_tags as $tag) {
            $tagids[] = $tag->tagid;
        }
        
        $cluster_courses = \block_carousel\get_carousel_curated_course($courseids, $tagids);
        
        foreach ($cluster_courses as $course) {
            $cluster_data->courses[] = $course;
        }
        
        $data->clusters[] = $cluster_data;
    }
    
    // If curated id is 0
    // and there are courseids and tagids
    // then its a new cluster
    // add this to the preview
    if (empty($curatedid) && (!empty($form_courseids) || !empty($form_tagids))) {
        $cluster_data = new stdClass();
        $cluster_data->clusterid = 0;
        $cluster_data->clustername = $clustername; 
        $cluster_data->panel_expanded = true;

        $cluster_courses = \block_carousel\get_carousel_curated_course($form_courseids, $form_tagids);

        foreach ($cluster_courses as $course) {
            $cluster_data->courses[] = $course;
        }
        $data->clusters[] = $cluster_data;
    }
} else {
    // TODO: avoid duplication of code when you have some downtime
    // just curated contents
    if ((!empty($form_courseids) || !empty($form_tagids))) {
        $cluster_data = new stdClass();
        $cluster_data->clusterid = $curatedid;
        $cluster_data->clustername = $clustername; 
        $cluster_data->panel_expanded = true;

        $cluster_courses = \block_carousel\get_carousel_curated_course($form_courseids, $form_tagids);

        foreach ($cluster_courses as $course) {
            $cluster_data->courses[] = $course;
        }
        $data->clusters[] = $cluster_data;
    }
}

$preview_list = $renderer->render_curated_preview_list($data);

echo json_encode(array('html' => $preview_list));