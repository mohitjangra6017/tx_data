<?php
/*
 * Copyright City & Guilds Kineo 2021
 * Author: Michael Geering <michael.geering@kineo.com>
 */

function xmldb_local_credly_upgrade($oldVersion)
{
    global $DB;
    $manager = $DB->get_manager();

    if ($oldVersion < 2021081101) {
        $table = 'local_credly_badges';
        if (!$manager->table_exists($table)) {
            $manager->install_one_table_from_xmldb_file(__DIR__ . '/install.xml', $table);
        }

        upgrade_plugin_savepoint(true, 2021081101, 'local', 'credly');
    }

    if ($oldVersion < 2021081102) {
        $table = 'local_credly_badge_issues';
        if (!$manager->table_exists($table)) {
            $manager->install_one_table_from_xmldb_file(__DIR__ . '/install.xml', $table);
        }

        upgrade_plugin_savepoint(true, 2021081102, 'local', 'credly');
    }

    if ($oldVersion < 2021081103) {
        $table = new xmldb_table('local_credly_badges');
        if (!$manager->field_exists($table, 'courseid')) {
            $field = new xmldb_field('courseid', XMLDB_TYPE_INTEGER, '10', null, false);
            $manager->add_field($table, $field);
            $key = new xmldb_key('local_credly_badges_courseid', XMLDB_KEY_FOREIGN, ['courseid'], 'course', 'id');
            $manager->add_key($table, $key);
        }

        upgrade_plugin_savepoint(true, 2021081103, 'local', 'credly');
    }

    if ($oldVersion < 2021081104) {
        $table = new xmldb_table('local_credly_badge_issues');
        if (!$manager->field_exists($table, 'courseid')) {
            $field = new xmldb_field('courseid', XMLDB_TYPE_INTEGER, '10', null, false);
            $manager->add_field($table, $field);
            $key = new xmldb_key('local_credly_badge_issues_courseid', XMLDB_KEY_FOREIGN, ['courseid'], 'course', 'id');
            $manager->add_key($table, $key);
        }

        upgrade_plugin_savepoint(true, 2021081104, 'local', 'credly');
    }

    if ($oldVersion < 2021081105) {
        $table = new xmldb_table('local_credly_badges');
        if (!$manager->field_exists($table, 'certificationid')) {
            $field = new xmldb_field('certificationid', XMLDB_TYPE_INTEGER, '10', null, false);
            $manager->add_field($table, $field);
            $key = new xmldb_key('local_credly_badges_certificationid', XMLDB_KEY_FOREIGN, ['certificationid'], 'prog', 'certifid');
            $manager->add_key($table, $key);
        }

        upgrade_plugin_savepoint(true, 2021081105, 'local', 'credly');
    }

    if ($oldVersion < 2021081106) {
        $table = new xmldb_table('local_credly_badges');
        if (!$manager->field_exists($table, 'name')) {
            // This field should be NOT NULL, however there might be data in the table already and that would fail.
            // So we create the field as NULLABLE, with a default of a blank string, then ALTER it to NOT NULL.
            // The data will be filled in the next time someone loads the Badges page.
            $field = new xmldb_field('name', XMLDB_TYPE_CHAR, '255', null, false, false, '');
            $manager->add_field($table, $field);

            $field->setNotNull(true);
            $manager->change_field_type($table, $field);

            $index = new xmldb_index('local_credly_badges_name', XMLDB_INDEX_NOTUNIQUE, ['name']);
            $manager->add_index($table, $index);
        }

        $table = new xmldb_table('local_credly_badge_issues');
        if ($manager->field_exists($table, 'response')) {
            $manager->drop_field($table, new xmldb_field('response'));
        }

        $table = 'local_credly_issue_logs';
        if (!$manager->table_exists($table)) {
            $manager->install_one_table_from_xmldb_file(__DIR__ . '/install.xml', $table);
        }

        upgrade_plugin_savepoint(true, 2021081106, 'local', 'credly');
    }

    if ($oldVersion < 2021081107) {
        $table = new xmldb_table('local_credly_badge_issues');
        if (!$manager->field_exists($table, 'certificationid')) {
            $field = new xmldb_field('certificationid', XMLDB_TYPE_INTEGER, '10', null, false);
            $manager->add_field($table, $field);
            $key =
                new xmldb_key(
                    'local_credly_badge_issues_certificationid',
                    XMLDB_KEY_FOREIGN,
                    ['certificationid'],
                    'prog',
                    'certifid'
                );
            $manager->add_key($table, $key);
        }

        if (!$manager->field_exists($table, 'issueid')) {
            $field = new xmldb_field('issueid', XMLDB_TYPE_CHAR, '255', null, false);
            $manager->add_field($table, $field);
        }

        if (!$manager->field_exists($table, 'timeexpires')) {
            $field = new xmldb_field('timeexpires', XMLDB_TYPE_INTEGER, '10', null, false);
            $manager->add_field($table, $field);
            $index =
                new xmldb_index(
                    'local_credly_badge_issues_timeexpires',
                    XMLDB_INDEX_NOTUNIQUE,
                    ['timeexpires']
                );
            $manager->add_index($table, $index);
        }

        upgrade_plugin_savepoint(true, 2021081107, 'local', 'credly');
    }

    if ($oldVersion < 2021081108) {
        set_config('opt_out_disclaimer', get_string('user_preferences:opt_out:disclaimer', 'local_credly'), 'local_credly');
        upgrade_plugin_savepoint(true, 2021081108, 'local', 'credly');
    }

    if ($oldVersion < 2021081109) {
        $table = new xmldb_table('local_credly_badges');
        if (!$manager->field_exists($table, 'state')) {
            $field = new xmldb_field('state', XMLDB_TYPE_CHAR, '255', null, false);
            $manager->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2021081109, 'local', 'credly');
    }

    if ($oldVersion < 2021081110) {
        $table = 'local_credly_webhook_logs';
        if (!$manager->table_exists($table)) {
            $manager->install_one_table_from_xmldb_file(__DIR__ . '/install.xml', $table);
        }

        upgrade_plugin_savepoint(true, 2021081110, 'local', 'credly');
    }


    return true;
}