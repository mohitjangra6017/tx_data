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
 * @author Matthias Bonk <matthias.bonk@totaralearning.com>
 * @package totara_certification
 */

use core\json_editor\helper\document_helper;
use core\json_editor\node\paragraph;
use totara_certification\totara_notification\resolver\new_exception as new_exception_resolver;
use totara_core\extended_context;
use totara_notification\entity\notifiable_event_queue;
use totara_notification\entity\notification_queue;
use totara_notification\json_editor\node\placeholder;
use totara_notification\task\process_event_queue_task;
use totara_notification\task\process_notification_queue_task;
use totara_notification\testing\generator as notification_generator;
use totara_program\task\send_messages_task;
use totara_program\totara_notification\recipient\site_admin;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/totara_notification_base.php');

/**
 * @group totara_notification
 */
class totara_certification_totara_notification_new_exception_testcase extends totara_certification_totara_notification_base {

    public function test_resolver() {
        global $DB;

        $data = $this->setup_certifications();

        // Create a custom notification in system context.
        $notification_generator = notification_generator::instance();
        $notification_generator->create_notification_preference(
            new_exception_resolver::class,
            extended_context::make_system(),
            [
                'schedule_offset' => 0,
                'recipient' => site_admin::class,
                'body_format' => FORMAT_JSON_EDITOR,
                'body' => document_helper::json_encode_document(
                    document_helper::create_document_from_content_nodes([
                        paragraph::create_json_node_from_text('Test notification body'),
                        paragraph::create_json_node_with_content_nodes([
                            placeholder::create_node_from_key_and_label('recipient:full_name', 'Recipient full name'),
                            placeholder::create_node_from_key_and_label('certification:full_name', 'Certification full name'),
                        ]),
                    ])
                ),
                'subject' => 'Test notification subject',
                'subject_format' => FORMAT_PLAIN,
                'forced_delivery_channels' => null,
            ]
        );

        // Remove the 'assigned' notifiable event queue record.
        $DB->delete_records('notifiable_event_queue');

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        // Cause an exception.
        $DB->set_field('prog', 'exceptionssent', 0, ['id' => $data->program1->id]);
        $DB->insert_record('prog_exception', [
            'programid' => $data->program1->id,
            'userid' => get_admin()->id,
            'exceptiontype' => 2, // PROGRAM_EXCEPTION_DISMISSED
        ]);

        // Run the cron task which triggers the notifications.
        $program_messages_task = new send_messages_task();
        ob_start(); // Start a buffer to catch all the mtraces in the task.
        $program_messages_task->execute();
        ob_end_clean(); // Throw away the buffer content.

        self::assertEquals(1, (int)$DB->get_field('prog', 'exceptionssent', ['id' => $data->program1->id]));
        self::assertEquals(1, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        // Redirect messages.
        $sink = self::redirectMessages();

        // Run tasks.
        $task = new process_event_queue_task();
        $task->execute();

        // Includes the one built-in notification.
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(2, $DB->count_records(notification_queue::TABLE));

        $task = new process_notification_queue_task();
        $task->execute();

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $messages = $sink->get_messages();
        self::assertCount(1, $messages);
        $message = reset($messages);

        self::assertEquals('Test notification subject', $message->subject);
        self::assertStringContainsString('Test notification body', $message->fullmessage);
        self::assertStringContainsString('Admin User', $message->fullmessage);
        self::assertStringContainsString('My certification1 full name', $message->fullmessage);
        self::assertEquals(get_admin()->id, $message->userto->id);

        // If the whole cycle is repeated, then there shouldn't be an additional
        // exception messages sent.
        $program_messages_task = new send_messages_task();
        ob_start(); // Start a buffer to catch all the mtraces in the task.
        $program_messages_task->execute();
        ob_end_clean(); // Throw away the buffer content.

        self::assertEquals(1, (int)$DB->get_field('prog', 'exceptionssent', ['id' => $data->program1->id]));
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));
    }
}
