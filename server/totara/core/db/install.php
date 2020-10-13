<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
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
 * @author Ciaran Irvine <ciaran.irvine@totaralms.com>
 * @package totara
 * @subpackage totara_core
 */

function xmldb_totara_core_install() {
    global $DB;

    // NOTE: this file is now deprecated, please consider using lib/db/install.php instead.

    // TODO: TL-28175 find out why the following code cannot be moved to lib/db/install.php

    // Installing default topic collection, as this is required to be done before any other component/plugin
    // being installed. If it is not being installed first, then by the time plugin get to installed, it will not be
    // able to find any topic collection.
    $record = new stdClass();
    $record->name = get_string('pluginname', 'totara_topic');
    $record->isdefault = 1;
    $record->component = 'totara_topic';
    $record->sortorder = 1 + (int)$DB->get_field_sql('SELECT MAX(sortorder) FROM "ttr_tag_coll"');
    $record->searchable = 1;
    set_config('topic_collection_id', $DB->insert_record('tag_coll', $record));
    // Installing default hashtag collection.
    $record = new stdClass();
    $record->name = get_string('hashtag', 'totara_core');
    $record->isdefault = 1;
    $record->component = 'totara_core';
    $record->sortorder = 1 + (int)$DB->get_field_sql('SELECT MAX(sortorder) FROM "ttr_tag_coll"');
    $record->searchable = 1;
    set_config('hashtag_collection_id', $DB->insert_record('tag_coll', $record));

    return true;
}
