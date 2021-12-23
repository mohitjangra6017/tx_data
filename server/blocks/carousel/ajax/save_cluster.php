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

$blockinstanceid = required_param('blockinstanceid', PARAM_INT);
$curatedid = optional_param('curatedid', 0, PARAM_INT);
$clustername = optional_param('clustername', null, PARAM_TEXT);
$courseids = optional_param_array('courseids', null, PARAM_RAW);
$tagids = optional_param_array('tagids', null, PARAM_RAW);
$heroimage = $_FILES['heroimage']['tmp_name'];

if (empty($curatedid)) {
    // Its a new insert
    $data_curated = new stdClass();
    $data_curated->blockinstanceid = $blockinstanceid;
    $data_curated->name = $clustername;
    $data_curated->timemodified = time();
    $data_curated->timecreated = time();
    
    $curatedid = $DB->insert_record('block_carousel_curated', $data_curated, true);
} else {
    $data_curated = $DB->get_record('block_carousel_curated', ['id' => $curatedid]);
    $data_curated->name = $clustername;
    $DB->update_record('block_carousel_curated', $data_curated);
}

// Insert hero image
if (!empty($heroimage)) {
    raise_memory_limit(MEMORY_EXTRA);
    
    // TODO sanitize the Image
    // Check extension and mime type
    $err_msg = '';
    $filename = basename($_FILES["heroimage"]["name"]);
    $extension = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
    $allowed = array('gif', 'png', 'jpg', 'jpeg');
    if (!in_array($extension, $allowed)) {
        $err_msg = 'Uploaded file is not an image file';
    }
    
    if (!$err_msg) {
        // Delete image if any
        if (!empty($data_curated->id) && !empty($data_curated->filename)) {
            \block_carousel\delete_curated_image($blockinstanceid, $data_curated);
        }
        
        // Move to temp dir
        $temp_file_path = $CFG->tempdir . '/' . $filename;
        move_uploaded_file($_FILES["heroimage"]["tmp_name"], $temp_file_path);

        $context = context_block::instance($blockinstanceid);

        $fs = get_file_storage();
        // Prepare file record object
        $fileinfo = array(
            'contextid' => $context->id, 
            'component' => 'block_carousel',     
            'filearea' => 'curated',    
            'itemid' => $curatedid,               
            'filepath' => '/',          
            'filename' => $filename
        ); 

        $file_info = $fs->create_file_from_pathname($fileinfo, $temp_file_path);

        unlink($temp_file_path);

        // Update block_carousel_curated with filename
        if (!empty($file_info)) {
            $data_curated = $DB->get_record('block_carousel_curated', ['id' => $curatedid]);
            $data_curated->filename = $file_info->get_filename();
            $DB->update_record('block_carousel_curated', $data_curated);
        }
    }
}

// Insert / update curated courses
// Delete existing records
// Its way too much work to check for existing records
// delete missing data etc.
$DB->delete_records('block_carousel_curated_course', ['curatedid' => $curatedid]);
// Insert new set
block_carousel\insert_curated_course($curatedid, $courseids);

// Insert / update curated tags
$DB->delete_records('block_carousel_curated_tags', ['curatedid' => $curatedid]);
// Insert new set
block_carousel\insert_curated_tags($curatedid, $tagids);

echo json_encode(['success' => true]);