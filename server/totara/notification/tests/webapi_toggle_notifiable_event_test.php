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
 * @author  Alastair Munro <alastair.munro@totaralearning.com>
 * @package totara_notification
 */

use core_phpunit\testcase;
use totara_core\extended_context;
use totara_notification\entity\notifiable_event_preference as entity;
use totara_notification\model\notifiable_event_preference;
use totara_notification\testing\generator;
use totara_notification\webapi\resolver\mutation\toggle_notifiable_event;
use totara_notification_mock_notifiable_event_resolver as mock_resolver;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_notification_webapi_toggle_notifiable_event_testcase extends testcase {
    use webapi_phpunit_helper;

    protected function setUp(): void {
        global $DB;

        $DB->execute('TRUNCATE TABLE {notifiable_event_preference}');

        $notification_generator = generator::instance();
        $notification_generator->include_mock_notifiable_event_resolver();
    }

    /**
     * @return void
     */
    public function test_toggle_in_system_context(): void {
        global $DB;

        // Confirm we have no existing preference
        $count = $DB->count_records('notifiable_event_preference');
        self::assertEquals(0, $count);

        $this->setAdminUser();
        $extended_context = extended_context::make_system();

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(toggle_notifiable_event::class),
            [
                'resolver_class_name' => mock_resolver::class,
                'extended_context' => [
                    'context_id' => $extended_context->get_context_id(),
                    'component' => $extended_context->get_component(),
                    'area' => $extended_context->get_area(),
                    'item_id' => $extended_context->get_item_id(),
                ],
                'is_enabled' => true,
            ]
        );

        $system_context = extended_context::make_system();

        $notifiable_event_entity = entity::repository()->for_context(mock_resolver::class, $extended_context);
        $notifiable_event = notifiable_event_preference::from_entity($notifiable_event_entity);
        $this->assertTrue($notifiable_event->enabled);
        $this->assertEquals(mock_resolver::class, $notifiable_event->resolver_class_name);
        $this->assertTrue($notifiable_event->extended_context->is_same($system_context));

        $count = $DB->count_records('notifiable_event_preference');
        self::assertEquals(1, $count);

        // Run mutation again setting enabled to false
        $this->resolve_graphql_mutation(
            $this->get_graphql_name(toggle_notifiable_event::class),
            [
                'resolver_class_name' => mock_resolver::class,
                'extended_context' => [
                    'context_id' => $extended_context->get_context_id(),
                    'component' => $extended_context->get_component(),
                    'area' => $extended_context->get_area(),
                    'item_id' => $extended_context->get_item_id(),
                ],
                'is_enabled' => false,
            ]
        );

        // Ensure we still only have one record
        $count = $DB->count_records('notifiable_event_preference');
        self::assertEquals(1, $count);

        $notifiable_event_entity = entity::repository()->for_context(mock_resolver::class, $extended_context);
        $notifiable_event = notifiable_event_preference::from_entity($notifiable_event_entity);
        $this->assertFalse($notifiable_event->enabled);
        $this->assertEquals(mock_resolver::class, $notifiable_event->resolver_class_name);
        $this->assertTrue($notifiable_event->extended_context->is_same($system_context));
    }

    public function test_toggle_in_course_context(): void {
        global $DB;

        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();
        $course_context = context_course::instance($course->id);
        $extended_context = extended_context::make_with_context($course_context);

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(toggle_notifiable_event::class),
            [
                'resolver_class_name' => mock_resolver::class,
                'extended_context' => [
                    'context_id' => $extended_context->get_context_id(),
                    'component' => $extended_context->get_component(),
                    'area' => $extended_context->get_area(),
                    'item_id' => $extended_context->get_item_id(),
                ],
                'is_enabled' => true,
            ]
        );

        $count = $DB->count_records('notifiable_event_preference');
        self::assertEquals(1, $count);

        $notifiable_event_entity = entity::repository()->for_context(mock_resolver::class, $extended_context);
        $notifiable_event = notifiable_event_preference::from_entity($notifiable_event_entity);
        $this->assertTrue($notifiable_event->enabled);
        $this->assertEquals(mock_resolver::class, $notifiable_event->resolver_class_name);
        $this->assertTrue($extended_context->is_same($notifiable_event->extended_context));
    }

    public function test_toggle_in_multiple_contexts(): void {
        global $DB;

        $this->setAdminUser();

        // Confirm we have no existing preference
        $count = $DB->count_records('notifiable_event_preference');
        self::assertEquals(0, $count);

        $course = $this->getDataGenerator()->create_course();
        $course_context = context_course::instance($course->id);
        $course_extended_context = extended_context::make_with_context($course_context);

        // Add course level preference
        $this->resolve_graphql_mutation(
            $this->get_graphql_name(toggle_notifiable_event::class),
            [
                'resolver_class_name' => mock_resolver::class,
                'extended_context' => [
                    'context_id' => $course_extended_context->get_context_id(),
                    'component' => $course_extended_context->get_component(),
                    'area' => $course_extended_context->get_area(),
                    'item_id' => $course_extended_context->get_item_id(),
                ],
                'is_enabled' => true,
            ]
        );

        $extended_context = extended_context::make_system();

        // Add system level preference
        $this->resolve_graphql_mutation(
            $this->get_graphql_name(toggle_notifiable_event::class),
            [
                'resolver_class_name' => mock_resolver::class,
                'extended_context' => [
                    'context_id' => $extended_context->get_context_id(),
                    'component' => $extended_context->get_component(),
                    'area' => $extended_context->get_area(),
                    'item_id' => $extended_context->get_item_id(),
                ],
                'is_enabled' => true,
            ]
        );

        // Confirm we have 2 preferences one for course context and one system
        $count = $DB->count_records('notifiable_event_preference');
        self::assertEquals(2, $count);
    }

    public function test_capability_checks(): void {
        $extended_context = extended_context::make_system();

        $user = $this->getDataGenerator()->create_user();
        $role_id = $this->getDataGenerator()->create_role();
        $this->getDataGenerator()->role_assign($role_id, $user->id, $extended_context->get_context_id());

        // Check that we can toggle the mutation
        assign_capability(
            'totara/notification:managenotifications',
            CAP_ALLOW,
            $role_id,
            $extended_context->get_context(),
            true
        );

        $this->setUser($user);

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(toggle_notifiable_event::class),
            [
                'resolver_class_name' => mock_resolver::class,
                'extended_context' => [
                    'context_id' => $extended_context->get_context_id(),
                    'component' => $extended_context->get_component(),
                    'area' => $extended_context->get_area(),
                    'item_id' => $extended_context->get_item_id(),
                ],
                'is_enabled' => true,
            ]
        );

        // Prohibit the capability and check that an exception is thrown
        assign_capability(
            'totara/notification:managenotifications',
            CAP_PROHIBIT,
            $role_id,
            $extended_context->get_context(),
            true
        );

        $this->expectException(required_capability_exception::class);

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(toggle_notifiable_event::class),
            [
                'resolver_class_name' => mock_resolver::class,
                'extended_context' => [
                    'context_id' => $extended_context->get_context_id(),
                    'component' => $extended_context->get_component(),
                    'area' => $extended_context->get_area(),
                    'item_id' => $extended_context->get_item_id(),
                ],
                'is_enabled' => true,
            ]
        );
    }
}