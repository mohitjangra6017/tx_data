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

class totara_notification_notification_queue_repository_testcase extends advanced_testcase {
    /**
     * @return void
     */
    public function test_get_queues_that_are_not_yet_due(): void {
        global $DB;

        $queue = new notification_queue();
        $queue->context_id = context_system::instance()->id;
        $queue->notification_name = 'martin_garrix';
        $queue->set_decoded_event_data([]);
        $queue->scheduled_time = 15;
        $queue->save();

        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));

        $repository = notification_queue::repository();
        $collection = $repository->get_due_notification_queues(10);

        $records = $collection->to_array();
        $collection->close();

        self::assertEmpty($records);
        self::assertCount(0, $records);

        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));
    }

    /**
     * @return void
     */
    public function test_get_queues_that_are_yet_due(): void {
        global $DB;

        $queue = new notification_queue();
        $queue->context_id = context_system::instance()->id;
        $queue->notification_name = 'martin_garrix';
        $queue->set_decoded_event_data([]);
        $queue->scheduled_time = 15;
        $queue->save();

        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));

        $repository = notification_queue::repository();
        $collection = $repository->get_due_notification_queues(42);

        $records = $collection->to_array();
        $collection->close();

        self::assertNotEmpty($records);
        self::assertCount(1, $records);

        /** @var notification_queue $first_record */
        $first_record = reset($records);

        self::assertInstanceOf(notification_queue::class, $first_record);
        self::assertEquals($queue->id, $first_record->id);

        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));
    }

    /**
     * @return void
     */
    public function test_get_queues_with_mixed_records(): void {
        $context_system = context_system::instance();

        $due_queue = new notification_queue();
        $due_queue->context_id = $context_system->id;
        $due_queue->notification_name = 'anima_garrix';
        $due_queue->scheduled_time = 15;

        $due_queue->set_decoded_event_data([]);
        $due_queue->save();

        $non_due_queue = new notification_queue();
        $non_due_queue->context_id = $context_system->id;
        $non_due_queue->notification_name = 'martin_garrix';
        $non_due_queue->scheduled_time = 21;

        $non_due_queue->set_decoded_event_data([]);
        $non_due_queue->save();

        $repository = notification_queue::repository();

        $collection = $repository->get_due_notification_queues(20);
        $records = $collection->to_array();

        $collection->close();
        self::assertCount(1, $records);

        // There should only be due queue returned from the repository.

        /** @var notification_queue $record */
        $record = reset($records);
        self::assertInstanceOf(notification_queue::class, $record);
        self::assertNotEquals($non_due_queue->id, $record->id);
        self::assertEquals($due_queue->id, $record->id);
    }
}