<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright Kineo 2019
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_block_related_courses_upgrade($oldversion, $block)
{
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 201910600) {
        set_config('imagesource', 'summary', 'block_related_courses');
        upgrade_plugin_savepoint(true, 2019101600, 'block', 'related_courses');
    }

    return true;
}