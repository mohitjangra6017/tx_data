<?php
/**
 * @copyright City & Guilds Kineo 2019
 * @author Simon Adams <simon.adams@kineo.com>
 */

use local_leaderboard\Utils;

defined('MOODLE_INTERNAL') || die();

function xmldb_local_leaderboard_upgrade($oldversion)
{
    global $DB, $CFG;

    $dbman = $DB->get_manager();

    if ($oldversion < 2019073101) {

        $table = new xmldb_table('local_leaderboard_user');
        $field = new xmldb_field('courseid', XMLDB_TYPE_INTEGER, '10');

        if (!$dbman->field_exists($table, $field)) {

            require_once $CFG->dirroot . '/lib/accesslib.php';

            $index = new xmldb_key('fk_courseid', XMLDB_KEY_FOREIGN, ['courseid'], 'course', ['id']);
            $dbman->add_field($table, $field);
            $dbman->add_key($table, $index);

            $rs = $DB->get_recordset('local_leaderboard_user');

            foreach ($rs as $record) {

                if ($context = context::instance_by_id($record->contextid, IGNORE_MISSING)) {
                    $course = $context->get_course_context(false);
                    $record->courseid = $course->instanceid ?? null;
                    $DB->update_record('local_leaderboard_user', $record);
                }
            }
        }

        upgrade_plugin_savepoint(true, 2019073101, 'local', 'leaderboard');
    }

    if ($oldversion < 2019121200) {
        $table = new xmldb_table('local_leaderboard');
        $field = new xmldb_field('deleted', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'usergrade');

        // Conditionally launch add field deleted.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2019121200, 'local', 'leaderboard');
    }

    if ($oldversion < 2019121201) {
        // Find any cases where the constant got added to the DB, and change it to the correct value.
        $records = $DB->get_records('local_leaderboard', ['eventname' => 'EVENT_NAME_ADHOC']);
        foreach ($records as $record) {
            $record->eventname = Utils::EVENT_NAME_ADHOC;
            $DB->update_record('local_leaderboard', $record);
        }

        upgrade_plugin_savepoint(true, 2019121201, 'local', 'leaderboard');
    }

    return true;
}
