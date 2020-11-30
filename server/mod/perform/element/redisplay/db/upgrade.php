<?php
/**
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Marco Song <marco.song@totaralearning.com>
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

function xmldb_performelement_redisplay_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2020112500) {

        // Define table perform_element_redisplay_relationship to be created.
        $table = new xmldb_table('perform_element_redisplay_relationship');

        // Adding fields to table perform_element_redisplay_relationship.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('source_activity_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('source_section_element_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('redisplay_element_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table perform_element_redisplay_relationship.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('redisplay_element_id', XMLDB_KEY_FOREIGN, array('redisplay_element_id'), 'perform_element', array('id'), 'cascade');

        // Conditionally launch create table for perform_element_redisplay_relationship.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Redisplay savepoint reached.
        upgrade_plugin_savepoint(true, 2020112500, 'performelement', 'redisplay');
    }
}