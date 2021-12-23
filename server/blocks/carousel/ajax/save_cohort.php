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
$cohortids = optional_param_array('cohortids', null, PARAM_RAW);

// Delete existing records
// Its way too much work to check for existing records
// delete missing data etc.
$DB->delete_records('block_carousel_cohorts', ['blockinstanceid' => $blockinstanceid]);

// Insert new ones
// Add Cohorts 
if (!empty($cohortids)) {
    $data_cohorts = [];
    foreach ($cohortids as $cohortid) {
        $data = new stdClass();
        $data->blockinstanceid = $blockinstanceid;
        $data->cohortid = $cohortid;

        $data_cohorts[] = $data;
    }
    $DB->insert_records('block_carousel_cohorts', $data_cohorts);
}

        
echo json_encode(['success' => true]);