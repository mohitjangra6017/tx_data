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
        // This is for continuous development only.
        $table = new xmldb_table('notification_queue');

        // Define field notification_name to be dropped from notification_queue.
        $old_index = new xmldb_index('notification_name_index', XMLDB_INDEX_NOTUNIQUE, array('notification_name'));

        // Conditionally launch drop index notification_name_index.
        if ($db_manager->index_exists($table, $old_index)) {
            $db_manager->drop_index($table, $old_index);
        }

        $old_field = new xmldb_field('notification_name');
        // Conditionally launch drop field notification_name.
        if ($db_manager->field_exists($table, $old_field)) {
            $db_manager->drop_field($table, $old_field);
        }

        $new_field = new xmldb_field(
            'notification_preference_id',
            XMLDB_TYPE_INTEGER,
            '10',
            null,
            XMLDB_NOTNULL,
            null,
            null,
            'id'
        );

        // Conditionally launch add field notification_preference_id.
        if (!$db_manager->field_exists($table, $new_field)) {
            $db_manager->add_field($table, $new_field);
        }

        $preference_key = new xmldb_key(
            'notification_preference_id_key',
            XMLDB_KEY_FOREIGN,
            ['notification_preference_id'],
            'notification_preference',
            ['id']
        );

        // Launch add key notification_preference_id_key.
        if (!$db_manager->key_exists($table, $preference_key)) {
            $db_manager->add_key($table, $preference_key);
        }

        // Notification savepoint reached.
        upgrade_plugin_savepoint(true, 2021012002, 'totara', 'notification');
    }

    if ($old_version < 2021012003) {
        totara_notification_sync_built_in_notification();

        // Notification savepoint reached.
        upgrade_plugin_savepoint(true, 2021012003, 'totara', 'notification');
    }

    return true;
}