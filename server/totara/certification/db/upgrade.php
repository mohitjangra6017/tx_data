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
 * @author Jon Sharp <jon.sharp@catalyst-eu.net>
 * @package totara
 * @subpackage certification
 */

// Certification db upgrades.

use totara_certification\totara_notification\notification\assigned_for_managers;
use totara_certification\totara_notification\notification\assigned_for_subject;
use totara_certification\totara_notification\notification\completed_for_managers;
use totara_certification\totara_notification\notification\completed_for_subject;
use totara_certification\totara_notification\notification\course_set_completed_for_subject;
use totara_certification\totara_notification\notification\course_set_completed_for_managers;
use totara_certification\totara_notification\notification\failure_to_recertify_for_subject;
use totara_certification\totara_notification\notification\new_exception_for_site_admin;
use totara_certification\totara_notification\notification\unassigned_for_managers;
use totara_certification\totara_notification\notification\unassigned_for_subject;
use totara_certification\totara_notification\notification\window_open_date_for_subject;
use totara_certification\totara_notification\resolver\assigned;
use totara_certification\totara_notification\resolver\completed;
use totara_certification\totara_notification\resolver\course_set_completed;
use totara_certification\totara_notification\resolver\course_set_due_date;
use totara_certification\totara_notification\resolver\due_date;
use totara_certification\totara_notification\resolver\expiry_date;
use totara_certification\totara_notification\resolver\failure_to_recertify;
use totara_certification\totara_notification\resolver\new_exception;
use totara_certification\totara_notification\resolver\unassigned;
use totara_certification\totara_notification\resolver\window_open_date;

/**
 * Certification database upgrade script
 *
 * @param   integer $oldversion Current (pre-upgrade)
 * @return  boolean $result
 */
function xmldb_totara_certification_upgrade($oldversion) {
    global $CFG, $DB;

    require_once("{$CFG->dirroot}/totara/notification/db/upgradelib.php");
    require_once("{$CFG->dirroot}/totara/program/db/upgradelib.php");
    require_once("{$CFG->dirroot}/totara/program/program_messages.class.php");

    $dbman = $DB->get_manager();

    if ($oldversion < 2021041100) {
        totara_program_upgrade_migrate_messages(
            assigned::class,
            [MESSAGETYPE_ENROLMENT => false],
            false,
            'alert',
            'totara_message',
            [assigned_for_managers::class, assigned_for_subject::class]
        );

        totara_program_upgrade_migrate_messages(
            unassigned::class,
            [MESSAGETYPE_UNENROLMENT => false],
            false,
            'alert',
            'totara_message',
            [unassigned_for_managers::class, unassigned_for_subject::class]
        );

        totara_program_upgrade_migrate_messages(
            due_date::class,
            [MESSAGETYPE_PROGRAM_DUE => true, MESSAGETYPE_PROGRAM_OVERDUE => false],
            false,
            'alert',
            'totara_message',
            []
        );

        totara_program_upgrade_migrate_messages(
            completed::class,
            [MESSAGETYPE_PROGRAM_COMPLETED => false, MESSAGETYPE_LEARNER_FOLLOWUP => false],
            false,
            'alert',
            'totara_message',
            [completed_for_managers::class, completed_for_subject::class]
        );

        totara_program_upgrade_migrate_messages(
            course_set_due_date::class,
            [MESSAGETYPE_COURSESET_DUE => true, MESSAGETYPE_COURSESET_OVERDUE => false],
            false,
            'alert',
            'totara_message',
            []
        );

        totara_program_upgrade_migrate_messages(
            course_set_completed::class,
            [MESSAGETYPE_COURSESET_COMPLETED => false],
            false,
            'alert',
            'totara_message',
            [course_set_completed_for_managers::class, course_set_completed_for_subject::class]
        );

        totara_program_upgrade_migrate_messages(
            new_exception::class,
            [MESSAGETYPE_EXCEPTION_REPORT => false],
            false,
            'alert',
            'totara_message',
            [new_exception_for_site_admin::class]
        );

        totara_program_upgrade_migrate_messages(
            window_open_date::class,
            [MESSAGETYPE_RECERT_WINDOWOPEN => false],
            false,
            'alert',
            'totara_message',
            [window_open_date_for_subject::class]
        );

        totara_program_upgrade_migrate_messages(
            failure_to_recertify::class,
            [MESSAGETYPE_RECERT_FAILRECERT => false],
            false,
            'alert',
            'totara_message',
            [failure_to_recertify_for_subject::class]
        );

        totara_program_upgrade_migrate_messages(
            expiry_date::class,
            [MESSAGETYPE_RECERT_WINDOWDUECLOSE => true],
            false,
            'alert',
            'totara_message',
            []
        );

        // Savepoint reached.
        upgrade_plugin_savepoint(true, 2021041100, 'totara', 'certification');
    }

    if ($oldversion < 2021052501) {
        // Define index courcomphist_usrcou_ix (not unique) to be added to course_completion_history.
        $table = new xmldb_table('course_completion_history');
        $index = new xmldb_index('courcomphist_usrcou_ix', XMLDB_INDEX_NOTUNIQUE, array('userid', 'courseid'));

        // Conditionally launch add index courcomphist_usrcou_ix.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Certification savepoint reached.
        upgrade_plugin_savepoint(true, 2021052501, 'totara', 'certification');
    }

    return true;
}
