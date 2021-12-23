<?php
/**
 * Version details
 *
 * @package   block_score
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

$plugin->version = 2021111100;
$plugin->component = 'block_leaderboard';
$plugin->dependencies = [
    'local_leaderboard' => ANY_VERSION,
];
$plugin->release = \local_core\ComposerPluginInfo::getInstance()->getInstalledPluginVersion('block-leaderboard');

