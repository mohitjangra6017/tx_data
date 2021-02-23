<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
defined('MOODLE_INTERNAL') || die();

function xmldb_totara_notification_upgrade($old_version) {
    global $DB, $CFG;
    require_once("{$CFG->dirroot}/totara/notification/db/upgradelib.php");

    $db_manager = $DB->get_manager();

    if ($old_version < 2021012001) {
        // This is for continuous development only.
        // Define table notification_preference to be created.
        $table = new xmldb_table('notification_preference');

        // Adding fields to table notification_preference.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('ancestor_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('event_class_name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('notification_class_name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('context_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('recipient', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('subject', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('body', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('body_format', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('time_created', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table notification_preference.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('context_id_key', XMLDB_KEY_FOREIGN, ['context_id'], 'context', ['id']);

        // Adding indexes to table notification_preference.
        $table->add_index('event_name_index', XMLDB_INDEX_NOTUNIQUE, ['event_class_name']);
        $table->add_index('notification_name_index', XMLDB_INDEX_NOTUNIQUE, ['notification_class_name']);
        $table->add_index('title_index', XMLDB_INDEX_NOTUNIQUE, ['title']);

        // Conditionally launch create table for notification_preference.
        if (!$db_manager->table_exists($table)) {
            $db_manager->create_table($table);
        }

        // Notification savepoint reached.
        upgrade_plugin_savepoint(true, 2021012001, 'totara', 'notification');
    }

    if ($old_version < 2021012002) {
        totara_notification_sync_built_in_notification();

        // Notification savepoint reached.
        upgrade_plugin_savepoint(true, 2021012002, 'totara', 'notification');
    }

    if ($old_version < 2021012003) {
        // This is for continuous development only.
        $table = new xmldb_table('notification_queue');

        // Define field notification_name to be dropped from notification_queue.
        $old_index = new xmldb_index('notification_name_index', XMLDB_INDEX_NOTUNIQUE, array('notification_name'));
        $old_field = new xmldb_field('notification_name');

        $new_field = new xmldb_field(
            'notification_preference_id',
            XMLDB_TYPE_INTEGER,
            '10',
            null,
            null,
            null,
            null,
            'id'
        );

        $preference_key = new xmldb_key(
            'notification_preference_id_key',
            XMLDB_KEY_FOREIGN,
            ['notification_preference_id'],
            'notification_preference',
            ['id']
        );

        if (0 != $DB->count_records('notification_queue')) {
            // For the table that has records, we will have to do the following:
            // + Add the field notification_preference_id but leave it nullable
            // + Search for preference table with notification_name
            // + Populate the data on column
            // + Delete the field notification_name field
            // + Make the field notification_preference_id not-nullable

            // Add the field first but it has to be null-able, then fetch the records.
            $new_field->setNotNull(false);
            if (!$db_manager->field_exists($table, $new_field)) {
                $db_manager->add_field($table, $new_field);

                if ($db_manager->field_exists($table, $old_field)) {
                    // We do not need context's id here, because the system firstly install/upgrade will not have
                    // the overridden record at lower context just yet. Hence using context_system here.
                    $records = $DB->get_records_sql('SELECT id, notification_name FROM "ttr_notification_queue"');
                    $context_id = $DB->get_field('context', 'id', ['contextlevel' => CONTEXT_SYSTEM]);

                    foreach ($records as $record) {
                        $preference_id = $DB->get_field(
                            'notification_preference',
                            'id',
                            [
                                'notification_class_name' => $record->notification_name,
                                'context_id' => $context_id
                            ],
                            MUST_EXIST
                        );

                        $record->notification_preference_id = $preference_id;
                        $DB->update_record('notification_queue', $record);
                    }
                }
            }
        }

        $new_field->setNotNull(XMLDB_NOTNULL);
        if ($db_manager->field_exists($table, $new_field)) {
            // This is probably from the if statement above that we need to populate the data.
            // Hence we will update the field this time.
            $db_manager->change_field_notnull($table, $new_field);
        } else {
            $db_manager->add_field($table, $new_field);
        }

        // Conditionally launch drop index notification_name_index.
        if ($db_manager->index_exists($table, $old_index)) {
            $db_manager->drop_index($table, $old_index);
        }

        // Conditionally launch drop field notification_name.
        if ($db_manager->field_exists($table, $old_field)) {
            $db_manager->drop_field($table, $old_field);
        }

        // Launch add key notification_preference_id_key.
        if (!$db_manager->key_exists($table, $preference_key)) {
            $db_manager->add_key($table, $preference_key);
        }

        // Notification savepoint reached.
        upgrade_plugin_savepoint(true, 2021012003, 'totara', 'notification');
    }

    if ($old_version < 2021012004) {
        // Add the scheduled tasks changes
        $table = new xmldb_table('notifiable_event_queue');

        // This field is not null, but must default to the created_time value
        // We're going to insert it, then change it afterwards
        $new_field = new xmldb_field(
            'event_time',
            XMLDB_TYPE_INTEGER,
            '10',
            null,
            null
        );
        if (!$db_manager->field_exists($table, $new_field)) {
            $db_manager->add_field($table, $new_field);

            // Set any to the time_created default
            $DB->execute(
                'UPDATE "ttr_notifiable_event_queue" 
                SET event_time = time_created 
                WHERE event_time IS NULL'
            );

            // Now make it not null
            $new_field->setNotNull(XMLDB_NOTNULL);
            $db_manager->change_field_notnull($table, $new_field);
        }

        $index = new xmldb_index('event_time_index', XMLDB_INDEX_NOTUNIQUE, array('event_time'));
        if (!$db_manager->index_exists($table, $index)) {
            $db_manager->add_index($table, $index);
        }

        // Notification preferences
        $pref_table = new xmldb_table('notification_preference');
        $offset = new xmldb_field(
            'schedule_offset',
            XMLDB_TYPE_INTEGER,
            '3',
            null,
            null,
        );
        if (!$db_manager->field_exists($pref_table, $offset)) {
            $db_manager->add_field($pref_table, $offset);
        }

        // For any preferences that do not have an ancestor we will default
        // their offset to 0 (on_event).
        $DB->execute(
            'UPDATE "ttr_notification_preference" 
                SET schedule_offset = 0 
                WHERE schedule_offset IS NULL AND ancestor_id IS NULL'
        );

        upgrade_plugin_savepoint(true, 2021012004, 'totara', 'notification');
    }

    if ($old_version < 2021012005) {
        // Define field subject_format to be added to notification_preference.
        $table = new xmldb_table('notification_preference');
        $field = new xmldb_field('subject_format', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'subject');

        // Conditionally launch add field subject_format.
        if (!$db_manager->field_exists($table, $field)) {
            $db_manager->add_field($table, $field);
        }

        $records = $DB->get_records_sql('
            SELECT id FROM "ttr_notification_preference" 
            WHERE ancestor_id IS NULL AND notification_class_name IS NULL
        ');

        foreach ($records as $record) {
            // We are defaulting format to FORMAT_MOODLE
            $record->subject_format = FORMAT_MOODLE;
            $DB->update_record(
                'notification_preference',
                $record
            );
        }

        // Notification savepoint reached.
        upgrade_plugin_savepoint(true, 2021012005, 'totara', 'notification');
    }

    return true;
}