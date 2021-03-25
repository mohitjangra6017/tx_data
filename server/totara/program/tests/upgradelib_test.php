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
 * @author Nathan Lewis <nathan.lewis@totaralearning.com>
 * @package totara_notification
 */
global $CFG;

use totara_core\extended_context;
use totara_program\testing\generator;
use totara_program\totara_notification\recipient\manager;
use totara_program\totara_notification\recipient\subject;

require_once("{$CFG->dirroot}/totara/program/db/upgradelib.php");

/**
 * @group totara_notification
 */
class totara_program_upgradelib_testcase extends advanced_testcase {

    public function test_totara_program_upgrade_migrate_message(): void {
        global $DB;

        $program_generator = generator::instance();
        $prog1 = $program_generator->create_program();
        $prog2 = $program_generator->create_program();
        $cert1 = $program_generator->create_certification();

        $resolver_class_name = 'test_resolver';

        $old_messages = [
            [
                'programid' => $prog1->id,
                'messagetype' => 123,
                'sortorder' => 1,
                'messagesubject' => 'Subject message 1 subject %programfullname%',
                'mainmessage' => 'Subject 1 main message %username%',
                'notifymanager' => 1,
                'managersubject' => 'Manager message 1 subject %programfullname%',
                'managermessage' => 'Manager 1 main message %username%',
                'triggertime' => 0,
            ],
            [
                'programid' => $prog1->id,
                'messagetype' => 123,
                'sortorder' => 2,
                'messagesubject' => 'Subject message 2 subject %programfullname%',
                'mainmessage' => 'Subject 2 main message %username%',
                'notifymanager' => 0,
                'managersubject' => '',
                'managermessage' => '',
                'triggertime' => 1000,
            ],
            [
                'programid' => $prog2->id,
                'messagetype' => 123,
                'sortorder' => 1,
                'messagesubject' => 'Subject message 3 subject %programfullname%',
                'mainmessage' => 'Subject 3 main message %username%',
                'notifymanager' => 0,
                'managersubject' => '',
                'managermessage' => '',
                'triggertime' => 1000,
            ],
            [
                'programid' => $prog2->id,
                'messagetype' => 999,
                'sortorder' => 1,
                'messagesubject' => 'Subject message 2 subject %programfullname%',
                'mainmessage' => 'Subject 1 main message %username%',
                'notifymanager' => 0,
                'managersubject' => '',
                'managermessage' => '',
                'triggertime' => 1000,
            ],
            [
                'programid' => $cert1->id,
                'messagetype' => 123,
                'sortorder' => 1,
                'messagesubject' => 'Cert subject',
                'mainmessage' => 'Cert body',
                'notifymanager' => 0,
                'managersubject' => '',
                'managermessage' => '',
                'triggertime' => 1000,
            ],
        ];

        $DB->insert_records('prog_message', $old_messages);

        totara_program_upgrade_migrate_message_instances(
            123,
            true,
            true,
            $resolver_class_name
        );

        // Check new subject messages (3) exist.
        self::assertEquals(3, $DB->count_records('notification_preference',[
            'resolver_class_name' => $resolver_class_name,
            'recipient' => subject::class,
        ]));
        $subject_notification_preference = $DB->get_record('notification_preference', [
            'resolver_class_name' => $resolver_class_name,
            'recipient' => subject::class,
            'context_id' => context_program::instance($prog2->id)->id
        ]);
        self::assertEquals(context_program::instance($prog2->id)->id, $subject_notification_preference->context_id);
        self::assertEquals('totara_program', $subject_notification_preference->component);
        self::assertEquals('program', $subject_notification_preference->area);
        self::assertEquals($prog2->id, $subject_notification_preference->item_id);
        self::assertEquals(1, $subject_notification_preference->enabled);
        self::assertEquals('Subject message 3 subject [program:full_name]', $subject_notification_preference->title);
        self::assertEquals('Subject message 3 subject [program:full_name]', $subject_notification_preference->subject);
        self::assertEquals(FORMAT_PLAIN, $subject_notification_preference->subject_format);
        self::assertEquals('Subject 3 main message [subject:username]', $subject_notification_preference->body);
        self::assertEquals(FORMAT_PLAIN, $subject_notification_preference->body_format);
        self::assertEquals(-1000, $subject_notification_preference->schedule_offset);
        self::assertEquals('[]', $subject_notification_preference->forced_delivery_channels);

        // Check new manager message (1) exists.
        $manager_notification_preferences = $DB->get_records('notification_preference',[
            'resolver_class_name' => $resolver_class_name,
            'recipient' => manager::class,
        ]);
        self::assertCount(1, $manager_notification_preferences);
        $manager_notification_preference = reset($manager_notification_preferences);
        self::assertEquals(context_program::instance($prog1->id)->id, $manager_notification_preference->context_id);
        self::assertEquals('totara_program', $manager_notification_preference->component);
        self::assertEquals('program', $manager_notification_preference->area);
        self::assertEquals($prog1->id, $manager_notification_preference->item_id);
        self::assertEquals(1, $manager_notification_preference->enabled);
        self::assertEquals('Manager message 1 subject [program:full_name]', $manager_notification_preference->title);
        self::assertEquals('Manager message 1 subject [program:full_name]', $manager_notification_preference->subject);
        self::assertEquals(FORMAT_PLAIN, $manager_notification_preference->subject_format);
        self::assertEquals('Manager 1 main message [subject:username]', $manager_notification_preference->body);
        self::assertEquals(FORMAT_PLAIN, $manager_notification_preference->body_format);
        self::assertEquals(0, $manager_notification_preference->schedule_offset);
        self::assertEquals('[]', $manager_notification_preference->forced_delivery_channels);

        // Check old message is gone.
        self::assertEquals(0, $DB->count_records('prog_message', [
            'messagetype' => 123,
            'programid' => $prog1->id,
        ]));

        // Control old message is not touched.
        self::assertEquals(1, $DB->count_records('prog_message', ['messagetype' => 999]));

        // Control cert old message is not touched.
        self::assertEquals(1, $DB->count_records('prog_message', [
            'messagetype' => 123,
            'programid' => $cert1->id,
        ]));

        // Check that schedule after works, as well as for a cert.
        totara_program_upgrade_migrate_message_instances(
            123,
            false,
            false,
            'other_resolver_name'
        );
        $after_notification_preferences = $DB->get_records('notification_preference',[
            'resolver_class_name' => 'other_resolver_name',
            'recipient' => subject::class,
        ]);
        self::assertCount(1, $after_notification_preferences);
        $after_notification_preference = reset($after_notification_preferences);
        self::assertEquals(1000, $after_notification_preference->schedule_offset);
    }

    public function test_totara_program_upgrade_disable_notification_instances(): void {
        global $DB;

        $initial_notif_pref_count = $DB->count_records('notification_preference');

        $program_generator = generator::instance();
        $prog1 = $program_generator->create_program();
        $prog2 = $program_generator->create_program();

        $resolver_class_name = "test_resolver_class_name";
        $notification_class_name = "test_notification_class_name";

        // Target notification preference in system context.
        $record = new stdClass();
        $record->resolver_class_name = $resolver_class_name;
        $record->context_id = context_system::instance()->id;
        $record->component = extended_context::NATURAL_CONTEXT_COMPONENT;
        $record->area = extended_context::NATURAL_CONTEXT_AREA;
        $record->item_id = extended_context::NATURAL_CONTEXT_ITEM_ID;
        $record->notification_class_name = $notification_class_name;
        $record->time_created = time();
        $target_notification_preference_id = $DB->insert_record('notification_preference', $record);

        // Control notification preference in system context.
        $record = new stdClass();
        $record->resolver_class_name = $resolver_class_name;
        $record->context_id = context_system::instance()->id;
        $record->component = extended_context::NATURAL_CONTEXT_COMPONENT;
        $record->area = extended_context::NATURAL_CONTEXT_AREA;
        $record->item_id = extended_context::NATURAL_CONTEXT_ITEM_ID;
        $record->notification_class_name = $notification_class_name;
        $record->time_created = time();
        $control_notification_preference_id = $DB->insert_record('notification_preference', $record);

        // Do it.
        totara_program_upgrade_disable_notification_instances(
            $target_notification_preference_id,
            true
        );

        // Two manually created in system context and two created by the upgrade function.
        self::assertEquals($initial_notif_pref_count + 4, $DB->count_records('notification_preference'));

        // All existing progs off.
        self::assertEquals(2, $DB->count_records('notification_preference', [
            'ancestor_id' => $target_notification_preference_id,
        ]));
        self::assertEquals(1, $DB->count_records('notification_preference', [
            'ancestor_id' => $target_notification_preference_id,
            'resolver_class_name' => $resolver_class_name,
            'notification_class_name' => $notification_class_name,
            'context_id' => context_program::instance($prog1->id)->id,
            'component' => 'totara_program',
            'area' => 'program',
            'item_id' => $prog1->id,
            'enabled' => 0,
        ]));
        self::assertEquals(1, $DB->count_records('notification_preference', [
            'ancestor_id' => $target_notification_preference_id,
            'resolver_class_name' => $resolver_class_name,
            'notification_class_name' => $notification_class_name,
            'context_id' => context_program::instance($prog2->id)->id,
            'component' => 'totara_program',
            'area' => 'program',
            'item_id' => $prog2->id,
            'enabled' => 0,
        ]));

        // Non-related message in prog on.
        self::assertEquals(0, $DB->count_records('notification_preference', [
            'ancestor_id' => $control_notification_preference_id,
        ]));
    }

    /**
     * Test that the placeholder converter is working.
     */
    public function test_totara_program_upgrade_convert_placeholders(): void {
        $source = 'Some text %invalidplaceholder% %programfullname% %programfullname% %username% whatever';
        $expected = 'Some text %invalidplaceholder% [certification:full_name] [certification:full_name] [subject:username] whatever';

        $result = totara_program_upgrade_convert_placeholders($source, false);

        self::assertEquals($expected, $result);
    }
}