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

use core_phpunit\testcase;
use totara_core\extended_context;
use totara_notification\entity\notifiable_event_queue;
use totara_notification\entity\notification_queue;
use totara_notification\manager\event_queue_manager;
use totara_notification\model\notification_preference;
use totara_notification\task\process_event_queue_task;
use totara_notification\testing\generator;

/**
 * This test is indirectly cover {@see event_queue_manager}
 */
class totara_notification_process_event_task_testcase extends testcase {
    /**
     * @return void
     */
    protected function setUp(): void {
        $notification_generator = generator::instance();
        $notification_generator->include_mock_notifiable_event();
    }

    /**
     * @return void
     */
    public function test_process_event_task_with_valid_event(): void {
        global $DB;
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $generator = self::getDataGenerator();

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $notification_generator->add_mock_built_in_notification_for_component();

        $context_system = context_system::instance();
        $data = ['user_id' => 42];

        // Create mock event first.
        $event_queue = new notifiable_event_queue();
        $event_queue->event_name = totara_notification_mock_notifiable_event::class;
        $event_queue->set_decoded_event_data($data);
        $event_queue->set_extended_context(extended_context::make_with_context($context_system));
        $event_queue->save();

        self::assertEquals(1, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        // Execute the tasks, which it should create a new notification queue from the event queue.
        $task = new process_event_queue_task();
        $task->execute();

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));

        $this->waitForSecond();

        // All the data from the event queue should be added to the nofication queue.
        // There should only be one record existing in the table.
        $notification_queue_record = $DB->get_record(
            notification_queue::TABLE,
            ['context_id' => $context_system->id],
            '*',
            MUST_EXIST
        );

        $notification_queue = new notification_queue($notification_queue_record);
        self::assertEquals(json_encode($data), $notification_queue->event_data);
        self::assertEquals($data, $notification_queue->get_decoded_event_data());
        self::assertEquals($context_system->id, $notification_queue->context_id);

        $notification_preference = notification_preference::from_id($notification_queue->notification_preference_id);
        self::assertEquals(
            totara_notification_mock_built_in_notification::class,
            $notification_preference->get_notification_class_name()
        );

        self::assertLessThan(time(), $notification_queue->scheduled_time);
    }

    /**
     * @return void
     */
    public function test_prcocess_valid_event_without_built_in_notification(): void {
        global $DB;
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $context_system = context_system::instance();

        // Create mock event first.
        $event_queue = new notifiable_event_queue();
        $event_queue->event_name = totara_notification_mock_notifiable_event::class;
        $event_queue->set_decoded_event_data([]);
        $event_queue->set_extended_context(extended_context::make_with_context($context_system));
        $event_queue->save();

        self::assertEquals(1, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        // Execute the tasks, which it should not create any new notification queue
        // from the event queue, because the built in notifications are not found for such event.
        $task = new process_event_queue_task();
        $task->execute();

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));
    }

    /**
     * @return void
     */
    public function test_process_on_invalid_event(): void {
        global $DB;
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $context_system = context_system::instance();

        // Create mock event first.
        $event_queue = new notifiable_event_queue();
        $event_queue->event_name = 'martin_garrix_anima_event';
        $event_queue->set_decoded_event_data([]);
        $event_queue->set_extended_context(extended_context::make_with_context($context_system));
        $event_queue->save();

        self::assertEquals(1, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        /** @var generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $trace = $generator->get_test_progress_trace();

        // Execute the tasks, which it should not create any new notification queue
        // from the event queue, because the built in notifications are not found for such event.
        $task = new process_event_queue_task();
        $task->set_trace($trace);
        $task->execute();

        // The event queue will be skipped to process, because it contain invalid record.
        self::assertEquals(1, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $messages = $trace->get_messages();
        self::assertCount(2, $messages);

        $first_message = reset($messages);
        self::assertEquals(
            "The event class name is not a notifiable event: 'martin_garrix_anima_event'",
            $first_message
        );

        $second_message = end($messages);
        self::assertEquals(
            "Cannot process the event queue at id: '{$event_queue->id}'",
            $second_message
        );
    }
}