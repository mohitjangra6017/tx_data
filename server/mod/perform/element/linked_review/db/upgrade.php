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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Kunle Odusan <kunle.odusan@totaralearning.com>
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Database upgrade script
 *
 * @param integer $oldversion Current (pre-upgrade) local db version timestamp
 * @return bool
 *
 */
function xmldb_performelement_linked_review_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2021030100) {
        // Define table perform_element_linked_review_content_responses to be created.
        $table = new xmldb_table('perform_element_linked_review_content_response');

        // Adding fields to table perform_element_linked_review_content_responses.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('linked_review_content_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('element_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('participant_instance_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('response_data', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('created_at', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('updated_at', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table perform_element_linked_review_content_responses.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('linked_review_content_id', XMLDB_KEY_FOREIGN, array('linked_review_content_id'), 'perform_element_linked_review_content', array('id'));
        $table->add_key('element_id', XMLDB_KEY_FOREIGN, array('element_id'), 'perform_element', array('id'));
        $table->add_key('participant_instance_id', XMLDB_KEY_FOREIGN, array('participant_instance_id'), 'perform_participant_instance', array('id'), 'cascade');

        // Conditionally launch create table for perform_element_linked_review_content_responses.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Linked_review savepoint reached.
        upgrade_plugin_savepoint(true, 2021030100, 'performelement', 'linked_review');
    }

    return true;
}
