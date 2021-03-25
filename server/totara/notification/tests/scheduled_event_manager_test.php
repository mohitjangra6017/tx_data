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
use totara_notification\entity\notification_queue;
use totara_notification\local\schedule_helper;
use totara_notification\manager\scheduled_event_manager;
use totara_notification\testing\generator;
use totara_notification_mock_recipient as mock_recipient;
use totara_notification_mock_scheduled_aware_event_resolver as scheduled_event_resolver;
use totara_notification_mock_scheduled_built_in_notification as built_in;

class totara_notification_scheduled_event_manager_testcase extends testcase {
    /**
     * @return void
     */
    protected function setUp(): void {
        $generator = generator::instance();
        $generator->include_mock_scheduled_aware_notifiable_event_resolver();
    }

    /**
     * @return void
     */
    public function test_schedule_before_event(): void {
        global $DB;

        $notification_generator = generator::instance();
        $notification_generator->add_notifiable_event_resolver(scheduled_event_resolver::class);

        $now = time();
        $extended_context = extended_context::make_system();

        // Create an event that has the event time which is happening 5 days from time-now
        scheduled_event_resolver::set_events([
            [scheduled_event_resolver::EVENT_TIME_KEY => ($now + schedule_helper::days_to_seconds(5))],
        ]);

        // Create a custom notification preference for scheduled event with 3 days before event time.
        $custom_preference = $notification_generator->create_notification_preference(
            scheduled_event_resolver::class,
            $extended_context,
            [
                // 3 days before the event time.
                'schedule_offset' => schedule_helper::days_to_seconds(-3),
                'recipient' => mock_recipient::class,
                'subject' => 'This is subject',
                'body' => 'This is body',
            ]
        );

        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $manager = new scheduled_event_manager();
        $manager->execute($now);

        // The process above should not queue up any notification as the time windows is not yet hit.
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        // Execute the before event but this time the time now is up to further two days and couple hours after.
        // And the last cron time is today.
        $manager->execute(
            ($now + schedule_helper::days_to_seconds(2) + (2 * HOURSECS)),
            $now
        );

        // There should be one record created for the queue.
        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));
        $fetched_preference_id = $DB->get_field(notification_queue::TABLE, 'notification_preference_id', [], MUST_EXIST);

        self::assertEquals(
            $custom_preference->get_id(),
            $fetched_preference_id
        );
    }

    /**
     * @return void
     */
    public function test_schedule_after_event(): void {
        global $DB;

        $notification_generator = generator::instance();
        $notification_generator->add_notifiable_event_resolver(scheduled_event_resolver::class);

        $now = time();
        $extended_context = extended_context::make_system();

        // Create an event that happened in a day to now.
        scheduled_event_resolver::set_events([
            [scheduled_event_resolver::EVENT_TIME_KEY => $now - schedule_helper::days_to_seconds(1)],
        ]);

        $custom_preference = $notification_generator->create_notification_preference(
            scheduled_event_resolver::class,
            $extended_context,
            [
                'recipient' => mock_recipient::class,
                'schedule_offset' => schedule_helper::days_to_seconds(3),
            ]
        );

        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $manager = new scheduled_event_manager();
        $manager->execute($now);

        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        // Now move the time now up to 3 days and check that we are able to queue the notifications.
        // And the last cron time is now.
        $manager->execute(
            $now + schedule_helper::days_to_seconds(3) + HOURSECS,
            $now
        );

        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));
        $fetched_preference_id = $DB->get_field(notification_queue::TABLE, 'notification_preference_id', [], MUST_EXIST);

        self::assertEquals(
            $custom_preference->get_id(),
            $fetched_preference_id
        );
    }

    /**
     * @return void
     */
    public function test_get_fixed_event_time_of_scheduled_event_to_be_empty(): void {
        $generator = generator::instance();
        $generator->add_notifiable_event_resolver(scheduled_event_resolver::class);

        $extended_context = extended_context::make_system();
        scheduled_event_resolver::set_events([
            [scheduled_event_resolver::EVENT_TIME_KEY => 0],
        ]);

        $generator->create_notification_preference(
            scheduled_event_resolver::class,
            $extended_context,
            [
                'recipient' => mock_recipient::class,
                'schedule_offset' => -schedule_helper::days_to_seconds(6),
            ]
        );

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('Invalid event time resolved by the resolver');

        $time_now = time();
        $manager = new scheduled_event_manager();
        $manager->execute($time_now - HOURSECS);
    }

    /**
     * @return void
     */
    public function test_trigger_exception_when_last_cron_time_is_greater_than_time_now(): void {
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('Invalid time frame that the min value is greater than max value');

        $manager = new scheduled_event_manager();
        $manager->execute(0, 1);
    }

    /**
     * @return void
     */
    public function test_execution_without_scheduled_preferences(): void {
        global $DB;

        $extended_context = extended_context::make_system();
        $generator = generator::instance();
        $generator->include_mock_scheduled_built_in_notification();

        // Reset the built in default offset - so that we can get a zero in the result
        // for min/max of resolver event.
        built_in::set_default_schedule_offset(0);

        $generator->create_notification_preference(
            scheduled_event_resolver::class,
            $extended_context,
            [
                'recipient' => mock_recipient::class,
                'schedule_offset' => 0,
            ]
        );

        scheduled_event_resolver::set_associated_notifiable_event(true);
        scheduled_event_resolver::set_events([
            [scheduled_event_resolver::EVENT_TIME_KEY => time() - DAYSECS],
        ]);

        $generator->purge_notifiable_event_resolvers();
        $generator->add_notifiable_event_resolver(scheduled_event_resolver::class);

        $trace = $generator->get_test_progress_trace();

        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));
        self::assertEmpty($trace->get_messages());

        // The manager should skip the event resolver above as there are no default
        $now = time();
        $last_cron = $now - DAYSECS;

        $manager = new scheduled_event_manager($trace);
        $manager->execute($now, $last_cron);

        // Nothing should be queued after the manager
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));
        $output_messages = $trace->get_messages();

        self::assertNotEmpty($output_messages);
        self::assertCount(4, $output_messages);

        $cls = scheduled_event_resolver::class;
        self::assertEquals(
            [
                "Current time is '{$now}'",
                "Last cron time is '{$last_cron}'",
                "The resolver class '{$cls}' does not have any scheduled preference.",
                "Furthermore the resolver does not implement interface for on event schedule, hence it is skipped.",
            ],
            $output_messages
        );
    }
}