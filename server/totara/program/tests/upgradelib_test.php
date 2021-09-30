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

use totara_core\extended_context;
use totara_program\testing\generator;
use totara_program\totara_notification\recipient\manager;
use totara_program\totara_notification\recipient\subject;
use core_phpunit\testcase;
use totara_program\utils;

/**
 * @group totara_notification
 */
class totara_program_upgradelib_testcase extends testcase {
    /**
     * @return void
     */
    protected function setUp(): void {
        global $CFG;

        require_once("{$CFG->dirroot}/totara/program/db/upgradelib.php");
        require_once("{$CFG->dirroot}/totara/notification/db/upgradelib.php");
    }

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

        self::assertEquals(0, $DB->count_records('files', [
            'filearea' => 'program_legacy_message_backup',
            'mimetype' => 'application/json',
        ]));
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

        // Check that the backup function was called.
        self::assertEquals(3, $DB->count_records('files', [
            'filearea' => 'program_legacy_message_backup',
            'mimetype' => 'application/json',
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

    public function test_totara_program_upgrade_backup_message(): void {
        global $DB;

        $program_generator = generator::instance();
        $program = $program_generator->create_program();
        $cert = $program_generator->create_certification();
        $old_program_message = [
            'programid' => (string)$program->id,
            'messagetype' => '123',
            'sortorder' => '1',
            'messagesubject' => 'Subject message 1 subject %programfullname%',
            'mainmessage' => 'Subject 1 main message %username%',
            'notifymanager' => '1',
            'managersubject' => 'Manager message 1 subject %programfullname%',
            'managermessage' => 'Manager 1 main message %username%',
            'triggertime' => '0',
        ];
        $old_program_message_id = $DB->insert_record('prog_message', (object)$old_program_message);

        $old_cert_message = [
            'programid' => (string)$cert->id,
            'messagetype' => '123',
            'sortorder' => '1',
            'messagesubject' => 'Cert subject',
            'mainmessage' => 'Cert body',
            'notifymanager' => '0',
            'managersubject' => '',
            'managermessage' => '',
            'triggertime' => '1000',
        ];
        $old_cert_message_id = $DB->insert_record('prog_message', (object)$old_cert_message);

        self::assertFalse($DB->record_exists('files', ['filearea' => 'program_legacy_message_backup']));

        totara_program_upgrade_backup_message($old_program_message_id, true);
        self::assertEquals(1, $DB->count_records('files', [
            'filearea' => 'program_legacy_message_backup',
            'mimetype' => 'application/json',
        ]));
        $this->assert_backup_file($program->id, $old_program_message_id, $old_program_message);

        totara_program_upgrade_backup_message($old_cert_message_id, false);
        self::assertEquals(2, $DB->count_records('files', [
            'filearea' => 'program_legacy_message_backup',
            'mimetype' => 'application/json',
        ]));
        $this->assert_backup_file($cert->id, $old_cert_message_id, $old_cert_message, false);
    }

    private function assert_backup_file(int $program_id, int $message_id, array $expected_content, bool $is_program = true): void {
        $fs = get_file_storage();
        $program_context = context_program::instance($program_id);
        $file_info = [
            'contextid' => $program_context->id,
            'component' => $is_program ? 'totara_program' : 'totara_certification',
            'filearea' => 'program_legacy_message_backup',
            'itemid' => $message_id,
            'filepath' => '/',
            'filename' => 'program_legacy_message_backup_' . $message_id . '.json',
        ];

        $file = $fs->get_file(...array_values($file_info));
        $json_decoded = json_decode($file->get_content(), true);

        // Add id to the expected record.
        $expected_content = array_merge(['id' => (string)$message_id], $expected_content);
        self::assertEqualsCanonicalizing($expected_content, $json_decoded);
    }

    public function test_totara_program_upgrade_migrate_relative_dates_data(): void {
        global $DB;

        $program_generator = generator::instance();
        $pid = $program_generator->create_program()->id;
        $cid = $program_generator->create_certification()->id;

        $records = [
            ['programid' => $pid, 'assignmenttype' => ASSIGNTYPE_COHORT, 'assignmenttypeid' => 1, 'completiontime' => 1632913200, 'completionevent' => COMPLETION_EVENT_NONE],
            ['programid' => $cid, 'assignmenttype' => ASSIGNTYPE_COHORT, 'assignmenttypeid' => 1, 'completiontime' => DAYSECS * 7 * 5, 'completionevent' => COMPLETION_EVENT_FIRST_LOGIN],
            ['programid' => $pid, 'assignmenttype' => ASSIGNTYPE_INDIVIDUAL, 'assignmenttypeid' => 5, 'completiontime' => 1632913200, 'completionevent' => COMPLETION_EVENT_NONE],
            ['programid' => $cid, 'assignmenttype' => ASSIGNTYPE_INDIVIDUAL, 'assignmenttypeid' => 5, 'completiontime' => DAYSECS * 25, 'completionevent' => COMPLETION_EVENT_PROGRAM_COMPLETION],
            ['programid' => $pid, 'assignmenttype' => ASSIGNTYPE_ORGANISATION, 'assignmenttypeid' => 2, 'completiontime' => 1632913200, 'completionevent' => COMPLETION_EVENT_NONE],
            ['programid' => $cid, 'assignmenttype' => ASSIGNTYPE_ORGANISATION, 'assignmenttypeid' => 2, 'completiontime' => DAYSECS * 10, 'completionevent' => COMPLETION_EVENT_COURSE_COMPLETION],
            ['programid' => $pid, 'assignmenttype' => ASSIGNTYPE_POSITION, 'assignmenttypeid' => 4, 'completiontime' => 1632913200, 'completionevent' => COMPLETION_EVENT_NONE],
            ['programid' => $cid, 'assignmenttype' => ASSIGNTYPE_POSITION, 'assignmenttypeid' => 4, 'completiontime' => DAYSECS * 30 * 4, 'completionevent' => COMPLETION_EVENT_POSITION_START_DATE],
            ['programid' => $pid, 'assignmenttype' => ASSIGNTYPE_MANAGERJA, 'assignmenttypeid' => 3, 'completiontime' => 1632913200, 'completionevent' => COMPLETION_EVENT_NONE],
            ['programid' => $cid, 'assignmenttype' => ASSIGNTYPE_MANAGERJA, 'assignmenttypeid' => 3, 'completiontime' => DAYSECS * 365 * 2, 'completionevent' => COMPLETION_EVENT_ENROLLMENT_DATE],
            ['programid' => $pid, 'assignmenttype' => ASSIGNTYPE_INDIVIDUAL, 'assignmenttypeid' => 3, 'completiontime' => 0, 'completionevent' => COMPLETION_EVENT_FIRST_LOGIN],
        ];

        $DB->insert_records('prog_assignment', $records);

        self::assertCount(11, $DB->get_records('prog_assignment'));
        self::assertEmpty($DB->get_records_select('prog_assignment', 'completionoffsetamount IS NOT NULL AND completionoffsetunit IS NOT NULL'));

        totara_program_upgrade_migrate_relative_dates_data();

        self::assertCount(11, $DB->get_records('prog_assignment'));
        self::assertCount(6, $DB->get_records_select('prog_assignment', 'completionoffsetamount IS NOT NULL AND completionoffsetunit IS NOT NULL'));
        self::assertCount(5, $DB->get_records_select('prog_assignment', 'completiontime IS NOT NULL'));

        $conditions1 = [
            'programid'        => $cid,
            'assignmenttype'   => ASSIGNTYPE_COHORT,
            'assignmenttypeid' => 1,
            'completionevent'  => COMPLETION_EVENT_FIRST_LOGIN,
        ];
        self::assertEquals(5, $DB->get_field('prog_assignment', 'completionoffsetamount', $conditions1));
        self::assertEquals(utils::TIME_SELECTOR_WEEKS, $DB->get_field('prog_assignment', 'completionoffsetunit', $conditions1));
        self::assertEmpty($DB->get_field('prog_assignment', 'completiontime', $conditions1));

        $conditions2 = [
            'programid'        => $cid,
            'assignmenttype'   => ASSIGNTYPE_INDIVIDUAL,
            'assignmenttypeid' => 5,
            'completionevent'  => COMPLETION_EVENT_PROGRAM_COMPLETION,
        ];
        self::assertEquals(25, $DB->get_field('prog_assignment', 'completionoffsetamount', $conditions2));
        self::assertEquals(utils::TIME_SELECTOR_DAYS, $DB->get_field('prog_assignment', 'completionoffsetunit', $conditions2));
        self::assertEmpty($DB->get_field('prog_assignment', 'completiontime', $conditions2));

        $conditions3 = [
            'programid'        => $cid,
            'assignmenttype'   => ASSIGNTYPE_ORGANISATION,
            'assignmenttypeid' => 2,
            'completionevent'  => COMPLETION_EVENT_COURSE_COMPLETION,
        ];
        self::assertEquals(10, $DB->get_field('prog_assignment', 'completionoffsetamount', $conditions3));
        self::assertEquals(utils::TIME_SELECTOR_DAYS, $DB->get_field('prog_assignment', 'completionoffsetunit', $conditions3));
        self::assertEmpty($DB->get_field('prog_assignment', 'completiontime', $conditions3));

        $conditions4 = [
            'programid'        => $cid,
            'assignmenttype'   => ASSIGNTYPE_POSITION,
            'assignmenttypeid' => 4,
            'completionevent'  => COMPLETION_EVENT_POSITION_START_DATE,
        ];
        self::assertEquals(4, $DB->get_field('prog_assignment', 'completionoffsetamount', $conditions4));
        self::assertEquals(utils::TIME_SELECTOR_MONTHS, $DB->get_field('prog_assignment', 'completionoffsetunit', $conditions4));
        self::assertEmpty($DB->get_field('prog_assignment', 'completiontime', $conditions4));

        $conditions5 = [
            'programid'        => $cid,
            'assignmenttype'   => ASSIGNTYPE_MANAGERJA,
            'assignmenttypeid' => 3,
            'completionevent'  => COMPLETION_EVENT_ENROLLMENT_DATE,
        ];
        self::assertEquals(2, $DB->get_field('prog_assignment', 'completionoffsetamount', $conditions5));
        self::assertEquals(utils::TIME_SELECTOR_YEARS, $DB->get_field('prog_assignment', 'completionoffsetunit', $conditions5));
        self::assertEmpty($DB->get_field('prog_assignment', 'completiontime', $conditions5));

        $conditions6 = [
            'programid'        => $pid,
            'assignmenttype'   => ASSIGNTYPE_INDIVIDUAL,
            'assignmenttypeid' => 3,
            'completionevent'  => COMPLETION_EVENT_FIRST_LOGIN,
        ];
        self::assertEquals(0, $DB->get_field('prog_assignment', 'completionoffsetamount', $conditions6));
        self::assertEquals(utils::TIME_SELECTOR_DAYS, $DB->get_field('prog_assignment', 'completionoffsetunit', $conditions6));
        self::assertEmpty($DB->get_field('prog_assignment', 'completiontime', $conditions6));
    }
}
