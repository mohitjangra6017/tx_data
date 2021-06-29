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

use core_phpunit\testcase;
use totara_core\extended_context;
use totara_notification_mock_additional_criteria_resolver as mock_resolver;
use totara_notification\entity\notifiable_event_queue;
use totara_notification\entity\notification_queue;
use totara_notification\manager\event_queue_manager;
use totara_notification\testing\generator as notification_generator;

class totara_notification_additional_criteria_testcase extends testcase {

    /**
     * @return void
     */
    public function test_event_queue_manager_processes_additional_criteria(): void {
        global $DB;

        $notif_generator = notification_generator::instance();
        $notif_generator->include_mock_additional_criteria_notification();
        $notif_generator->include_mock_additional_criteria_resolver();

        // Create a valid preference using the built-in notification - checks that get_default_additional_criteria
        // is working correctly, as well as inheritance.
        $valid_preference = $notif_generator->add_mock_built_in_notification_for_component(
            totara_notification_mock_additional_criteria_notification::class
        );

        $user_one = $this->getDataGenerator()->create_user();
        $context_user = context_user::instance($user_one->id);

        // Create a second notification preference, but this one is invalid.
        $notif_generator->create_notification_preference(
            mock_resolver::class,
            extended_context::make_with_context(context_system::instance()),
            [
                'additional_criteria' => json_encode((object)['valid' => false]),
            ]
        );

        // Create a queued event which meets the criteria to be sent.
        $meets_criteria_event = new notifiable_event_queue();
        $meets_criteria_event->set_extended_context(extended_context::make_with_context($context_user));
        $meets_criteria_event->set_decoded_event_data([
            'expected_context_id' => $context_user->id,
            'meets_criteria' => true,
        ]);
        $meets_criteria_event->resolver_class_name = mock_resolver::class;
        $meets_criteria_event->save();

        // Create a queued event which fails to meet the criteria to be sent.
        $fails_criteria_event = new notifiable_event_queue();
        $fails_criteria_event->set_extended_context(extended_context::make_with_context($context_user));
        $fails_criteria_event->set_decoded_event_data([
            'expected_context_id' => $context_user->id,
            'meets_criteria' => false,
        ]);
        $fails_criteria_event->resolver_class_name = mock_resolver::class;
        $fails_criteria_event->save();

        // There should be two events queued within database.
        self::assertEquals(2, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $trace = notification_generator::instance()->get_test_progress_trace();

        // Process the queue.
        $manager = new event_queue_manager($trace);
        $manager->process_queues();

        // There should be one notification queued up, as one of the notifiable events didn't meet the criteria and
        // was skipped, while one preference was invalid and was skipped.
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));

        // The one queued message relates to the valid preference, not the invalid one.
        self::assertEquals(1, $DB->count_records(
            notification_queue::TABLE,
            ['notification_preference_id' => $valid_preference->get_id()],
        ));

        $error_messages = $trace->get_messages();
        self::assertEmpty($error_messages);
    }
}