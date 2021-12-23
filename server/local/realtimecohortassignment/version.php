<?php
/**
 * Version details.
 *
 * @package   local_realtimecohortassignment
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die;

$plugin->version = 2021111100;
$plugin->release = \local_core\ComposerPluginInfo::getInstance()->getInstalledPluginVersion('local-realtimecohortassignment');
$plugin->requires = 2021110500;
$plugin->component = 'local_realtimecohortassignment';
$plugin->dependencies = [
    'totara_cohort' => ANY_VERSION,
];

