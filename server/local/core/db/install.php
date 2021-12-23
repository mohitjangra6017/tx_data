<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

defined('MOODLE_INTERNAL') || die;

function xmldb_local_core_install()
{
    \local_core\Hook\ReportBuilder\ScormOptimisation::onInstall();
}