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

use core\orm\query\exceptions\record_not_found_exception;
use core_phpunit\testcase;
use totara_core\extended_context;
use totara_notification\model\notification_preference as model;
use totara_notification\testing\generator;
use totara_notification\webapi\resolver\query\notification_preference;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_notification_webapi_get_notification_preference_testcase extends testcase {
    use webapi_phpunit_helper;

    /**
     * @return void
     */
    public function test_get_notification_preference(): void {
        $this->setAdminUser();

        /** @var generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $preference = $generator->add_mock_built_in_notification_for_component();

        /** @var model $fetched_preference */
        $fetched_preference = $this->resolve_graphql_query(
            $this->get_graphql_name(notification_preference::class),
            ['id' => $preference->get_id()]
        );

        self::assertInstanceOf(model::class, $fetched_preference);
        self::assertEquals($preference->get_id(), $fetched_preference->get_id());
    }

    /**
     * @return void
     */
    public function test_get_non_existing_notification_preference(): void {
        $this->setAdminUser();

        $this->expectException(record_not_found_exception::class);
        $this->expectExceptionMessage("Can not find data record in database");

        $this->resolve_graphql_query(
            $this->get_graphql_name(notification_preference::class),
            ['id' => 42]
        );
    }

    /**
     * @return void
     */
    public function test_get_overridden_notification_preference(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $system_built_in = $notification_generator->add_mock_built_in_notification_for_component();
        $notification_generator->include_mock_recipient();

        // Create an overridden at the course level.
        $course_overridden = $notification_generator->create_overridden_notification_preference(
            $system_built_in,
            extended_context::make_with_context(context_course::instance($course->id)),
            ['subject' => 'Course subject', 'recipient' => totara_notification_mock_recipient::class]
        );

        $this->setAdminUser();

        // Execute the persist query to get notification_preference.
        $first_result = $this->execute_graphql_operation(
            'totara_notification_notification_preference',
            ['id' => $course_overridden->get_id()]
        );

        self::assertEmpty($first_result->errors);
        self::assertNotEmpty($first_result->data);
        self::assertArrayHasKey('notification_preference', $first_result->data);

        $first_result_preference = $first_result->data['notification_preference'];
        self::assertIsArray($first_result_preference);

        self::assertArrayHasKey('id', $first_result_preference);
        self::assertEquals($course_overridden->get_id(), $first_result_preference['id']);

        self::assertArrayHasKey('parent_id', $first_result_preference);
        self::assertEquals($system_built_in->get_id(), $first_result_preference['parent_id']);

        self::assertArrayHasKey('subject', $first_result_preference);
        self::assertNotEquals($system_built_in->get_subject(), $first_result_preference['subject']);
        self::assertEquals('Course subject', $first_result_preference['subject']);

        self::assertArrayHasKey('title', $first_result_preference);
        self::assertEquals($system_built_in->get_title(), $first_result_preference['title']);

        self::assertArrayHasKey('body', $first_result_preference);
        self::assertEquals($system_built_in->get_body(), $first_result_preference['body']);

        self::assertArrayHasKey('body_format', $first_result_preference);
        self::assertEquals($system_built_in->get_body_format(), $first_result_preference['body_format']);

        self::assertArrayHasKey('overridden_body', $first_result_preference);
        self::assertFalse($first_result_preference['overridden_body']);

        self::assertArrayHasKey('overridden_subject', $first_result_preference);
        self::assertTrue($first_result_preference['overridden_subject']);

        // Create an overridden at the category context level.
        $context_category = context_coursecat::instance($course->category);
        $category_overridden = $notification_generator->create_overridden_notification_preference(
            $system_built_in,
            extended_context::make_with_context($context_category),
            ['body' => 'Category body', 'recipient' => totara_notification_mock_recipient::class]
        );

        $second_result = $this->execute_graphql_operation(
            'totara_notification_notification_preference',
            ['id' => $course_overridden->get_id()]
        );

        self::assertEmpty($second_result->errors);
        self::assertNotEmpty($second_result->data);
        self::assertArrayHasKey('notification_preference', $first_result->data);

        $second_result_preference = $second_result->data['notification_preference'];
        self::assertIsArray($second_result_preference);

        self::assertArrayHasKey('id', $second_result_preference);
        self::assertEquals($course_overridden->get_id(), $second_result_preference['id']);

        self::assertArrayHasKey('parent_id', $second_result_preference);
        self::assertNotEquals($system_built_in->get_id(), $second_result_preference['parent_id']);
        self::assertEquals($category_overridden->get_id(), $second_result_preference['parent_id']);

        self::assertArrayHasKey('subject', $second_result_preference);
        self::assertNotEquals($system_built_in->get_subject(), $second_result_preference['subject']);
        self::assertEquals('Course subject', $second_result_preference['subject']);

        self::assertArrayHasKey('title', $second_result_preference);
        self::assertEquals($system_built_in->get_title(), $second_result_preference['title']);

        self::assertArrayHasKey('body', $second_result_preference);
        self::assertEquals($category_overridden->get_body(), $second_result_preference['body']);

        self::assertArrayHasKey('body_format', $second_result_preference);
        self::assertEquals($system_built_in->get_body_format(), $second_result_preference['body_format']);

        self::assertArrayHasKey('overridden_body', $second_result_preference);
        self::assertFalse($second_result_preference['overridden_body']);

        self::assertArrayHasKey('overridden_subject', $second_result_preference);
        self::assertTrue($second_result_preference['overridden_subject']);
    }
}