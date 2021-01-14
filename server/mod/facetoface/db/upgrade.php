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
 * @package mod_facetoface
 */

// This file keeps track of upgrades to
// the facetoface module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

/**
 * Local database upgrade script
 *
 * @param   int $oldversion Current (pre-upgrade) local db version timestamp
 * @return  boolean always true
 */
function xmldb_facetoface_upgrade($oldversion) {
    global $CFG, $DB;
    require_once(__DIR__ . '/upgradelib.php');

    $dbman = $DB->get_manager();

    // Totara 13.0 release line.

    if ($oldversion < 2020113000) {
        // Fixed the orphaned records with statuscode 50 as we deprecated "Approved" status.
        facetoface_upgradelib_approval_to_declined_status();

        // Facetoface savepoint reached.
        upgrade_mod_savepoint(true, 2020113000, 'facetoface');
    }

    // Virtual room updates.
    if ($oldversion < 2020122100) {
        // Create the room dates virtual meeting table to link room dates to virtual meetings.
        $table = new xmldb_table('facetoface_room_dates_virtualmeeting');
        // Fields.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        $table->add_field('status', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('roomdateid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        $table->add_field('virtualmeetingid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        // Keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('roomdatevm_date_fk', XMLDB_KEY_FOREIGN, array('roomdateid'), 'facetoface_room_dates', array('id'));
        $table->add_key('roomdatevm_meet_fk', XMLDB_KEY_FOREIGN, array('virtualmeetingid'), 'virtualmeeting', array('id'));

        // Add the meetingid field to room dates.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Create the room virtual meeting table, to link a room with a wirtual meeting plugin type.
        $table = new xmldb_table('facetoface_room_virtualmeeting');
        // Fields.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        $table->add_field('status', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('roomid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        $table->add_field('plugin', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL);
        $table->add_field('options', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        // Keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('roomvm_room_fk', XMLDB_KEY_FOREIGN, array('roomid'), 'facetoface_room', array('id'));
        $table->add_key('roomvm_user_fk', XMLDB_KEY_FOREIGN, array('userid'), 'user', array('id'));
        // Indexs.
        $table->add_index('roomvm_plugin', XMLDB_INDEX_NOTUNIQUE, array('plugin'));

        // Add the meetingid field to room dates.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Create the default notification template for virtualmeetingfailure.
        facetoface_upgradelib_add_new_template(
            'virtualmeetingfailure',
            get_string('setting:defaultvirtualmeetingfailuresubjectdefault', 'facetoface'),
            get_string('setting:defaultvirtualmeetingfailuremessagedefault', 'facetoface'),
            1 << 25 // MDL_F2F_CONDITION_VIRTUALMEETING_CREATION_FAILURE
        );

        // Facetoface savepoint reached.
        upgrade_mod_savepoint(true, 2020122100, 'facetoface');
    }

    if ($oldversion < 2021011800) {
        // Fixed the orphaned url records left after room changed from 'Internal' to 'MS teams'.
        facetoface_upgradelib_clear_room_url();

        // Facetoface savepoint reached.
        upgrade_mod_savepoint(true, 2021011800, 'facetoface');
    }

    return true;
}
