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
 * @package contentmarketplace_linkedin
 */

use contentmarketplace_linkedin\task\create_oauth2_client_provider_task;
use core\orm\query\builder;

defined('MOODLE_INTERNAL') || die();

function xmldb_contentmarketplace_linkedin_upgrade($old_version): bool {
    global $DB;
    $db_manager = $DB->get_manager();

    if ($old_version < 2021081800) {
        // This is for temporary
        create_oauth2_client_provider_task::enqueue();
    }

    if ($old_version < 2021081900) {
        // Define table linkedin_user_completion to be created.
        $table = new xmldb_table('linkedin_user_completion');

        // Adding fields to table linkedin_user_completion.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('learning_object_urn', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('progress', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('completion', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);
        $table->add_field('time_created', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table linkedin_user_completion.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('user_id_fk', XMLDB_KEY_FOREIGN, ['user_id'], 'user', ['id'], 'cascade');

        // Adding indexes to table linkedin_user_completion.
        $table->add_index('progress_idx', XMLDB_INDEX_NOTUNIQUE, ['progress']);
        $table->add_index('time_created_idx', XMLDB_INDEX_NOTUNIQUE, ['time_created']);

        // Conditionally launch create table for linkedin_user_completion.
        if (!$db_manager->table_exists($table)) {
            $db_manager->create_table($table);
        }

        // Linkedin savepoint reached.
        upgrade_plugin_savepoint(true, 2021081900, 'contentmarketplace', 'linkedin');
    }

    if ($old_version < 2021090200) {
        // Define table marketplace_linkedin_user_completion to be renamed to marketplace_linkedin_user_completion.
        $table = new xmldb_table('linkedin_user_completion');// Define key user_id_fk (foreign) to be dropped form marketplace_linkedin_user_completion.
        $key = new xmldb_key('user_id_fk', XMLDB_KEY_FOREIGN, ['user_id'], 'user', ['id'], 'cascade');

        // Launch drop key user_id_fk.
        if ($db_manager->key_exists($table, $key)) {
            $db_manager->drop_key($table, $key);
        }


        if ($db_manager->table_exists("linkedin_user_completion")) {
            // Launch rename table for marketplace_linkedin_user_completion.
            $db_manager->rename_table($table, 'marketplace_linkedin_user_completion');
            $new_table = new xmldb_table("marketplace_linkedin_user_completion");

            $db_manager->add_key($new_table, $key);
        }

        // Linkedin savepoint reached.
        upgrade_plugin_savepoint(true, 2021090200, 'contentmarketplace', 'linkedin');
    }

    if ($old_version < 2021090201) {
        // Define field availability to be added to marketplace_linkedin_learning_object.
        $table = new xmldb_table('marketplace_linkedin_learning_object');
        $field = new xmldb_field('availability', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'asset_type');

        // Conditionally launch add field availability.
        if (!$db_manager->field_exists($table, $field)) {
            $db_manager->add_field($table, $field);
        }

        $db = builder::get_db();
        $records = $db->get_records('marketplace_linkedin_learning_object');

        foreach ($records as $record) {
            $record->availability = 'AVAILABLE';
            $db->update_record('marketplace_linkedin_learning_object', $record);
        }
        // Linkedin savepoint reached.
        upgrade_plugin_savepoint(true, 2021090201, 'contentmarketplace', 'linkedin');
    }

    return true;
}