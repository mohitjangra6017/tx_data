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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */

use totara_notification\entity\notifiable_event_queue;
use totara_notification\entity\notification_queue;
use totara_notification\manager\event_queue_manager;
use totara_notification\testing\generator;

class totara_notification_event_queue_manager_testcase extends advanced_testcase {
    /**
     * @return void
     */
    protected function setUp(): void {
        $generator = self::getDataGenerator();

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $notification_generator->include_mock_notifiable_event();

        $notification_generator->add_mock_built_in_notification_for_component();
    }

    /**
     * @return void
     */
    public function test_process_queues_with_valid_and_invalid_items(): void {
        global $DB;

        $generator = self::getDataGenerator();
        $user_one = $generator->create_user();

        $context_user = context_user::instance($user_one->id);

        // Create a valid queue.
        $valid_queue = new notifiable_event_queue();
        $valid_queue->context_id = $context_user->id;
        $valid_queue->set_decoded_event_data(['message' => 'data']);
        $valid_queue->event_name = totara_notification_mock_notifiable_event::class;
        $valid_queue->save();

        // Create an invalid queue.
        $invalid_queue = new notifiable_event_queue();
        $invalid_queue->context_id = $context_user->id;
        $invalid_queue->set_decoded_event_data(['boom' => 'kaboom']);
        $invalid_queue->event_name = 'anima_martin_garrix';
        $invalid_queue->save();

        // There should be two queues within database.
        self::assertEquals(2, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $trace = $notification_generator->get_test_progress_trace();

        // Process the queue.
        $manager = new event_queue_manager($trace);
        $manager->process_queues();

        // One is invalid record, which should be skipped to process.
        self::assertEquals(1, $DB->count_records(notifiable_event_queue::TABLE));

        // There should be one notification queue up, as one of the notifiable event
        // is a legitimate one.
        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));

        $error_messages = $trace->get_messages();
        self::assertNotEmpty($error_messages);
        self::assertCount(2, $error_messages);

        $first_message = reset($error_messages);
        self::assertEquals(
            "The event class name is not a notifiable event: 'anima_martin_garrix'",
            $first_message
        );

        $second_message = end($error_messages);
        self::assertEquals(
            "Cannot process the event queue at id: '{$invalid_queue->id}'",
            $second_message
        );
    }
}