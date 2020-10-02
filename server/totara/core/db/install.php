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
    global $CFG, $DB, $SITE;
    require_once(__DIR__ . '/upgradelib.php');

    $dbman = $DB->get_manager(); // loads ddl manager and xmldb classes

    totara_core_upgrade_create_relationship('totara_core\relationship\resolvers\subject', 'subject', 1);

    // Installing default topic collection, as this is required to be done before any other component/plugin
    // being installed. If it is not being installed first, then by the time plugin get to installed, it will not be
    // able to find any topic collection.
    require_once("{$CFG->dirroot}/totara/topic/db/upgradelib.php");
    totara_topic_add_tag_collection();

    // Installing default hashtag collection.
    totara_core_add_hashtag_tag_collection();

    return true;
}
