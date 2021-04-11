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
 * @author  Nathan Lewis <nathan.lewis@totaralearning.com>
 * @package totara_program
 */

defined('MOODLE_INTERNAL') || die();

use totara_program\totara_notification\recipient\manager;
use totara_program\totara_notification\recipient\site_admin;
use totara_program\totara_notification\recipient\subject;

/**
 * Shortcut to do all the program and certification migration steps.
 *
 * @param string $resolver_class_name
 * @param bool[] $program_message_types_and_schedules where key is int $program_message_type and value is bool $schedule_is_before
 * @param bool $is_for_program
 * @param string $provider_name
 * @param string $provider_component
 * @param array $notification_class_names
 */
function totara_program_upgrade_migrate_messages(
    string $resolver_class_name,
    array $program_message_types_and_schedules,
    bool $is_for_program,
    string $provider_name,
    string $provider_component,
    array $notification_class_names
): void {
    global $DB;

    // Register all program built-in notifications.
    if ($is_for_program) {
        totara_notification_sync_built_in_notification('totara_program');
    } else {
        totara_notification_sync_built_in_notification('totara_certification');
    }

    foreach ($program_message_types_and_schedules as $program_message_type => $schedule_is_before) {
        totara_program_upgrade_migrate_message_instances(
            $program_message_type,
            $is_for_program,
            $schedule_is_before,
            $resolver_class_name
        );
    }

    totara_notification_migrate_notifiable_event_prefs(
        $resolver_class_name,
        $provider_name,
        $provider_component
    );

    // Go through built-in notifications.
    foreach ($notification_class_names as $notification_class_name) {
        $notification_preference_id = $DB->get_field('notification_preference', 'id',
            [
                'notification_class_name' => $notification_class_name,
                'context_id' => context_system::instance()->id,
                'component' => '',
                'area' => '',
                'item_id' => 0,
            ]
        );
        if ($notification_preference_id) {
            // Disable for all programs.
            totara_program_upgrade_disable_notification_instances(
                $notification_preference_id,
                $is_for_program
            );
            // Migrate preferences.
            totara_notification_migrate_notification_prefs(
                $notification_preference_id,
                $provider_name,
                $provider_component
            );
        }
    }
}

/**
 * Reads old program or certification messages of the given type and creates new custom messages based on the given resolver.
 *
 * - Placeholders are converted from old to new
 * - The user and manager messages are separated
 * - Only program OR certification instances are processed - call once for each type, potentially with different resolvers
 *
 * @param int $program_message_type
 * @param bool $is_for_program true for program, false for certification
 * @param bool $schedule_is_before true if the schedule is "X days before event", false if it is "on event" or "X days after event"
 * @param string $resolver_class_name
 */
function totara_program_upgrade_migrate_message_instances(
    int $program_message_type,
    bool $is_for_program,
    bool $schedule_is_before,
    string $resolver_class_name
): void {
    global $DB;

    $sql = "SELECT pm.*
              FROM {prog_message} pm
              JOIN {prog} prog ON prog.id = pm.programid
             WHERE pm.messagetype = :message_type";
    $sql .= $is_for_program ? " AND certifid IS NULL" : " AND certifid > 0";
    $messages = $DB->get_records_sql($sql, ['message_type' => $program_message_type]);

    foreach ($messages as $message) {
        $offset = $schedule_is_before ? -$message->triggertime : $message->triggertime;

        $recipient = ($program_message_type == MESSAGETYPE_EXCEPTION_REPORT)
            ? site_admin::class
            : subject::class;

        // Subject message.
        $notif_pref_override = [
            'resolver_class_name' => $resolver_class_name,
            'context_id' => context_program::instance($message->programid)->id,
            'component' => $is_for_program ? 'totara_program' : 'totara_certification',
            'area' => 'program',
            'item_id' => $message->programid,
            'enabled' => 1,
            'recipient' => $recipient,
            'title' => totara_program_upgrade_convert_placeholders($message->messagesubject, $is_for_program),
            'subject' => totara_program_upgrade_convert_placeholders($message->messagesubject, $is_for_program),
            'subject_format' => FORMAT_PLAIN,
            'body' => totara_program_upgrade_convert_placeholders($message->mainmessage, $is_for_program),
            'body_format' => FORMAT_PLAIN,
            'schedule_offset' => $offset,
            'forced_delivery_channels' => json_encode([]),
            'time_created' => time(),
        ];

        $DB->insert_record('notification_preference', $notif_pref_override);

        // Manager message.
        if ($message->notifymanager) {
            $notif_pref_override = [
                'resolver_class_name' => $resolver_class_name,
                'context_id' => context_program::instance($message->programid)->id,
                'component' => $is_for_program ? 'totara_program' : 'totara_certification',
                'area' => 'program',
                'item_id' => $message->programid,
                'enabled' => 1,
                'recipient' => manager::class,
                'title' => totara_program_upgrade_convert_placeholders($message->managersubject, $is_for_program),
                'subject' => totara_program_upgrade_convert_placeholders($message->managersubject, $is_for_program),
                'subject_format' => FORMAT_PLAIN,
                'body' => totara_program_upgrade_convert_placeholders($message->managermessage, $is_for_program),
                'body_format' => FORMAT_PLAIN,
                'schedule_offset' => $offset,
                'forced_delivery_channels' => json_encode([]),
                'time_created' => time(),
            ];

            $DB->insert_record('notification_preference', $notif_pref_override);
        }

        // Remove the old message.
        $DB->delete_records('prog_message', ['id' => $message->id]);
    }
}

/**
 * Disables the given notification in all existing programs or certificatins
 *
 * Note that this doesn't disabled the notification in the system context. The idea is that new programs will inherit
 * the system-context notification while existing programs will not, in order that they continue to use pre-existing
 * messages that have been migrated to custom notifications using totara_program_upgrade_migrate_message.
 *
 * @param int $notification_preference_id
 * @param bool $is_for_program true for program, false for certification
 */
function totara_program_upgrade_disable_notification_instances(
    int $notification_preference_id,
    bool $is_for_program
): void {
    global $DB;

    $notification_preference = $DB->get_record('notification_preference', ['id' => $notification_preference_id]);

    if (!empty($notification_preference->ancestor_id)) {
        throw new coding_exception(
            'Tried to disabled instances of a program notification which is not an ancestor'
        );
    }

    if (empty($notification_preference->notification_class_name)) {
        throw new coding_exception(
            'Tried to disabled instances of a program notification which is not built in'
        );
    }

    if ($notification_preference->context_id != context_system::instance()->id) {
        throw new coding_exception(
            'Tried to disabled instances of a program notification which is not defined in the system context'
        );
    }

    if ($is_for_program) {
        $progs = $DB->get_recordset_select('prog', "certifid IS NULL");
    } else {
        $progs = $DB->get_recordset_select('prog', "certifid > 0");
    }

    foreach ($progs as $prog) {
        $notif_pref_override = [
            'ancestor_id' => $notification_preference_id,
            'resolver_class_name' => $notification_preference->resolver_class_name,
            'notification_class_name' => $notification_preference->notification_class_name,
            'context_id' => context_program::instance($prog->id)->id,
            'component' => $is_for_program ? 'totara_program' : 'totara_certification',
            'area' => 'program',
            'item_id' => $prog->id,
            'enabled' => 0,
            'time_created' => time(),
        ];

        $DB->insert_record('notification_preference', $notif_pref_override);
    }

    $progs->close();
}

/**
 * Convert old message placeholders to new notifiable event placeholders in programs
 *
 * @param string $text
 * @return string
 * @param bool $is_for_program true for program, false for certification
 */
function totara_program_upgrade_convert_placeholders(string $text, bool $is_for_program): string {
    $prog_or_cert = $is_for_program ? 'program' : 'certification';
    $map = [
        '%programfullname%' => '[' . $prog_or_cert . ':full_name]',
        '%userfullname%' => '[subject:full_name]',
        '%username%' => '[subject:username]',
        '%completioncriteria%' => '[assignment:due_date_criteria]',
        '%duedate%' => '[assignment:due_date]',
        '%managername%' => '[managers:full_name]',
        '%manageremail%' => '[managers:email]',
        '%setlabel%' => '[course_set:label]',
    ];

    foreach ($map as $old_key => $new_key) {
        $text = str_replace($old_key, $new_key, $text);
    }

    return $text;
}