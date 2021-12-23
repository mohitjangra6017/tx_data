<?php

/**
 * Comment
 *
 * @package    package
 * @subpackage sub_package
 * @copyright  &copy; 2018 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

define('AJAX_SCRIPT', true);

require_once('../../../config.php');

global $PAGE, $DB;

$block_instanceid = required_param('block_instanceid', PARAM_INT);
if (!$block_instance = $DB->get_record('block_instances', array('id' => $block_instanceid, 'blockname' => 'awesome'))) {
    exit;
}

$original_block_context = context_block::instance($block_instance->id);
$parent_context = $original_block_context->get_parent_context();
$can_create_block = has_capability('block/awesome:addinstance', $parent_context);

$PAGE->set_context($parent_context);
$PAGE->set_blocks_editing_capability('block/awesome:addinstance');

if(!isloggedin() || !$can_create_block || !$PAGE->user_is_editing()) {
    core\notification::add(get_string('duplicate:fail', 'block_awesome'), core\notification::ERROR);
    echo json_encode(
        [
            'type' => 'fail',
            'message' => get_string('duplicate:fail', 'block_awesome'),
        ]
    );
    exit;
}

global $DB;
if (!empty($block_instance)) {
    $clone = new stdClass();
    $clone = $block_instance;
    unset($clone->id);
    
    $insert_id = $DB->insert_record('block_instances', $clone, true);
    
    // get block position of the parent
    $parent_position = $DB->get_record('block_positions', array('blockinstanceid' => $block_instanceid));
    if(!empty($parent_position)) {
        $clone_position = new stdClass();
        $clone_position = $parent_position;
        unset($clone_position->id);
        $clone_position->blockinstanceid = $insert_id;
        $clone_position->weight = $clone_position->weight + 1;
        $DB->insert_record('block_positions', $clone_position);
    }
}
core\notification::add(get_string('duplicate:success', 'block_awesome'), core\notification::SUCCESS);
echo json_encode(['success' => true]);
exit;