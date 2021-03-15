<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Fabian Derschatta <fabian.derschatta@totaralearning.com>
 * @package mod_perform
 */

/**
 * Database upgrade script
 *
 * @param integer $oldversion Current (pre-upgrade) local db version timestamp
 * @return bool
 *
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_perform_upgrade($oldversion) {
    global $DB, $CFG;
    require_once(__DIR__ . '/upgradelib.php');

    $dbman = $DB->get_manager();

    // Totara 13.0 release line.

    if ($oldversion < 2020100800) {
        $sql = "UPDATE {report_builder}
                   SET source = 'perform_manage_participation_participant_instance'
                 WHERE source = 'participant_instance_manage_participation'";
        $DB->execute($sql);

        $sql = "UPDATE {report_builder}
                   SET source = 'perform_manage_participation_participant_section'
                 WHERE source = 'participant_section_manage_participation'";
        $DB->execute($sql);

        $sql = "UPDATE {report_builder}
                   SET source = 'perform_manage_participation_subject_instance'
                 WHERE source = 'subject_instance_manage_participation'";
        $DB->execute($sql);

        $sql = "UPDATE {report_builder}
                   SET source = 'perform_participation_participant_instance'
                 WHERE source = 'perform_participant_instance'";
        $DB->execute($sql);

        $sql = "UPDATE {report_builder}
                   SET source = 'perform_participation_participant_section'
                 WHERE source = 'perform_participant_section'";
        $DB->execute($sql);

        $sql = "UPDATE {report_builder}
                   SET source = 'perform_participation_subject_instance'
                 WHERE source = 'perform_subject_instance'";
        $DB->execute($sql);

        $sql = "UPDATE {report_builder}
                   SET source = 'perform_response_element'
                 WHERE source = 'element_performance_reporting'";
        $DB->execute($sql);

        $sql = "UPDATE {report_builder}
                   SET source = 'perform_response_subject_instance'
                 WHERE source = 'subject_instance_performance_reporting'";
        $DB->execute($sql);

        $sql = "UPDATE {report_builder}
                   SET source = 'perform_response_user'
                 WHERE source = 'user_performance_reporting'";
        $DB->execute($sql);

        // Perform savepoint reached.
        upgrade_mod_savepoint(true, 2020100800, 'perform');
    }

    if ($oldversion < 2020101201) {
        // Create records for existing activities that do not already have records for the following notifications:
        mod_perform_upgrade_create_missing_notification_records([
            'completion' => [],
            'due_date' => [],
            'due_date_reminder' => [86400], // Trigger: 1 day (in seconds)
            'instance_created' => [],
            'instance_created_reminder' => [86400], // Trigger: 1 day (in seconds)
            'overdue_reminder' => [86400], // Trigger: 1 day (in seconds)
            'participant_selection' => [],
            'reopened' => [],
        ]);

        // Perform savepoint reached.
        upgrade_mod_savepoint(true, 2020101201, 'perform');
    }

    if ($oldversion < 2020101202) {
        // Define field task_id to be added to perform_subject_instance.
        $table = new xmldb_table('perform_subject_instance');
        $field = new xmldb_field('task_id', XMLDB_TYPE_CHAR, '32', null, null, null, null, 'updated_at');

        // Conditionally launch add field task_id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $index = new xmldb_index('task_id', XMLDB_INDEX_NOTUNIQUE, array('task_id'));

        // Conditionally launch add index task_id.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Define field task_id to be added to perform_participant_instance.
        $table = new xmldb_table('perform_participant_instance');
        $field = new xmldb_field('task_id', XMLDB_TYPE_CHAR, '32', null, null, null, null, 'updated_at');

        // Conditionally launch add field task_id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $index = new xmldb_index('task_id', XMLDB_INDEX_NOTUNIQUE, array('task_id'));

        // Conditionally launch add index task_id.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Perform savepoint reached.
        upgrade_mod_savepoint(true, 2020101202, 'perform');
    }

    if ($oldversion < 2020110900) {
        mod_perform_upgrade_unwrap_response_data();

        upgrade_mod_savepoint(true, 2020110900, 'perform');
    }

    if ($oldversion < 2020121500) {
        mod_perform_upgrade_long_text_responses_to_weka_format();

        upgrade_mod_savepoint(true, 2020121500, 'perform');
    }

    if ($oldversion < 2020122100) {
        $table = new xmldb_table('perform_element_response');
        $created_at_field = new xmldb_field('created_at', XMLDB_TYPE_INTEGER, '10', null, false, null, null, 'response_data');
        $updated_at_field = new xmldb_field('updated_at', XMLDB_TYPE_INTEGER, '10', null, false, null, null, 'created_at');

        // Conditionally launch add field created_at and update_d.
        if (!$dbman->field_exists($table, $created_at_field) && !$dbman->field_exists($table, $updated_at_field)) {
            $dbman->add_field($table, $created_at_field);
            $dbman->add_field($table, $updated_at_field);

            mod_perform_upgrade_element_responses_to_include_timestamps();

            $created_at_field = new xmldb_field('created_at', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'response_data');
            $dbman->change_field_notnull($table, $created_at_field);
        }

        upgrade_mod_savepoint(true, 2020122100, 'perform');
    }

    if ($oldversion < 2020122102) {
        $table = new xmldb_table('perform_element');
        $parent_field = new xmldb_field('parent', XMLDB_TYPE_INTEGER, '10', null, false, null, null, 'context_id');
        $dbman->add_field($table, $parent_field);
        $key = new xmldb_key('parent', XMLDB_KEY_FOREIGN, array('parent'), 'perform_element', array('id'));
        $dbman->add_key($table, $key);

        upgrade_mod_savepoint(true, 2020122102, 'perform');
    }

    if ($oldversion < 2020122104) {
        $table = new xmldb_table('perform_element');
        $sort_order_field = new xmldb_field('sort_order', XMLDB_TYPE_INTEGER, '10', null, false, null, null, 'data');

        if (!$dbman->field_exists($table, $sort_order_field)) {
            $dbman->add_field($table, $sort_order_field);
        }
        $index = new xmldb_index('parent_sort_order', XMLDB_INDEX_UNIQUE, ['parent', 'sort_order']);

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        upgrade_mod_savepoint(true, 2020122104, 'perform');
    }

    if ($oldversion < 2021021800) {
        $table = new xmldb_table('perform_section_element_reference');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('source_section_element_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        $table->add_field('referencing_element_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('source_section_element_id', XMLDB_KEY_FOREIGN, ['source_section_element_id'], 'perform_section_element', ['id'], 'cascade');
        $table->add_key('referencing_element_id', XMLDB_KEY_FOREIGN, ['referencing_element_id'], 'perform_element', ['id'], 'cascade');

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_mod_savepoint(true, 2021021800, 'perform');
    }

    if ($oldversion < 2021031500) {
        $table = new xmldb_table('perform_section_element_reference');
        $index = new xmldb_index('source_referencing_pair', XMLDB_INDEX_UNIQUE, ['referencing_element_id', 'source_section_element_id']);

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }
    }

    return true;
}
