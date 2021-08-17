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
 * @package totara_xapi
 */
defined('MOODLE_INTERNAL') || die();

/**
 * @param int $old_version
 * @return bool
 */
function xmldb_totara_xapi_upgrade(int $old_version): bool {
    global $DB;
    $db_manager = $DB->get_manager();

    if ($old_version < 2021081800) {
        // Define table xapi_statement to be created.
        $table = new xmldb_table('xapi_statement');

        // Adding fields to table xapi_statement.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('statement', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('request_headers', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('component', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('time_created', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table xapi_statement.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table xapi_statement.
        $table->add_index('component_idx', XMLDB_INDEX_NOTUNIQUE, ['component']);
        $table->add_index('time_created_idx', XMLDB_INDEX_NOTUNIQUE, ['time_created']);

        // Conditionally launch create table for xapi_statement.
        if (!$db_manager->table_exists($table)) {
            $db_manager->create_table($table);
        }

        // Paypal savepoint reached.
        upgrade_plugin_savepoint(true, 2021081800, 'totara', 'xapi');
    }

    return true;
}