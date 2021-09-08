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

use totara_program\totara_notification\notification\assigned_for_managers;
use totara_program\totara_notification\notification\assigned_for_subject;
use totara_program\totara_notification\notification\completed_for_managers;
use totara_program\totara_notification\notification\completed_for_subject;
use totara_program\totara_notification\notification\course_set_completed_for_managers;
use totara_program\totara_notification\notification\course_set_completed_for_subject;
use totara_program\totara_notification\notification\new_exception_for_site_admin;
use totara_program\totara_notification\notification\unassigned_for_managers;
use totara_program\totara_notification\notification\unassigned_for_subject;
use totara_program\totara_notification\resolver\assigned;
use totara_program\totara_notification\resolver\completed;
use totara_program\totara_notification\resolver\course_set_completed;
use totara_program\totara_notification\resolver\course_set_due_date;
use totara_program\totara_notification\resolver\due_date;
use totara_program\totara_notification\resolver\new_exception;
use totara_program\totara_notification\resolver\unassigned;

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

    if ($oldversion < 2021041100) {
        totara_program_upgrade_migrate_messages(
            assigned::class,
            [MESSAGETYPE_ENROLMENT => false],
            true,
            'alert',
            'totara_message',
            [assigned_for_managers::class, assigned_for_subject::class]
        );

        totara_program_upgrade_migrate_messages(
            unassigned::class,
            [MESSAGETYPE_UNENROLMENT => false],
            true,
            'alert',
            'totara_message',
            [unassigned_for_managers::class, unassigned_for_subject::class]
        );

        totara_program_upgrade_migrate_messages(
            due_date::class,
            [MESSAGETYPE_PROGRAM_DUE => true, MESSAGETYPE_PROGRAM_OVERDUE => false],
            true,
            'alert',
            'totara_message',
            []
        );

        totara_program_upgrade_migrate_messages(
            completed::class,
            [MESSAGETYPE_PROGRAM_COMPLETED => false, MESSAGETYPE_LEARNER_FOLLOWUP => false],
            true,
            'alert',
            'totara_message',
            [completed_for_managers::class, completed_for_subject::class]
        );

        totara_program_upgrade_migrate_messages(
            course_set_due_date::class,
            [MESSAGETYPE_COURSESET_DUE => true, MESSAGETYPE_COURSESET_OVERDUE => false],
            true,
            'alert',
            'totara_message',
            []
        );

        totara_program_upgrade_migrate_messages(
            course_set_completed::class,
            [MESSAGETYPE_COURSESET_COMPLETED => false],
            true,
            'alert',
            'totara_message',
            [course_set_completed_for_managers::class, course_set_completed_for_subject::class]
        );

        totara_program_upgrade_migrate_messages(
            new_exception::class,
            [MESSAGETYPE_EXCEPTION_REPORT => false],
            true,
            'alert',
            'totara_message',
            [new_exception_for_site_admin::class]
        );

        // Savepoint reached.
        upgrade_plugin_savepoint(true, 2021041100, 'totara', 'program');
    }

    if ($oldversion < 2021091500) {
        $table = new xmldb_table('prog_assignment');

        // Define field completionoffsetamount to be added to prog_assignment.
        $field = new xmldb_field('completionoffsetamount', XMLDB_TYPE_INTEGER, '4', null, null, null, null, 'completiontime');

        // Conditionally launch add field completionoffsetamount.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field completionoffsetunit to be added to prog_assignment.
        $field = new xmldb_field('completionoffsetunit', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'completionoffsetamount');

        // Conditionally launch add field completionoffsetunit.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Changing nullability and the default of field completiontime on table prog_assignment.
        $field = new xmldb_field('completiontime', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'includechildren');

        // Launch change of nullability for field completiontime.
        $dbman->change_field_notnull($table, $field);

        // Launch change of default for field completiontime.
        $dbman->change_field_default($table, $field);

        // Migrate data to the new column.
        totara_program_upgrade_migrate_relative_dates_data();

        // Program savepoint reached.
        upgrade_plugin_savepoint(true, 2021091500, 'totara', 'program');
    }

    return true;
}
