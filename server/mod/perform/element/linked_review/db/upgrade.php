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
        $table->add_field('child_element_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('participant_instance_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('response_data', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('created_at', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('updated_at', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table perform_element_linked_review_content_responses.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key(
            'linked_review_content_id',
            XMLDB_KEY_FOREIGN,
            array('linked_review_content_id'),
            'perform_element_linked_review_content',
            array('id')
        );
        $table->add_key('child_element_id', XMLDB_KEY_FOREIGN, array('child_element_id'), 'perform_element', array('id'));
        $table->add_key(
            'participant_instance_id',
            XMLDB_KEY_FOREIGN,
            array('participant_instance_id'),
            'perform_participant_instance',
            array('id'),
            'cascade'
        );

        // Conditionally launch create table for perform_element_linked_review_content_responses.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Linked_review savepoint reached.
        upgrade_plugin_savepoint(true, 2021030100, 'performelement', 'linked_review');
    }

    if ($oldversion < 2021031502) {
        $table = new xmldb_table('perform_element_linked_review_content');
        $content_type_field = new xmldb_field('content_type', XMLDB_TYPE_CHAR, '100', null, true, null, null, 'content_id');

        if (!$dbman->field_exists($table, $content_type_field)) {
            $dbman->add_field($table, $content_type_field);
        }
        $index = new xmldb_index('content_type_id', XMLDB_INDEX_NOTUNIQUE, ['content_type', 'content_id']);

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        $records = $DB->get_records_sql("
            SELECT lc.id, e.data
            FROM {perform_element_linked_review_content} lc
            JOIN {perform_section_element} se on se.id = lc.section_element_id
            JOIN {perform_element} e on e.id = se.element_id
        ");

        foreach ($records as $record) {
            $decoded_content = json_decode($record->data, true);
            if (!empty($decoded_content['content_type'])) {
                $DB->set_field(
                    'perform_element_linked_review_content',
                    'content_type',
                    $decoded_content['content_type'],
                    ['id' => $record->id]
                );
            }
        }

        upgrade_plugin_savepoint(true, 2021031502, 'performelement', 'linked_review');
    }

    return true;
}
