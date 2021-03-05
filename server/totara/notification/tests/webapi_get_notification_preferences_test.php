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

use totara_notification\factory\notifiable_event_factory;
use totara_notification\loader\notification_preference_loader;
use totara_notification\model\notification_preference as model;
use totara_notification\testing\generator;
use totara_notification\webapi\resolver\query\notification_preferences;
use totara_notification_mock_built_in_notification as mock_built_in;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_notification_webapi_get_notification_preferences_testcase extends advanced_testcase {
    use webapi_phpunit_helper;

    /**
     * @return void
     */
    protected function setUp(): void {
        /** @var generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $generator->add_mock_built_in_notification_for_component();
        $generator->include_mock_recipient();
    }

    /**
     * @return void
     */
    public function test_get_notifications_at_context_only(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $context_course = context_course::instance($course->id);

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $custom_notification = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            $context_course->id,
            ['recipient' => totara_notification_mock_recipient::class]
        );

        $this->setAdminUser();
        $preferences = $this->resolve_graphql_query(
            $this->get_graphql_name(notification_preferences::class),
            [
                'context_id' => $context_course->id,
                'at_context_only' => true,
            ]
        );

        $system_built_in = notification_preference_loader::get_built_in(mock_built_in::class);
        self::assertCount(1, $preferences);

        /** @var model $preference */
        $preference = reset($preferences);
        self::assertInstanceOf(model::class, $preference);
        self::assertNotEquals($system_built_in->get_id(), $preference->get_id());
        self::assertEquals($custom_notification->get_id(), $preference->get_id());
    }

    /**
     * @return void
     */
    public function test_get_notifications_at_context_only_with_notifiable_event(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $context_course = context_course::instance($course->id);

        $all_events = notifiable_event_factory::get_notifiable_events();
        self::assertNotEmpty($all_events);

        $first_event = reset($all_events);

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $custom_one_notification = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            $context_course->id,
            ['recipient' => totara_notification_mock_recipient::class]
        );

        $custom_two_notification = $notification_generator->create_notification_preference(
            $first_event,
            $context_course->id,
            ['recipient' => totara_notification_mock_recipient::class]
        );

        $this->setAdminUser();

        // Now fetch the notification preferences at specific context, and only at the context.
        // However, we will narrow it down to just a specific event.
        $preferences = $this->resolve_graphql_query(
            $this->get_graphql_name(notification_preferences::class),
            [
                'context_id' => $context_course->id,
                'event_class_name' => $first_event,
                'at_context_only' => true,
            ]
        );

        self::assertNotEmpty($preferences);
        self::assertCount(1, $preferences);

        /** @var model $preference */
        $preference = reset($preferences);

        self::assertNotEquals($custom_one_notification->get_id(), $preference->get_id());
        self::assertEquals($custom_two_notification->get_id(), $preference->get_id());
    }

    /**
     * @return void
     */
    public function test_get_notification_at_context(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $context_course = context_course::instance($course->id);

        $all_events = notifiable_event_factory::get_notifiable_events();
        self::assertNotEmpty($all_events);

        $first_event = reset($all_events);

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $custom_one_notification = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            $context_course->id,
            ['recipient' => totara_notification_mock_recipient::class]
        );

        $custom_two_notification = $notification_generator->create_notification_preference(
            $first_event,
            $context_course->id,
            ['recipient' => totara_notification_mock_recipient::class]
        );

        $this->setAdminUser();

        // Now fetch the notification preferences at specific context, without specific at_only_context.
        $preferences = $this->resolve_graphql_query(
            $this->get_graphql_name(notification_preferences::class),
            ['context_id' => $context_course->id]
        );

        // Note that it is not reliable to do the exact count number.
        // However we can make sure that all the notification preferences that we
        // want it to appear in the list would appear in the list.
        self::assertNotEmpty($preferences);
        $preference_ids = array_map(
            function (model $preference): int {
                return $preference->get_id();
            },
            $preferences
        );

        $system_built_in = notification_preference_loader::get_built_in(mock_built_in::class);

        self::assertContainsEquals($custom_one_notification->get_id(), $preference_ids);
        self::assertContainsEquals($custom_two_notification->get_id(), $preference_ids);
        self::assertContainsEquals($system_built_in->get_id(), $preference_ids);
    }

    /**
     * @return void
     */
    public function test_get_notification_at_context_with_notifiable_event(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $context_course = context_course::instance($course->id);

        $all_events = notifiable_event_factory::get_notifiable_events();
        self::assertNotEmpty($all_events);

        $first_event = reset($all_events);
        $system_built_in = notification_preference_loader::get_built_in(mock_built_in::class);

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $system_overridden = $notification_generator->create_overridden_notification_preference(
            $system_built_in,
            $context_course->id,
            ['recipient' => totara_notification_mock_recipient::class]
        );

        $custom_two_notification = $notification_generator->create_notification_preference(
            $first_event,
            $context_course->id,
            ['recipient' => totara_notification_mock_recipient::class]
        );

        $this->setAdminUser();

        // Now fetch the notification preferences at specific context, without specific at_only_context.
        // But with specified event name.
        $preferences = $this->resolve_graphql_query(
            $this->get_graphql_name(notification_preferences::class),
            [
                'context_id' => $context_course->id,
                'event_class_name' => totara_notification_mock_notifiable_event::class,
            ]
        );

        self::assertNotEmpty($preferences);
        self::assertCount(1, $preferences);

        /** @var model $preference */
        $preference = reset($preferences);

        self::assertNotEquals($system_built_in->get_id(), $preference->get_id());
        self::assertNotEquals($custom_two_notification->get_id(), $preference->get_id());
        self::assertEquals($system_overridden->get_id(), $preference->get_id());
    }
}