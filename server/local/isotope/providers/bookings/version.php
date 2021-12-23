<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

$plugin->component = 'isotopeprovider_bookings';
$plugin->name = get_string('pluginname', $plugin->component);
$plugin->version = 2021111100;
$plugin->requires = 2021110500;
$plugin->release = \local_core\ComposerPluginInfo::getInstance()->getInstalledPluginVersion('isotope-provider-bookings');

