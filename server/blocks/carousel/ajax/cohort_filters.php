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

use block_carousel\helper;

define('AJAX_SCRIPT', true);

require_once('../../../config.php');
require_once($CFG->dirroot . '/blocks/carousel/lib.php');
require_once($CFG->dirroot . '/blocks/carousel/curatedlib.php');

require_login();

global $DB, $PAGE;

$blockinstanceid = required_param('blockid', PARAM_INT);

$renderer = $PAGE->get_renderer('block_carousel');

$cohorts = helper::get_cohorts();
$existing_cohorts = helper::get_carousel_cohorts($blockinstanceid);

$data = new stdClass();
$data->blockinstanceid = $blockinstanceid;
// Build data for template
foreach ($cohorts as $cohort) {
    if (array_keys(array_combine(array_keys($existing_cohorts), array_column($existing_cohorts, 'id')),$cohort->id)) {
        $cohort->selected = true;
    }
    $data->cohorts[] = $cohort;
}

$output = $renderer->render_cohort_filters($data);

echo json_encode(array('html' => $output));