<?php
/**
 * Version details.
 *
 * @package   local_score
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version = 2021111100;
$plugin->requires = 2021110500;
$plugin->component = 'local_leaderboard';
$plugin->dependencies = array(
);
$plugin->release = \local_core\ComposerPluginInfo::getInstance()->getInstalledPluginVersion('local-leaderboard');

