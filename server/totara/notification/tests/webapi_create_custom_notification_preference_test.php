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

use totara_notification\entity\notification_preference as entity;
use totara_notification\testing\generator;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_notification_webapi_create_custom_notification_preference_testcase extends advanced_testcase {
    use webapi_phpunit_helper;

    /**
     * @return void
     */
    protected function setUp(): void {
        /** @var generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $generator->include_mock_notifiable_event();
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_at_system_context(): void {
        global $DB;

        $context = context_system::instance();
        $this->setAdminUser();

        $result = $this->execute_graphql_operation(
            'totara_notification_create_custom_notification_preference',
            [
                'context_id' => $context->id,
                'event_class_name' => totara_notification_mock_notifiable_event::class,
                'body' => 'This is body',
                'body_format' => FORMAT_MOODLE,
                'subject' => 'This is subject',
                'title' => 'This is title',
            ]
        );

        self::assertEmpty($result->errors);
        self::assertNotEmpty($result->data);
        self::assertIsArray($result->data);
        self::assertArrayHasKey('notification_preference', $result->data);

        $notification_preference = $result->data['notification_preference'];
        self::assertIsArray($notification_preference);

        self::assertArrayHasKey('id', $notification_preference);
        self::assertTrue($DB->record_exists(entity::TABLE, ['id' => $notification_preference['id']]));

        self::assertArrayHasKey('title', $notification_preference);
        self::assertEquals('This is title', $notification_preference['title']);

        self::assertArrayHasKey('subject', $notification_preference);
        self::assertEquals('This is subject', $notification_preference['subject']);

        self::assertArrayHasKey('body', $notification_preference);
        self::assertEquals('This is body', $notification_preference['body']);

        self::assertArrayHasKey('body_format', $notification_preference);
        self::assertEquals(FORMAT_MOODLE, $notification_preference['body_format']);

        self::assertArrayHasKey('exist_in_context', $notification_preference);
        self::assertTrue($notification_preference['exist_in_context']);

        self::assertArrayHasKey('is_custom', $notification_preference);
        self::assertTrue($notification_preference['is_custom']);
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_at_lower_context(): void {
        global $DB;

        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $context_course = context_course::instance($course->id);
        $this->setAdminUser();

        $result = $this->execute_graphql_operation(
            'totara_notification_create_custom_notification_preference',
            [
                'context_id' => $context_course->id,
                'event_class_name' => totara_notification_mock_notifiable_event::class,
                'body' => 'First body',
                'body_format' => FORMAT_HTML,
                'subject' => 'First subject',
                'title' => 'First title',
            ]
        );

        self::assertEmpty($result->errors);
        self::assertNotEmpty($result->data);
        self::assertIsArray($result->data);
        self::assertArrayHasKey('notification_preference', $result->data);

        $notification_preference = $result->data['notification_preference'];
        self::assertIsArray($notification_preference);

        self::assertArrayHasKey('id', $notification_preference);
        self::assertTrue($DB->record_exists(entity::TABLE, ['id' => $notification_preference['id']]));

        self::assertArrayHasKey('title', $notification_preference);
        self::assertEquals('First title', $notification_preference['title']);

        self::assertArrayHasKey('subject', $notification_preference);
        self::assertEquals('First subject', $notification_preference['subject']);

        self::assertArrayHasKey('body', $notification_preference);
        self::assertEquals('First body', $notification_preference['body']);

        self::assertArrayHasKey('body_format', $notification_preference);
        self::assertEquals(FORMAT_HTML, $notification_preference['body_format']);

        self::assertArrayHasKey('exist_in_context', $notification_preference);
        self::assertTrue($notification_preference['exist_in_context']);

        self::assertArrayHasKey('is_custom', $notification_preference);
        self::assertTrue($notification_preference['is_custom']);
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_at_invalid_context(): void {
        $this->setAdminUser();
        $result = $this->execute_graphql_operation(
            'totara_notification_create_custom_notification_preference',
            [
                'context_id' => 42,
                'event_class_name' => totara_notification_mock_notifiable_event::class,
                'title' => 'custom title',
                'body' => 'custom body',
                'subject' => 'custom subject',
                'body_format' => FORMAT_MOODLE,
            ]
        );

        self::assertEmpty($result->data);
        self::assertNotEmpty($result->errors);
        self::assertIsArray($result->errors);
        self::assertCount(1, $result->errors);

        $first_error = reset($result->errors);
        self::assertIsObject($first_error);
        self::assertObjectHasAttribute('message', $first_error);

        self::assertStringContainsString("Can not find data record in database.", $first_error->message);
    }

    /**
     * Note: this test will become wrongly when capability is in place. However to fix this test, we
     * need to convert the test to assert that random user is not able to create the notification preference.
     *
     * @return void
     */
    public function test_create_custom_notification_as_normal_user(): void {
        global $DB;
        $generator = self::getDataGenerator();
        $user_one = $generator->create_user();

        $this->setUser($user_one);
        $result = $this->execute_graphql_operation(
            'totara_notification_create_custom_notification_preference',
            [
                'event_class_name' => totara_notification_mock_notifiable_event::class,
                'context_id' => context_system::instance()->id,
                'title' => 'This is custom',
                'body' => 'This is body',
                'subject' => 'This is subject',
                'body_format' => FORMAT_MOODLE,
            ]
        );

        self::assertEmpty($result->errors);
        self::assertNotEmpty($result->data);
        self::assertIsArray($result->data);
        self::assertArrayHasKey('notification_preference', $result->data);

        $notification_preference = $result->data['notification_preference'];
        self::assertIsArray($notification_preference);

        self::assertArrayHasKey('id', $notification_preference);
        self::assertTrue($DB->record_exists(entity::TABLE, ['id' => $notification_preference['id']]));

        self::assertArrayHasKey('title', $notification_preference);
        self::assertEquals('This is custom', $notification_preference['title']);

        self::assertArrayHasKey('subject', $notification_preference);
        self::assertEquals('This is subject', $notification_preference['subject']);

        self::assertArrayHasKey('body', $notification_preference);
        self::assertEquals('This is body', $notification_preference['body']);

        self::assertArrayHasKey('body_format', $notification_preference);
        self::assertEquals(FORMAT_MOODLE, $notification_preference['body_format']);

        self::assertArrayHasKey('exist_in_context', $notification_preference);
        self::assertTrue($notification_preference['exist_in_context']);

        self::assertArrayHasKey('is_custom', $notification_preference);
        self::assertTrue($notification_preference['is_custom']);
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_with_html_content_value_of_field_body(): void {
        $this->setAdminUser();
        $context_system = context_system::instance();

        $result = $this->execute_graphql_operation(
            'totara_notification_create_custom_notification_preference',
            [
                'event_class_name' => totara_notification_mock_notifiable_event::class,
                'context_id' => $context_system->id,
                'title' => 'ddd',
                'body' => /** @lang text */ '<input type="text" value="cc"/>',
                'subject' => 'lplpdw',
                'body_format' => FORMAT_MOODLE,
            ]
        );

        self::assertEmpty($result->data);
        self::assertNotEmpty($result->errors);
        self::assertCount(1, $result->errors);

        $error = reset($result->errors);

        self::assertStringContainsString(
            "The record data does not have required field 'body'",
            $error->message
        );
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_with_html_content_value_of_field_subject(): void {
        $this->setAdminUser();
        $context_system = context_system::instance();

        $result = $this->execute_graphql_operation(
            'totara_notification_create_custom_notification_preference',
            [
                'event_class_name' => totara_notification_mock_notifiable_event::class,
                'context_id' => $context_system->id,
                'title' => 'ddd',
                'body' => 'cccd',
                'subject' => /** @lang text */ '<input type="text" value="cc"/>',
                'body_format' => FORMAT_MOODLE,
            ]
        );

        self::assertEmpty($result->data);
        self::assertNotEmpty($result->errors);
        self::assertCount(1, $result->errors);

        $error = reset($result->errors);

        self::assertStringContainsString(
            "The record data does not have required field 'subject'",
            $error->message
        );
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_with_html_content_value_of_field_title(): void {
        $this->setAdminUser();
        $context_system = context_system::instance();

        $result = $this->execute_graphql_operation(
            'totara_notification_create_custom_notification_preference',
            [
                'event_class_name' => totara_notification_mock_notifiable_event::class,
                'context_id' => $context_system->id,
                'title' => /** @lang text */ '<input type="text" value="cc"/>',
                'body' => 'cccd',
                'subject' => 'pokopkopfw',
                'body_format' => FORMAT_MOODLE,
            ]
        );

        self::assertEmpty($result->data);
        self::assertNotEmpty($result->errors);
        self::assertCount(1, $result->errors);

        $error = reset($result->errors);

        self::assertStringContainsString(
            "The record data does not have required field 'title'",
            $error->message
        );
    }


    /**
     * @return void
     */
    public function test_create_custom_notification_with_xss_content_value_of_field_body(): void {
        $this->setAdminUser();
        $context_system = context_system::instance();

        $result = $this->execute_graphql_operation(
            'totara_notification_create_custom_notification_preference',
            [
                'event_class_name' => totara_notification_mock_notifiable_event::class,
                'context_id' => $context_system->id,
                'title' => 'This is title',
                'body' => /** @lang text */ '<script type="javascript">alert(1)</script>',
                'subject' => 'This is subject',
                'body_format' => FORMAT_MOODLE,
            ]
        );

        self::assertEmpty($result->errors);
        self::assertNotEmpty($result->data);
        self::assertIsArray($result->data);
        self::assertArrayHasKey('notification_preference', $result->data);

        $notification_preference = $result->data['notification_preference'];
        self::assertArrayHasKey('title', $notification_preference);
        self::assertEquals('This is title', $notification_preference['title']);

        self::assertArrayHasKey('subject', $notification_preference);
        self::assertEquals('This is subject', $notification_preference['subject']);

        self::assertArrayHasKey('body', $notification_preference);
        self::assertEquals('alert(1)', $notification_preference['body']);
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_with_xss_content_value_of_field_subject(): void {
        $this->setAdminUser();
        $context_system = context_system::instance();

        $result = $this->execute_graphql_operation(
            'totara_notification_create_custom_notification_preference',
            [
                'event_class_name' => totara_notification_mock_notifiable_event::class,
                'context_id' => $context_system->id,
                'title' => 'This is title',
                'body' => 'This is body',
                'subject' => /** @lang text */ '<script type="javascript">alert(1)</script>',
                'body_format' => FORMAT_MOODLE,
            ]
        );

        self::assertEmpty($result->errors);
        self::assertNotEmpty($result->data);
        self::assertIsArray($result->data);
        self::assertArrayHasKey('notification_preference', $result->data);

        $notification_preference = $result->data['notification_preference'];
        self::assertArrayHasKey('title', $notification_preference);
        self::assertEquals('This is title', $notification_preference['title']);

        self::assertArrayHasKey('subject', $notification_preference);
        self::assertEquals('alert(1)', $notification_preference['subject']);

        self::assertArrayHasKey('body', $notification_preference);
        self::assertEquals('This is body', $notification_preference['body']);
    }
}