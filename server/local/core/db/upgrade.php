<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

defined('MOODLE_INTERNAL') || die;

function xmldb_local_core_upgrade($oldVersion)
{
    global $DB;
    $manager = $DB->get_manager();

    if ($oldVersion < 2021092101) {
        $table = 'local_core_optimised_scorm_sco_track';
        if (!$manager->table_exists($table)) {
            $manager->install_one_table_from_xmldb_file(__DIR__ . '/install.xml', $table);
        }
        \local_core\Hook\ReportBuilder\ScormOptimisation::onInstall();
        upgrade_plugin_savepoint(true, 2021092101, 'local', 'core');
    }

    return true;
}