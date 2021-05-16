<?php
/**
 * This file is part of Totara LMS
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
 * @author  Simon Coggins <simon.coggins@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

use contentmarketplace_linkedin\workflow\core_course\coursecreate\contentmarketplace;
use contentmarketplace_linkedin\workflow\totara_contentmarketplace\exploremarketplace\linkedin;

defined('MOODLE_INTERNAL') || die;

/**
 * LinkedIn Learning Content Marketplace plugin upgrade.
 *
 * @param int $old_version the version we are upgrading from
 * @return bool always true
 */
function xmldb_contentmarketplace_linkedin_upgrade(int $old_version): bool {
    global $DB;
    $db_manager = $DB->get_manager();

    if ($old_version < 2021042800) {
        // Define table linkedin_learning_object to be created.
        $table = new xmldb_table('marketplace_linkedin_learning_object');

        // Adding fields to table linkedin_learning_object.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('urn', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('description_include_html', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('short_description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('locale_language', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('locale_country', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('last_updated_at', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('published_at', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('retired_at', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('level', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('primary_image_url', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('time_to_complete', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('web_launch_url', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('sso_launch_url', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table linkedin_learning_object.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table linkedin_learning_object.
        $table->add_index('urn_index', XMLDB_INDEX_UNIQUE, array('urn'));
        $table->add_index('last_updated_at_index', XMLDB_INDEX_NOTUNIQUE, array('last_updated_at'));
        $table->add_index('published_at', XMLDB_INDEX_NOTUNIQUE, array('published_at'));
        $table->add_index('title_index', XMLDB_INDEX_NOTUNIQUE, array('title'));

        // Conditionally launch create table for linkedin_learning_object.
        if (!$db_manager->table_exists($table)) {
            $db_manager->create_table($table);
        }
        // Linkedin savepoint reached.
        upgrade_plugin_savepoint(true, 2021042800, 'contentmarketplace', 'linkedin');
    }

    if ($old_version < 2021042801) {
        // Define field type to be added to marketplace_linkedin_learning_object.
        $table = new xmldb_table('marketplace_linkedin_learning_object');
        $field = new xmldb_field('asset_type', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'sso_launch_url');

        // Conditionally launch add field type.
        if (!$db_manager->field_exists($table, $field)) {
            $db_manager->add_field($table, $field);
        }

        // Update any default record.
        $DB->execute(
            'UPDATE "ttr_marketplace_linkedin_learning_object" SET asset_type = ?',
            ['COURSE']
        );

        $field->setNotNull(XMLDB_NOTNULL);
        $db_manager->change_field_notnull($table, $field);

        // Linkedin savepoint reached.
        upgrade_plugin_savepoint(true, 2021042801, 'contentmarketplace', 'linkedin');
    }

    if ($old_version < 2021042802) {
        $workflow = contentmarketplace::instance();
        $workflow->enable();
        $workflow = linkedin::instance();
        $workflow->enable();
        // Linkedin savepoint reached.
        upgrade_plugin_savepoint(true, 2021042802, 'contentmarketplace', 'linkedin');
    }

    return true;
}
