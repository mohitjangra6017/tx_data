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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
use totara_notification\entity\notification_queue;
use totara_notification\manager\notification_queue_manager;

class totara_notifiaction_notification_queue_manager_testcaase extends advanced_testcase {
    /**
     * @return void
     */
    protected function setUp(): void {
        $generator = self::getDataGenerator();

        /** @var totara_notification_generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $notification_generator->add_mock_built_in_notification_for_component();
    }

    /**
     * @return void
     */
    public function test_dispatch_valid_queues_with_invalid_queues(): void {
        global $DB;

        $generator = self::getDataGenerator();
        $user_one = $generator->create_user();

        $context_user = context_user::instance($user_one->id);

        /** @var totara_notification_generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $notification_generator->add_mock_recipient_ids_to_resolver([$user_one->id]);

        // Create a valid queue.
        $valid_queue = new notification_queue();
        $valid_queue->set_decoded_event_data(['message' => 'This is message']);
        $valid_queue->notification_name = totara_notification_mock_built_in_notification::class;
        $valid_queue->scheduled_time = 10;
        $valid_queue->context_id = $context_user->id;
        $valid_queue->save();

        // Create an invalid queue.
        $invalid_queue = new notification_queue();
        $invalid_queue->set_decoded_event_data(['message' => 'This is invalid message']);
        $invalid_queue->notification_name = 'martin_garrix_boom';
        $invalid_queue->scheduled_time = 10;
        $invalid_queue->context_id = $context_user->id;
        $invalid_queue->save();

        $trace = $notification_generator->get_test_progress_trace();
        $manager = new notification_queue_manager($trace);

        // 2 records at this point.
        self::assertEquals(2, $DB->count_records(notification_queue::TABLE));

        $sink = $this->redirectMessages();
        self::assertEquals(0, $sink->count());
        self::assertEmpty($sink->get_messages());

        $manager->dispatch_queues(15);

        // There should be one notification sent out, the invalid one will remain
        // in they database.
        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));

        // There is one message sent out
        $notifications = $sink->get_messages();
        self::assertCount(1, $notifications);

        self::assertNotEmpty($notifications);
        self::assertCount(1, $notifications);

        $first_notification = reset($notifications);
        self::assertIsObject($first_notification);
        self::assertObjectHasAttribute('fullmessage', $first_notification);
        self::assertEquals(
            totara_notification_mock_built_in_notification::get_default_body()->out(),
            $first_notification->fullmessage
        );

        self::assertObjectHasAttribute('subject', $first_notification);
        self::assertEquals(
            totara_notification_mock_built_in_notification::get_default_subject()->out(),
            $first_notification->subject
        );

        // Start the check againist error messages.
        $error_messages = $trace->get_messages();
        self::assertNotEmpty($error_messages);
        self::assertCount(2, $error_messages);

        $first_message = reset($error_messages);
        self::assertEquals(
            "The built-in notification does not exist in the system 'martin_garrix_boom'",
            $first_message
        );

        $second_message = end($error_messages);
        self::assertEquals(
            "Cannot dispatch the queue at id '{$invalid_queue->id}'",
            $second_message
        );
    }
}