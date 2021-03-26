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

    if ($old_version < 2021012006) {
        totara_notification_add_extend_context_fields('notifiable_event_queue');
        totara_notification_add_extend_context_fields('notification_queue');
        totara_notification_add_extend_context_fields('notification_preference');

        // Notification savepoint reached.
        upgrade_plugin_savepoint(true, 2021012006, 'totara', 'notification');
    }

    if ($old_version < 2021031200) {
        $table = new xmldb_table('notifiable_event_queue');

        // Define field event_time to be dropped from notifiable_event_queue.
        $index = new xmldb_index('event_time_index', XMLDB_INDEX_NOTUNIQUE, array('event_time'));

        // Conditionally launch drop index event_time_index.
        if ($db_manager->index_exists($table, $index)) {
            $db_manager->drop_index($table, $index);
        }

        // Define field event_time to be dropped from notifiable_event_queue.
        $field = new xmldb_field('event_time');

        // Conditionally launch drop field event_time.
        if ($db_manager->field_exists($table, $field)) {
            $db_manager->drop_field($table, $field);
        }

        // Notification savepoint reached.
        upgrade_plugin_savepoint(true, 2021031200, 'totara', 'notification');
    }

    if ($old_version < 2021031201) {
        // Changing precision of field schedule_offset on table notification_preference to (10).
        $table = new xmldb_table('notification_preference');
        $field = new xmldb_field('schedule_offset', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'time_created');

        // Launch change of precision for field schedule_offset.
        $db_manager->change_field_precision($table, $field);

        // Convert all the old schedule offset into the seconds.
        $records = $DB->get_records_sql('
            SELECT id, schedule_offset FROM "ttr_notification_preference" 
            WHERE schedule_offset IS NOT NULL
        ');

        foreach ($records as $record) {
            $record->schedule_offset = $record->schedule_offset * DAYSECS;
            $DB->update_record('notification_preference', $record);
        }

        upgrade_plugin_savepoint(true, 2021031201, 'totara', 'notification');
    }

    if ($old_version < 2021031202) {
        // Convert all the `event_class_name` related field into `resolver_class_name` field, in table
        // notification_preference.
        $table = new xmldb_table('notification_preference');
        $old_index = new xmldb_index('event_class_name_index', XMLDB_INDEX_NOTUNIQUE, array('event_class_name'));

        // Conditionally launch drop index event_class_name_index.
        if ($db_manager->index_exists($table, $old_index)) {
            $db_manager->drop_index($table, $old_index);
        }

        $field = new xmldb_field('event_class_name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'ancestor_id');
        $db_manager->rename_field($table, $field, 'resolver_class_name');

        // Add the new index for field resolver_class_name.
        $new_index = new xmldb_index('resolver_class_name_index', XMLDB_INDEX_NOTUNIQUE, array('resolver_class_name'));
        if (!$db_manager->index_exists($table, $new_index)) {
            $db_manager->add_index($table, $new_index);
        }

        // Update after resolve the field name.
        $event_classes = $DB->get_fieldset_sql('SELECT DISTINCT resolver_class_name FROM "ttr_notification_preference"');

        foreach ($event_classes as $event_cls) {
            $event_cls = ltrim($event_cls, '\\');
            $parts = explode('\\', $event_cls);

            $component = reset($parts);
            $name = end($parts);

            $resolver_class_name = "{$component}\\totara_notification\\resolver\\{$name}";
            $DB->execute(
                '
                    UPDATE "ttr_notification_preference" SET resolver_class_name = :resolver_class_name 
                    WHERE resolver_class_name = :old_event_name
                ',
                [
                    'resolver_class_name' => $resolver_class_name,
                    'old_event_name' => $event_cls
                ]
            );
        }

        upgrade_plugin_savepoint(true, 2021031202, 'totara', 'notification');
    }

    if ($old_version < 2021032304) {
        // Define index event_name_index (not unique) to be dropped form notifiable_event_queue.
        $table = new xmldb_table('notifiable_event_queue');
        $index = new xmldb_index('event_name_index', XMLDB_INDEX_NOTUNIQUE, array('event_name'));

        // Conditionally launch drop index resolver_class_name_index.
        if ($db_manager->index_exists($table, $index)) {
            $db_manager->drop_index($table, $index);
        }

        // Rename field event_name on table notifiable_event_queue to resolver_class_name.
        $table = new xmldb_table('notifiable_event_queue');
        $field = new xmldb_field('event_name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'id');

        // Launch rename field event_name to resolver_class_name.
        $db_manager->rename_field($table, $field, 'resolver_class_name');

        // Define index resolver_class_name_index (not unique) to be added to notifiable_event_queue.
        $table = new xmldb_table('notifiable_event_queue');
        $index = new xmldb_index('resolver_class_name_index', XMLDB_INDEX_NOTUNIQUE, array('resolver_class_name'));

        // Conditionally launch add index resolver_class_name_index.
        if (!$db_manager->index_exists($table, $index)) {
            $db_manager->add_index($table, $index);
        }

        // Notification savepoint reached.
        upgrade_plugin_savepoint(true, 2021032304, 'totara', 'notification');
    }

    if ($old_version < 2021032305) {
        // Define table notifiable_event_preference to be created.
        $table = new xmldb_table('notifiable_event_preference');

        // Adding fields to table notifiable_event_preference.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('resolver_class_name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('context_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('component', XMLDB_TYPE_CHAR, '255', null, null, null, '');
        $table->add_field('area', XMLDB_TYPE_CHAR, '255', null, null, null, '');
        $table->add_field('item_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('enabled', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table notifiable_event_preference.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('context_id_key', XMLDB_KEY_FOREIGN, array('context_id'), 'context', array('id'));

        // Adding indexes to table notifiable_event_preference.
        $table->add_index('resolver_class_name_index', XMLDB_INDEX_NOTUNIQUE, array('resolver_class_name'));

        // Conditionally launch create table for notifiable_event_preference.
        if (!$db_manager->table_exists($table)) {
            $db_manager->create_table($table);
        }

        // Notification savepoint reached.
        upgrade_plugin_savepoint(true, 2021032305, 'totara', 'notification');
    }

    if ($old_version < 2021032306) {
        // Define field default_delivery_channels to be added to notifiable_event_preference.
        $table = new xmldb_table('notifiable_event_preference');
        $field = new xmldb_field('default_delivery_channels', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'enabled');

        // Conditionally launch add field default_delivery_channels.
        if (!$db_manager->field_exists($table, $field)) {
            $db_manager->add_field($table, $field);
        }

        // Notification savepoint reached.
        upgrade_plugin_savepoint(true, 2021032306, 'totara', 'notification');
    }

    return true;
}