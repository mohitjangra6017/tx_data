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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_contentmarketplace
 */

use core\orm\query\builder;

/**
 * Local database upgrade script
 *
 * @param   integer $oldversion Current (pre-upgrade) local db version timestamp
 * @return  boolean $result
 */
function xmldb_totara_contentmarketplace_upgrade($oldversion) {
    $DB = builder::get_db();
    $dbman = $DB->get_manager();

    if ($oldversion < 2021061501) {
        $condition = [
            'component' => 'totara_contentmarketplace',
            'classname' => '\totara_contentmarketplace\task\welcome_notification_task'
        ];
        if ($DB->record_exists('task_adhoc', $condition)) {
            $DB->delete_records('task_adhoc', $condition);
        }

        upgrade_plugin_savepoint(true, 2021061501, 'totara', 'contentmarketplace');
    }

    if ($oldversion < 2021061502) {
        // Define table totara_contentmarketplace_course_source to be created.
        $table = new xmldb_table('totara_contentmarketplace_course_source');

        // Adding fields to table totara_contentmarketplace_course_source.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('learning_object_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('course_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('marketplace_component', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table totara_contentmarketplace_course_source.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table totara_contentmarketplace_course_source.
        $table->add_index('learning_object_idx', XMLDB_INDEX_NOTUNIQUE, array('learning_object_id'));
        $table->add_index('course_idx', XMLDB_INDEX_NOTUNIQUE, array('course_id'));
        $table->add_index('marketplace_component_idx', XMLDB_INDEX_NOTUNIQUE, array('marketplace_component'));

        // Conditionally launch create table for totara_contentmarketplace_course_source.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Contentmarketplace savepoint reached.
        upgrade_plugin_savepoint(true, 2021061502, 'totara', 'contentmarketplace');
    }

    return true;
}