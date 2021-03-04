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

use totara_notification\loader\notification_preference_loader;
use totara_notification\testing\generator;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_notification_webapi_override_notification_preference_testcase extends advanced_testcase {
    use webapi_phpunit_helper;

    /**
     * @return void
     */
    protected function setUp(): void {
        $generator = self::getDataGenerator();

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $notification_generator->add_mock_built_in_notification_for_component();
        $notification_generator->include_mock_recipient();
    }

    /**
     * @return void
     */
    public function test_override_a_system_built_in_at_lower_context_without_providing_ancestor_id(): void {
        $this->setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $result = $this->execute_graphql_operation(
            'totara_notification_override_notification_preference',
            [
                'context_id' => context_course::instance($course->id)->id,
                'event_class_name' => totara_notification_mock_built_in_notification::class,
            ]
        );

        self::assertNotEmpty($result->errors);
        self::assertIsArray($result->errors);
        self::assertCount(1, $result->errors);

        $error = reset($result->errors);
        self::assertEquals(
            'Variable "$ancestor_id" of required type "param_integer!" was not provided.',
            $error->getMessage()
        );

        self::assertEmpty($result->data);
    }

    /**
     * @return void
     */
    public function test_override_a_system_built_in_at_lower_context(): void {
        $this->setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $context_course = context_course::instance($course->id);

        $system_built_in = notification_preference_loader::get_built_in(
            totara_notification_mock_built_in_notification::class,
        );

        $result = $this->execute_graphql_operation(
            'totara_notification_override_notification_preference',
            [
                'context_id' => $context_course->id,
                'event_class_name' => totara_notification_mock_notifiable_event::class,
                'ancestor_id' => $system_built_in->get_id(),
                'subject' => 'This is subject',
                'body_format' => FORMAT_HTML,
                'body' => 'This is body',
                'recipient' => totara_notification_mock_recipient::class
            ]
        );

        self::assertEmpty($result->errors);
        self::assertNotEmpty($result->data);
        self::assertIsArray($result->data);
        self::assertArrayHasKey('notification_preference', $result->data);

        $notification_preference = $result->data['notification_preference'];
        self::assertArrayHasKey('id', $notification_preference);
        self::assertGreaterThan(0, $notification_preference['id']);

        self::assertArrayHasKey('title', $notification_preference);
        self::assertEquals(
            totara_notification_mock_built_in_notification::get_title(),
            $notification_preference['title']
        );

        self::assertArrayHasKey('subject', $notification_preference);
        self::assertEquals('This is subject', $notification_preference['subject']);

        self::assertArrayHasKey('body', $notification_preference);
        self::assertEquals('This is body', $notification_preference['body']);

        self::assertArrayHasKey('body_format', $notification_preference);
        self::assertEquals(FORMAT_HTML, $notification_preference['body_format']);

        self::assertArrayHasKey('overridden_body', $notification_preference);
        self::assertArrayHasKey('overridden_subject', $notification_preference);

        self::assertTrue($notification_preference['overridden_body']);
        self::assertTrue($notification_preference['overridden_subject']);

        self::assertArrayHasKey('context_id', $notification_preference);
        self::assertEquals($context_course->id, $notification_preference['context_id']);

        self::assertArrayHasKey('event_name', $notification_preference);
        self::assertEquals(
            totara_notification_mock_notifiable_event::get_notification_title(),
            $notification_preference['event_name']
        );

        self::assertArrayHasKey('event_class_name', $notification_preference);
        self::assertEquals(
            totara_notification_mock_built_in_notification::get_event_class_name(),
            $notification_preference['event_class_name']
        );
    }
}