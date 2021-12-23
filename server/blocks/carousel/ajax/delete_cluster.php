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

global $DB;

$blockinstanceid = required_param('blockinstanceid', PARAM_INT);
$curatedid = required_param('curatedid', PARAM_INT);

$curated = $DB->get_record('block_carousel_curated', ['id' => $curatedid]);

if (!empty($curated)) {
    // Proceed with deletion
    
    // Delete curated courses
    $DB->delete_records('block_carousel_curated_course', ['curatedid' => $curatedid]);
    // Delete curated tags
    $DB->delete_records('block_carousel_curated_tags', ['curatedid' => $curatedid]);
    
    // Delete image if any
    $context = context_block::instance($blockinstanceid);
    
    $fs = get_file_storage();
    // Prepare file record object
    $fileinfo = array(
        'component' => 'block_carousel',
        'filearea' => 'curated',     
        'itemid' => $curatedid,              
        'contextid' => $context->id, 
        'filepath' => '/',          
        'filename' => $curated->filename
    ); 

    // Get file
    $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'], 
    $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);

    // Delete it if it exists
    if ($file) {
        $file->delete();
    }
    
    // Finally delete curated record
     $DB->delete_records('block_carousel_curated', ['id' => $curatedid]);
}

echo json_encode(['success' => true]);