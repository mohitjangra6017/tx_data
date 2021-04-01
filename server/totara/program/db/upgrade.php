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
 * @subpackage program
 */

/**
 * Local database upgrade script
 *
 * @param   integer $oldversion Current (pre-upgrade) local db version timestamp
 * @return  boolean $result
 */
function xmldb_totara_program_upgrade($oldversion) {
    global $CFG, $DB;
    require_once("{$CFG->dirroot}/totara/notification/db/upgradelib.php");
    require_once("{$CFG->dirroot}/totara/program/db/upgradelib.php");
    require_once("{$CFG->dirroot}/totara/program/program_messages.class.php");

    $dbman = $DB->get_manager();

    if ($oldversion < 2021040100) {
        // Register all program built-in notifications.
        totara_notification_sync_built_in_notification('totara_program');

        // Savepoint reached.
        upgrade_plugin_savepoint(true, 2021040100, 'totara', 'program');
    }

    return true;
}
