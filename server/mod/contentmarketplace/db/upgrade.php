<?php
/**
 * This file is part of Totara Core
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
 * @package mod_contentmarketplace
 */
defined('MOODLE_INTERNAL') || die();


/**
 * @param int $old_version
 * @return bool
 */
function xmldb_contentmarketplace_upgrade(int $old_version): bool {
    global $DB;
    $db_manager = $DB->get_manager();

    if ($old_version < 2021041301) {
        $table = new xmldb_table('contentmarketplace');

        // Define field learning_object_marketplace_type to be added to contentmarketplace.
        $field_type = new xmldb_field(
            'learning_object_marketplace_component',
            XMLDB_TYPE_CHAR,
            '255',
            null,
            XMLDB_NOTNULL,
            null,
            null,
            'name'
        );

        // Conditionally launch add field learning_object_marketplace_type.
        if (!$db_manager->field_exists($table, $field_type)) {
            $db_manager->add_field($table, $field_type);
        }

        // Define field learning_object_id to be added to contentmarketplace.
        $field_id = new xmldb_field(
            'learning_object_id',
            XMLDB_TYPE_INTEGER,
            '10',
            null,
            XMLDB_NOTNULL,
            null,
            null,
            'learning_object_marketplace_component'
        );

        // Conditionally launch add field learning_object_id.
        if (!$db_manager->field_exists($table, $field_id)) {
            $db_manager->add_field($table, $field_id);
        }

        // Define field time_modified to be added to contentmarketplace.
        $field_time = new xmldb_field(
            'time_modified',
            XMLDB_TYPE_INTEGER,
            '10',
            null,
            XMLDB_NOTNULL,
            null,
            null,
            'learning_object_id'
        );

        // Conditionally launch add field time_modified.
        if (!$db_manager->field_exists($table, $field_time)) {
            $db_manager->add_field($table, $field_time);
        }

        // Define index learning_object_idx (not unique) to be added to contentmarketplace.
        $learning_obj_idx = new xmldb_index('learning_object_idx', XMLDB_INDEX_NOTUNIQUE, ['learning_object_id']);

        // Conditionally launch add index learning_object_idx.
        if (!$db_manager->index_exists($table, $learning_obj_idx)) {
            $db_manager->add_index($table, $learning_obj_idx);
        }

        // Define index learning_object_marketplace_component_idx (not unique) to be added to contentmarketplace.
        $marketplace_component_idx = new xmldb_index(
            'learning_object_marketplace_component_idx',
            XMLDB_INDEX_NOTUNIQUE,
            ['learning_object_marketplace_component']
        );

        // Conditionally launch add index learning_object_marketplace_component_idx.
        if (!$db_manager->index_exists($table, $marketplace_component_idx)) {
            $db_manager->add_index($table, $marketplace_component_idx);
        }

        // Contentmarketplace savepoint reached.
        upgrade_mod_savepoint(true, 2021041301, 'contentmarketplace');
    }

    if ($old_version < 2021062500) {
        // Define field completion_on_launch to be added to contentmarketplace.
        $table = new xmldb_table('contentmarketplace');
        $field = new xmldb_field('completion_condition', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'learning_object_id');

        // Conditionally launch add field completion_on_launch.
        if (!$db_manager->field_exists($table, $field)) {
            $db_manager->add_field($table, $field);
        }

        // Contentmarketplace savepoint reached.
        upgrade_mod_savepoint(true, 2021062500, 'contentmarketplace');
    }


    return true;
}
