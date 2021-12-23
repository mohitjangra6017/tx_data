<?php

defined('MOODLE_INTERNAL') || die;

function xmldb_theme_kineo_install()
{
    $resolver = \theme_kineo\SettingsResolver::getInstance();
    $resolver->reloadThemeSettings();

    return true;
}