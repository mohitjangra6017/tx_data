<?php
/**
 * @copyright   City & Guilds Kineo 2017
 * @author      Steven Hughes <steven.hughes@kineo.com>
 */

use mod_assessment\controller\dashboard_controller;

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
global $CFG, $DB, $PAGE, $SESSION;

require_once($CFG->dirroot . '/totara/reportbuilder/lib.php');
require_login();

$type = optional_param('type', 'standard', PARAM_TEXT);
$controller = dashboard_controller::get_active_controller($type);

$SESSION->assessment['lastdash'] = $PAGE->url->get_param('type');

$controller->render_dashboard();
