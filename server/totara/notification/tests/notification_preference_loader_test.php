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

use container_course\course;
use totara_notification\loader\notification_preference_loader;
use totara_notification\model\notification_preference;

class totara_notification_notification_preference_loader_testcase extends advanced_testcase {
    protected function setUp(): void {
        /** @var totara_notification_generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $generator->add_mock_built_in_notification_for_component();

        $generator->include_mock_notifiable_event();
    }

    /**
     * @return void
     */
    public function test_fetch_all_notifications_should_exclude_those_middle_overriddens(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        /** @var totara_notification_generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $mock_preference = notification_preference_loader::get_built_in(
            totara_notification_mock_built_in_notification::class
        );

        // Create at course category level.
        $context_category = context_coursecat::instance($course->category);
        $category_preference = $notification_generator->create_overridden_notification_preference(
            $mock_preference,
            $context_category->id,
            [
                'title' => 'Kaboom',
                'subject' => 'dada-di-da'
            ]
        );

        // Create at course level.
        $context_course = context_course::instance($course->id);
        $course_preference = $notification_generator->create_overridden_notification_preference(
            $mock_preference,
            $context_course->id,
        );

        // Mock one custom record at system context for an event that we are going to fetch notifications for.
        $custom_preference = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            context_system::instance()->id,
            [
                'title' => 'kaboom',
                'subject' => 'my name',
                'body' => 'body',
                'body_format' => FORMAT_MOODLE
            ]
        );

        // Now loading the list of notification preferences that react to the specific notifiable event at the context course
        // level and we should be able to see two preferences, one is the mock that we had overridden at the course level
        // and one that is a custom one that we created at this course level.
        $preferences = notification_preference_loader::get_notification_preferences(
            $context_course->id,
            totara_notification_mock_notifiable_event::class
        );

        self::assertCount(2, $preferences);
        foreach ($preferences as $preference) {
            self::assertContainsEquals(
                $preference->get_id(),
                [
                    $custom_preference->get_id(),
                    $course_preference->get_id()
                ]
            );

            self::assertNotEquals($preference->get_id(), $mock_preference->get_id());
            self::assertNotEquals($preference->get_id(), $category_preference->get_id());
        }
    }

    /**
     * @return void
     */
    public function test_fetch_custom_preferences_that_should_not_include_ancestor(): void {
        $generator = self::getDataGenerator();
        $course_record = $generator->create_course();

        $course = course::from_record($course_record);

        /** @var totara_notification_generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');

        // Create a custom notification at the top level.
        $system_custom = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            context_system::instance()->id,
            [
                'body' => 'data',
                'subject' => 'body',
                'title' => 'title',
                'body_format' => FORMAT_JSON_EDITOR
            ]
        );

        // Create a custom notification at the category level, but different from  the system  one.
        $category_custom = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            context_coursecat::instance($course->category)->id,
            [
                'body' => 'daa',
                'subject' => 'ioko',
                'title' => 'category title',
                'body_format' => FORMAT_HTML
            ]
        );

        // Create an override at the course level, which override from the system one.
        $override_course = $notification_generator->create_overridden_notification_preference(
            $system_custom,
            $course->get_context()->id,
            [
                'body' => 'override body kjo!'
            ]
        );

        $preferences = notification_preference_loader::get_notification_preferences(
            $course->get_context()->id,
            totara_notification_mock_notifiable_event::class
        );

        // There should have 3 preferences:
        // + One override at the course context.
        // + One custom at category context
        // + And one mock at the system context.
        self::assertCount(3, $preferences);
        $mock_preference = notification_preference_loader::get_built_in(totara_notification_mock_built_in_notification::class);

        foreach ($preferences as $preference) {
            self::assertContainsEquals(
                $preference->get_id(),
                [
                    $mock_preference->get_id(),
                    $category_custom->get_id(),
                    $override_course->get_id()
                ]
            );

            self::assertNotEquals(
                $preference->get_id(),
                $system_custom->get_id()
            );
        }
    }

    /**
     * @return void
     */
    public function test_fetch_middle_overridden_preferences(): void {
        $generator = self::getDataGenerator();
        $course_record = $generator->create_course();

        $course = course::from_record($course_record);

        $context_course = $course->get_context();
        $context_category = $context_course->get_parent_context();

        /** @var totara_notification_generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $system_built_in = notification_preference_loader::get_built_in(totara_notification_mock_built_in_notification::class);

        // Override this system built in at the category level.
        $category_built_in = $notification_generator->create_overridden_notification_preference(
            $system_built_in,
            $context_category->id,
            ['body' => 'Category body']
        );

        // Fetch the notification at the course context level.
        // Start loading the preferences.
        $preferences = notification_preference_loader::get_notification_preferences(
            $context_course->id,
            totara_notification_mock_notifiable_event::class
        );

        // There should only have one preference, as the course context should fall back to the category level.
        self::assertCount(1, $preferences);
        $preference = reset($preferences);

        self::assertEquals($category_built_in->get_id(), $preference->get_id());
    }

    /**
     * @return void
     */
    public function test_find_built_in(): void {
        $result = notification_preference_loader::get_built_in('this_is_random');
        self::assertNull($result);

        $exist_preference = notification_preference_loader::get_built_in(totara_notification_mock_built_in_notification::class);
        self::assertNotNull($exist_preference);
    }

    /**
     * @return void
     */
    public function test_find_only_overridden_at_lower_context(): void {
        // Create a custom notification at the system context.
        // Then overriding the system built in notification preference at this course
        // context, and check the loader if it is loading the course context only.
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        /** @var totara_notification_generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $system_custom = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            context_system::instance()->id,
            ['body' => 'Custom body',]
        );

        // Create overridden of system built in at course context.
        $system_built_in = notification_preference_loader::get_built_in(
            totara_notification_mock_built_in_notification::class
        );

        $context_course = context_course::instance($course->id);
        $system_overridden = $notification_generator->create_overridden_notification_preference(
            $system_built_in,
            $context_course->id
        );

        $preferences = notification_preference_loader::get_notification_preferences(
            $context_course->id,
            null,
            true
        );

        self::assertCount(1, $preferences);
        $preference = reset($preferences);

        self::assertInstanceOf(notification_preference::class, $preference);
        self::assertNotEquals($system_custom->get_id(), $preference->get_id());
        self::assertNotEquals($system_built_in->get_id(), $preference->get_id());
        self::assertEquals($system_overridden->get_id(), $preference->get_id());
    }
}