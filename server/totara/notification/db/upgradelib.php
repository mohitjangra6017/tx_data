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

use totara_core\extended_context;
use totara_notification\factory\built_in_notification_factory;
use totara_notification\notification\built_in_notification;

/**
 * A helper function to sync up any new built-in notification that are introduced within a component
 * in a system context.
 *
 * This function will try to invoke several APIs from the production code, which it should be discourage.
 * However, since its all encapsulated in this very function, hence when those APIs are deprecated/upgraded,
 * then this function can be tweaked to reflect the changes.
 *
 * Use this function in your upgrade step, when you are introducing a new built-in notification and want to
 * sync up with the database.
 *
 * Here are the list of static function that we are trying to invoke:
 * + @see built_in_notification::get_resolver_class_name()
 *
 * Note: PLEASE DO NOT DELETE THIS FUNCTION EVEN WHEN IT IS NOT USED IN upgrade.php FILE !!!
 *
 * @param string|null $component If this is null, then we are sync all the built-in notifications within the system.
 * @return void
 */
function totara_notification_sync_built_in_notification(?string $component = null): void {
    global $DB;

    // At this point, the context system should had been created.
    $context_system = context_system::instance();
    $notification_classes = built_in_notification_factory::get_notification_classes($component);

    if (empty($notification_classes)) {
        return;
    }

    foreach ($notification_classes as $notification_class) {
        $resolver_class_name = call_user_func([$notification_class, 'get_resolver_class_name']);
        $search_params = [
            'context_id' => $context_system->id,
            'resolver_class_name' => $resolver_class_name,
            'notification_class_name' => $notification_class,
        ];

        if ($DB->record_exists('notification_preference', $search_params)) {
            // Skip the records that are already existing in the system.
            continue;
        }

        $record = new stdClass();
        $record->resolver_class_name = $resolver_class_name;
        $record->context_id = $context_system->id;
        $record->component = extended_context::NATURAL_CONTEXT_COMPONENT;
        $record->area = extended_context::NATURAL_CONTEXT_AREA;
        $record->item_id = extended_context::NATURAL_CONTEXT_ITEM_ID;
        $record->notification_class_name = $notification_class;
        $record->time_created = time();

        $DB->insert_record('notification_preference', $record);
    }
}

/**
 * @param string $table
 * @return void
 */
function totara_notification_add_extend_context_fields(string $table): void {
    global $DB;

    $db_manager = $DB->get_manager();

    $table = new xmldb_table($table);
    $component_field = new xmldb_field('component', XMLDB_TYPE_CHAR, '255', null, false,'', '');
    $area_field = new xmldb_field('area', XMLDB_TYPE_CHAR, '255', null, false, null, '');
    $item_id_field = new xmldb_field('item_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0);

    if (!$db_manager->field_exists($table, $component_field)) {
        $db_manager->add_field($table, $component_field);
    }

    if (!$db_manager->field_exists($table, $area_field)) {
        $db_manager->add_field($table, $area_field);
    }

    if (!$db_manager->field_exists($table, $item_id_field)) {
        $db_manager->add_field($table, $item_id_field);
    }
}