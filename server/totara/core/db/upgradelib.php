<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2014 onwards Totara Learning Solutions LTD
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
 * @author Petr Skoda <petr.skoda@totaralms.com>
 * @package totara
 * @subpackage totara_core
 */




/**
 * Create a relationship and corresponding relationship resolver record for a relationship class.
 *
 * Please note that if you refactor/move a relationship resolver class, you will need to
 * update all corresponding relationship resolver table rows that use that class_name!
 *
 * @param string|array $resolver_classes
 * @param string $idnumber Unique identifier.
 * @param int $sort_order
 * @param int $type Optional type identifier - defaults to 0.
 * @param string $component Plugin that the relationship is exclusive to. Defaults to being available for all.
 *
 * @since Totara 13.0
 */
function totara_core_upgrade_create_relationship($resolver_classes, $idnumber = null, $sort_order = 1, $type = 0, $component = null) {
    global $DB;

    $resolver_classes = is_array($resolver_classes)
        ? $resolver_classes
        : [$resolver_classes];

    // Checks if idnumber already exists, then updates the relationship.
    if ($idnumber) {
        $sql = "idnumber = :idnumber OR idnumber = :resolver_class";
        $params = ['idnumber' => $idnumber, 'resolver_class' => $resolver_classes[0]];
        $relationship = $DB->get_record_select(
            'totara_core_relationship',
            $sql,
            $params
        );

        // Update the sort order, type & component if the relationship already exists.
        if ($relationship) {
            $relationship->idnumber = $idnumber;
            // Conditionally add properties if they exist as a db column.
            if (isset($relationship->sort_order)) {
                $relationship->sort_order = $sort_order;
            }
            if (isset($relationship->type)) {
                $relationship->type = $type;
            }
            if (isset($relationship->component)) {
                $relationship->component = $component;
            }
            totara_core_update_relationship($relationship, $resolver_classes);
            return;
        }
    }

    if (!$idnumber) {
        $idnumber = $resolver_classes[0];
    }
    // Creates the new relationship with the resolver classes.
    totara_core_create_relationship($resolver_classes, $idnumber, $sort_order, $type, $component);
}

/**
 * Creates a totara relationship with the resolvers.
 *
 * @param array $resolver_classes
 * @param string $idnumber
 * @param int $sort_order
 * @param int $type
 * @param string|null $component
 */
function totara_core_create_relationship(array $resolver_classes, string $idnumber, int $sort_order = 1, int $type = 0, string $component = null): void {
    global $DB;
    $DB->transaction(static function() use ($DB, $resolver_classes, $idnumber, $type, $component, $sort_order) {
        $relationship_id = $DB->insert_record(
            'totara_core_relationship',
            [
                'idnumber' => $idnumber ? $idnumber : $resolver_classes[0],
                'type' => $type,
                'component' => $component,
                'sort_order' => $sort_order,
                'created_at' => time(),
            ]
        );

        foreach ($resolver_classes as $resolver_class) {
            $DB->insert_record('totara_core_relationship_resolver', [
                'relationship_id' => $relationship_id,
                'class_name' => $resolver_class,
            ]);
        }
    });
}

/**
 * Updates a relationship's properties and resolvers.
 *
 * @param $relationship
 * @param array $resolvers
 */
function totara_core_update_relationship ($relationship, array $resolvers) {
    global $DB;

    $DB->update_record( 'totara_core_relationship', $relationship);
    $existing_resolvers = $DB->get_records(
        'totara_core_relationship_resolver',
        [
            'relationship_id' => $relationship->id
        ]
    );
    $resolver_classes = array_column($existing_resolvers, 'class_name');

    foreach ($resolvers as $resolver) {
        if (!in_array($resolver, $resolver_classes, true)) {
            $DB->insert_record(
                'totara_core_relationship_resolver',
                [
                    'relationship_id' => $relationship->id,
                    'class_name' => $resolver
                ]
            );
        }
    }
}

/**
 * Creating a tag collection record for hashtag. Then returning the id of the collection.
 * This function will also try to set the config for `hashtag_collection_id` which is `$CFG->hashtag_collection_id`
 *
 * @return int
 */
function totara_core_add_hashtag_tag_collection(): int {
    global $DB, $CFG;

    if (!empty($CFG->hashtag_collection_id)) {
        return $CFG->hashtag_collection_id;
    }

    $sql = 'SELECT MAX(sortorder) AS sortorder FROM "ttr_tag_coll"';
    $current_sort_order = $DB->get_field_sql($sql);

    $record = new stdClass();
    $record->name = get_string('hashtag', 'totara_core');
    $record->isdefault = 1;
    $record->component = 'totara_core';
    $record->sortorder = $current_sort_order + 1;
    $record->searchable = 1;

    $id = (int) $DB->insert_record('tag_coll', $record);

    set_config('hashtag_collection_id', $id);
    return $id;
}

/**
 * Uninstall plugins that were removed after Totara 14 branching.
 */
function totara_core_upgrade_delete_removed_plugins() {
    global $DB;

    // NOTE: this should match \core_plugin_manager::is_deleted_standard_plugin() data.

    $deleteplugins = array(
        'tool_premigration',
    );

    foreach ($deleteplugins as $deleteplugin) {
        list($plugintype, $pluginname) = explode('_', $deleteplugin, 2);
        $dir = core_component::get_plugin_directory($plugintype, $pluginname);
        if ($dir and file_exists("$dir/version.php")) {
            // This should not happen, this is not a standard distribution!
            continue;
        }
        if (!get_config($deleteplugin, 'version')) {
            // Not installed.
            continue;
        }
        uninstall_plugin($plugintype, $pluginname);
    }
}
