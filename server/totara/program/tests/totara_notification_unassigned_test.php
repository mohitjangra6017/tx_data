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
 * @package totara_program
 */

use core\json_editor\helper\document_helper;
use core\json_editor\node\paragraph;
use totara_core\extended_context;
use totara_notification\entity\notifiable_event_queue;
use totara_notification\entity\notification_queue;
use totara_notification\task\process_event_queue_task;
use totara_notification\task\process_notification_queue_task;
use totara_notification\testing\generator as notification_generator;
use totara_program\event\program_unassigned as program_unassigned_event;
use totara_program\totara_notification\recipient\subject;
use totara_notification\json_editor\node\placeholder;
use totara_program\totara_notification\resolver\unassigned as program_unassigned_resolver;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/totara_notification_base.php');

/**
 * @group totara_notification
 */
class totara_program_totara_notification_unassigned_testcase extends totara_program_totara_notification_base {

    /**
     * @return void
     */
    public function test_unassigned(): void {
        global $DB;

        $data = $this->setup_programs();

        // Create a custom notification in system context.
        $notification_generator = notification_generator::instance();
        $notification_generator->create_notification_preference(
            program_unassigned_resolver::class,
            extended_context::make_system(),
            [
                'schedule_offset' => 0,
                'recipient' => subject::class,
                'body_format' => FORMAT_JSON_EDITOR,
                'body' => document_helper::json_encode_document(
                    document_helper::create_document_from_content_nodes([
                        paragraph::create_json_node_from_text('Test notification body'),
                        paragraph::create_json_node_with_content_nodes([
                            placeholder::create_node_from_key_and_label('recipient:last_name', 'Recipient last name'),
                            placeholder::create_node_from_key_and_label('program:full_name', 'Program full name'),
                            placeholder::create_node_from_key_and_label('managers:last_name', 'All managers last name'),
                            placeholder::create_node_from_key_and_label(
                                'assignment:due_date_criteria',
                                'Assignment due date criteria'
                            ),
                            placeholder::create_node_from_key_and_label(
                                'assignment:due_date',
                                'Assignment due date'
                            ),
                        ]),
                    ])
                ),
                'subject' => 'Test notification subject',
                'subject_format' => FORMAT_PLAIN,
            ]
        );

        // Remove the 'assigned' notifiable event queue record.
        $DB->delete_records('notifiable_event_queue');

        // Clear records.
        $DB->delete_records(notifiable_event_queue::TABLE);
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $event = program_unassigned_event::create([
            'objectid' => $data->program1->id,
            'context' => context_program::instance($data->program1->id),
            'userid' => $data->user1->id,
        ]);
        $event->trigger();

        self::assertEquals(1, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        // Redirect messages.
        $sink = self::redirectMessages();

        // Run tasks.
        $task = new process_event_queue_task();
        $task->execute();

        // Includes the two built-in notifications.
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(3, $DB->count_records(notification_queue::TABLE));

        $task = new process_notification_queue_task();
        $task->execute();

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $messages = $sink->get_messages();
        // Only one notification was processed, because the other built-in notifs were disabled.
        self::assertCount(1, $messages);

        $message = reset($messages);
        self::assertEquals('Test notification subject', $message->subject);
        self::assertStringContainsString('Test notification body', $message->fullmessage);
        self::assertStringContainsString('My user1 last name', $message->fullmessage);
        self::assertStringContainsString('My program1 full name', $message->fullmessage);
        self::assertStringContainsString('Manager1 last name, Manager2 last name', $message->fullmessage);
        self::assertStringContainsString('Due date criteria not defined', $message->fullmessage);
        self::assertStringContainsString(userdate($data->due_date->getTimestamp(), '%d/%m/%Y', 99, false), $message->fullmessage);
        self::assertEquals($data->user1->id, $message->userto->id);
    }
}
