<?php

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'theme_kineo';
$plugin->name = get_string('pluginname', $plugin->component);
$plugin->version = 2021111100;
$plugin->requires = 2021110500;
$plugin->release = \local_core\ComposerPluginInfo::getInstance()->getInstalledPluginVersion('theme-framework');

