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

use totara_core\extended_context;
use core_phpunit\testcase;
use totara_notification\entity\notifiable_event_preference as entity;
use totara_notification\factory\notifiable_event_resolver_factory;
use totara_notification\model\notifiable_event_preference;
use totara_notification\resolver\resolver_helper;
use totara_notification\testing\generator as totara_notification_generator;
use totara_notification\webapi\resolver\query\event_resolvers;
use totara_notification_mock_notifiable_event_resolver as mock_event_resolver;
use totara_webapi\phpunit\webapi_phpunit_helper;

/**
 * Note that this test is about testing the persist query rather than
 * the query resolver/handler. We are doing this because the resolver
 * only giving us the list of event class name.
 * Once we are upgrading the resolver to actually do DB look ups then it would be
 * the right time to have a test for the resolver.
 */
class totara_notification_webapi_get_event_resolvers_testcase extends testcase {
    use webapi_phpunit_helper;

    /**
     * @return void
     */
    public function test_get_event_resolvers_at_system_context(): void {
        $generator = totara_notification_generator::instance();
        $generator->include_mock_recipient();
        $generator->include_mock_notifiable_event_resolver();
        $generator->add_notifiable_event_resolver(mock_event_resolver::class);

        $context_system = context_system::instance();

        mock_event_resolver::set_notification_available_recipients([
            totara_notification_mock_recipient::class,
        ]);

        // Create a custom notification for the mock notifiable event and check if it is included
        // when calling to the query.
        $custom_notification = $generator->create_notification_preference(
            mock_event_resolver::class,
            extended_context::make_with_context($context_system),
            [
                'title' => 'Custom title',
                'subject' => 'Custom subject',
                'subject_format' => FORMAT_MOODLE,
                'body' => 'Custom body',
                'body_format' => FORMAT_MOODLE,
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        $this->setAdminUser();
        $result = $this->execute_graphql_operation(
            'totara_notification_event_resolvers',
            ['extended_context' => ['context_id' => $context_system->id],],
        );

        self::assertEmpty($result->errors);
        self::assertNotEmpty($result->data);
        self::assertIsArray($result->data);
        self::assertArrayHasKey('resolvers', $result->data);

        $resolvers = $result->data['resolvers'];
        self::assertIsArray($resolvers);

        // There are mock custom event, along side with all of the other events
        // from the system.
        self::assertGreaterThan(1, count($resolvers));
        $mock_resolvers = array_filter(
            $resolvers,
            function (array $resolver): bool {
                return $resolver['class_name'] === mock_event_resolver::class;
            }
        );

        self::assertCount(1, $mock_resolvers);
        $mock_resolver = reset($mock_resolvers);

        self::assertArrayHasKey('name', $mock_resolver);
        self::assertEquals(
            resolver_helper::get_human_readable_resolver_name(mock_event_resolver::class),
            $mock_resolver['name']
        );

        self::assertArrayHasKey('notification_preferences', $mock_resolver);
        self::assertIsArray($mock_resolver['notification_preferences']);

        // There should have only custom notification for this event at the system context.
        self::assertCount(1, $mock_resolver['notification_preferences']);
        $preference = reset($mock_resolver['notification_preferences']);

        self::assertIsArray($preference);
        self::assertArrayHasKey('id', $preference);
        self::assertEquals($custom_notification->get_id(), $preference['id']);

        self::assertArrayHasKey('status', $mock_resolver);
        $status = $mock_resolver['status'];
        self::assertTrue($status['is_enabled']);
    }

    /**
     * @return void
     */
    public function test_get_notifiable_events_at_lower_context(): void {
        $notification_generator = totara_notification_generator::instance();
        $notification_generator->include_mock_notifiable_event_resolver();
        $notification_generator->include_mock_recipient();
        $notification_generator->add_notifiable_event_resolver(mock_event_resolver::class);

        $context_system = context_system::instance();

        mock_event_resolver::set_notification_available_recipients([
            totara_notification_mock_recipient::class,
        ]);

        // Create a custom notification for the mock notifiable event and check if it is included
        // when calling to the query.
        $custom_notification = $notification_generator->create_notification_preference(
            mock_event_resolver::class,
            extended_context::make_with_context($context_system),
            [
                'title' => 'Custom title',
                'subject' => 'Custom subject',
                'subject_format' => FORMAT_MOODLE,
                'body' => 'Custom body',
                'body_format' => FORMAT_MOODLE,
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        // Create a course and fetch the notifiable_events at this course context.
        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $context_course = context_course::instance($course->id);

        $this->setAdminUser();
        mock_event_resolver::set_support_contexts(
            extended_context::make_with_context($context_course)
        );

        $result = $this->execute_graphql_operation(
            'totara_notification_event_resolvers',
            ['extended_context' => ['context_id' => $context_course->id],],
        );

        self::assertEmpty($result->errors);
        self::assertNotEmpty($result->data);
        self::assertIsArray($result->data);
        self::assertArrayHasKey('resolvers', $result->data);

        $resolvers = $result->data['resolvers'];
        self::assertIsArray($resolvers);

        // There are mock custom event, along side with all of the other events
        // from the system.
        self::assertGreaterThanOrEqual(1, count($resolvers));
        $mock_resolvers = array_filter(
            $resolvers,
            function (array $resolver): bool {
                return $resolver['class_name'] === mock_event_resolver::class;
            }
        );

        self::assertCount(1, $mock_resolvers);
        $mock_resolver = reset($mock_resolvers);

        self::assertArrayHasKey('name', $mock_resolver);
        self::assertEquals(
            resolver_helper::get_human_readable_resolver_name(mock_event_resolver::class),
            $mock_resolver['name']
        );

        self::assertArrayHasKey('notification_preferences', $mock_resolver);
        self::assertIsArray($mock_resolver['notification_preferences']);

        // There should have only custom notification for this event at the system context.
        self::assertCount(1, $mock_resolver['notification_preferences']);
        $preference = reset($mock_resolver['notification_preferences']);

        self::assertIsArray($preference);
        self::assertArrayHasKey('id', $preference);
        self::assertEquals($custom_notification->get_id(), $preference['id']);

        self::assertArrayHasKey('status', $mock_resolver);
        $status = $mock_resolver['status'];
        self::assertTrue($status['is_enabled']);
    }

    /**
     * @return void
     */
    public function test_get_notifiable_event_at_system_context_without_custom_at_lower_context(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $notification_generator = totara_notification_generator::instance();
        $notification_generator->include_mock_recipient();
        $notification_generator->include_mock_notifiable_event_resolver();
        $notification_generator->add_notifiable_event_resolver(mock_event_resolver::class);

        mock_event_resolver::set_notification_available_recipients([
            totara_notification_mock_recipient::class,
        ]);

        // Create a custom notification for the mock notifiable event and check if it is included
        // when calling to the query.
        $custom_notification = $notification_generator->create_notification_preference(
            mock_event_resolver::class,
            extended_context::make_with_context(context_course::instance($course->id)),
            [
                'title' => 'Custom title',
                'subject' => 'Custom subject',
                'subject_format' => FORMAT_MOODLE,
                'body' => 'Custom body',
                'body_format' => FORMAT_MOODLE,
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        $this->setAdminUser();
        $result = $this->execute_graphql_operation(
            'totara_notification_event_resolvers',
            ['extended_context' => ['context_id' => context_system::instance()->id],],
        );

        self::assertEmpty($result->errors);
        self::assertNotEmpty($result->data);
        self::assertIsArray($result->data);
        self::assertArrayHasKey('resolvers', $result->data);

        $resolvers = $result->data['resolvers'];
        self::assertIsArray($resolvers);

        // There are mock custom event, along side with all of the other events
        // from the system.
        self::assertGreaterThan(1, count($resolvers));
        $mock_resolvers = array_filter(
            $resolvers,
            function (array $notifiable_event): bool {
                return $notifiable_event['class_name'] === mock_event_resolver::class;
            }
        );

        self::assertCount(1, $mock_resolvers);
        $mock_resolver = reset($mock_resolvers);

        self::assertArrayHasKey('name', $mock_resolver);
        self::assertEquals(
            resolver_helper::get_human_readable_resolver_name(mock_event_resolver::class),
            $mock_resolver['name']
        );

        self::assertArrayHasKey('notification_preferences', $mock_resolver);
        self::assertIsArray($mock_resolver['notification_preferences']);
        self::assertEmpty($mock_resolver['notification_preferences']);

        foreach ($resolvers as $resolver) {
            self::assertArrayHasKey('notification_preferences', $resolver);
            $preferences = $resolver['notification_preferences'];

            self::assertIsArray($preferences);
            foreach ($preferences as $preference) {
                self::assertArrayHasKey('id', $preference);
                self::assertNotEquals($custom_notification->get_id(), $preference['id']);
            }
        }

        self::assertArrayHasKey('status', $mock_resolver);
        $status = $mock_resolver['status'];
        self::assertTrue($status['is_enabled']);
    }

    /**
     * @return void
     */
    public function test_get_notifiable_event_at_course_context_when_disabled_in_system_context(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $notification_generator = totara_notification_generator::instance();
        $notification_generator->include_mock_recipient();
        $notification_generator->include_mock_notifiable_event_resolver();
        $notification_generator->add_notifiable_event_resolver(mock_event_resolver::class);

        mock_event_resolver::set_notification_available_recipients([
            totara_notification_mock_recipient::class,
        ]);

        $course_context = context_course::instance($course->id);

        $this->setAdminUser();
        mock_event_resolver::set_support_contexts(
            extended_context::make_system(),
            extended_context::make_with_context($course_context)
        );

        $result = $this->execute_graphql_operation(
            'totara_notification_event_resolvers',
            ['extended_context' => ['context_id' => $course_context->id],],
        );

        self::assertEmpty($result->errors);
        self::assertNotEmpty($result->data);
        self::assertIsArray($result->data);
        self::assertArrayHasKey('resolvers', $result->data);

        $resolvers = $result->data['resolvers'];
        self::assertIsArray($resolvers);

        // There are mock custom event, along side with all of the other events
        // from the system.
        self::assertGreaterThanOrEqual(1, count($resolvers));
        $mock_resolvers = array_filter(
            $resolvers,
            function (array $resolver): bool {
                return $resolver['class_name'] === mock_event_resolver::class;
            }
        );

        self::assertCount(1, $mock_resolvers);
        $mock_resolver = reset($mock_resolvers);

        // Check the status of event before making any customisation
        self::assertArrayHasKey('status', $mock_resolver);
        $status = $mock_resolver['status'];
        self::assertTrue($status['is_enabled']);

        // Set notifiable event as disabled in system context
        $system_context = \context_system::instance();

        $extended_context = extended_context::make_with_context($system_context);

        $notifiable_event_entity = entity::repository()->for_context(mock_event_resolver::class, $extended_context);
        if (!$notifiable_event_entity) {
            $notifiable_event = notifiable_event_preference::create(mock_event_resolver::class, $extended_context);
        } else {
            $notifiable_event = notifiable_event_preference::from_entity($notifiable_event_entity);
        }

        $notifiable_event->set_enabled(false);
        $notifiable_event->save();

        // Check that event is now disabled in system context
        $result = $this->execute_graphql_operation(
            'totara_notification_event_resolvers',
            ['extended_context' => ['context_id' => $system_context->id],],
        );

        self::assertArrayHasKey('resolvers', $result->data);
        $resolvers = $result->data['resolvers'];
        self::assertIsArray($resolvers);

        // Get the mock custom event
        $mock_resolvers = array_filter(
            $resolvers,
            function (array $resolver): bool {
                return $resolver['class_name'] === mock_event_resolver::class;
            }
        );

        self::assertCount(1, $mock_resolvers);
        $mock_resolver = reset($mock_resolvers);

        self::assertArrayHasKey('status', $mock_resolver);
        $status = $mock_resolver['status'];
        self::assertFalse($status['is_enabled']);

        // Now check that it is not shown in the course context.
        $result = $this->execute_graphql_operation(
            'totara_notification_event_resolvers',
            ['extended_context' => ['context_id' => $course_context->id],],
        );

        self::assertArrayHasKey('resolvers', $result->data);
        $resolvers = $result->data['resolvers'];
        self::assertIsArray($resolvers);

        // Get the mock custom event
        $mock_resolvers = array_filter(
            $resolvers,
            function (array $resolver): bool {
                return $resolver['class_name'] === mock_event_resolver::class;
            }
        );

        // Resolver list should be empty before the filtering, but we'll check after filtering to make test more robust.
        self::assertEmpty($mock_resolvers);
    }

    /**
     * @return void
     */
    public function test_get_event_resolvers_at_system_contexts_where_one_resolver_does_not_support_user_permission(): void {
        global $DB;

        $generator = self::getDataGenerator();
        $user_one = $generator->create_user();

        $notification_generator = totara_notification_generator::instance();
        $notification_generator->add_notifiable_event_resolver(mock_event_resolver::class,);
        $notification_generator->include_mock_notifiable_event_resolver();

        $context_system = extended_context::make_system();
        mock_event_resolver::set_permissions($context_system, $user_one->id, true);

        // There will be mock_event_resolver returned in the factory, among the others.
        $event_resolvers = notifiable_event_resolver_factory::get_resolver_classes();
        self::assertGreaterThanOrEqual(1, count($event_resolvers));

        // Making sure that this user does not have the general capabilities.
        self::assertFalse(
            has_capability(
                'totara/notification:managenotifications',
                $context_system->get_context(),
                $user_one->id
            )
        );

        // Add a custom capability to the user to make sure that user is able to fetch the event resolvers,
        // but not all of it.
        $role_id = $DB->get_field('role', 'id', ['shortname' => 'user']);
        assign_capability(
            'moodle/site:config',
            CAP_ALLOW,
            $role_id,
            $context_system->get_context_id()
        );

        self::assertTrue(
            has_capability(
                'moodle/site:config',
                $context_system->get_context(),
                $user_one->id
            )
        );

        $notification_generator->add_extra_capability('moodle/site:config', [CONTEXT_SYSTEM]);
        $this->setUser($user_one);

        $fetched_event_resolvers = $this->resolve_graphql_query(
            $this->get_graphql_name(event_resolvers::class),
            [
                'extended_context' => [
                    'context_id' => $context_system->get_context_id(),
                ]
            ]
        );

        // There shoulld be only one notifiable event resolver that we added from the above.
        self::assertCount(1, $fetched_event_resolvers);
        $first_event_resolver = reset($fetched_event_resolvers);

        self::assertEquals(mock_event_resolver::class, $first_event_resolver);
    }
}