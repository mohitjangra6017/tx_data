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

use core\orm\query\builder;
use core_phpunit\testcase;
use totara_core\extended_context;
use totara_notification\entity\notification_preference as entity;
use totara_notification\loader\notification_preference_loader;
use totara_notification\model\notification_preference as model;
use totara_notification\schedule\schedule_on_event;
use totara_notification\testing\generator;
use totara_notification\webapi\resolver\mutation\create_notification_preference;
use totara_notification_mock_built_in_notification as mock_built_in;
use totara_notification_mock_notifiable_event_resolver as mock_resolver;
use totara_notification_mock_recipient as mock_recipient;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_notification_webapi_create_notification_preference_testcase extends testcase {
    use webapi_phpunit_helper;

    /**
     * @return void
     */
    protected function setUp(): void {
        $generator = generator::instance();

        $generator->include_mock_notifiable_event_resolver();
        $generator->include_mock_recipient();
        $generator->include_mock_built_in_notification();

        $generator->add_notifiable_event_resolver(mock_resolver::class);
        $generator->add_mock_built_in_notification_for_component(mock_built_in::class);
    }

    /**
     * Create an overridden notification preference at the course context.
     * @return void
     */
    public function test_create_notification_preference_from_built_in(): void {
        global $DB;
        $this->setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $system_built_in = notification_preference_loader::get_built_in(mock_built_in::class);

        /** @var model $notification_preference */
        $notification_preference = $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'ancestor_id' => $system_built_in->get_id(),
                'extended_context' => [
                    'context_id' => context_course::instance($course->id)->id,
                ],
                'resolver_class_name' => mock_resolver::class,
                'body' => 'This is overridden body',
            ]
        );

        self::assertInstanceOf(model::class, $notification_preference);

        self::assertTrue(
            $DB->record_exists(
                entity::TABLE,
                ['id' => $notification_preference->get_id()]
            )
        );

        self::assertEquals($system_built_in->get_subject(), $notification_preference->get_subject());
        self::assertEquals($system_built_in->get_title(), $notification_preference->get_title());
        self::assertEquals($system_built_in->get_body_format(), $notification_preference->get_body_format());
        self::assertNotEquals($system_built_in->get_body(), $notification_preference->get_body());
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_with_missing_body_field(): void {
        $this->setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The record data does not have required field 'body'");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => context_course::instance($course->id)->id,
                ],
                'resolver_class_name' => mock_resolver::class,
                'body_format' => FORMAT_MOODLE,
                'title' => 'This is title',
                'subject' => 'This is subject',
                'subject_format' => FORMAT_PLAIN,
                'schedule_type' => schedule_on_event::identifier(),
                'schedule_offset' => 0,
                'enabled' => true,
                'recipient' => mock_recipient::class,
            ]
        );
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_with_missing_body_format_field(): void {
        $this->setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The record data does not have required field 'body_format'");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => context_course::instance($course->id)->id,
                ],
                'resolver_class_name' => mock_resolver::class,
                'body' => 'This is body',
                'title' => 'This is title',
                'subject' => 'This is subject',
                'subject_format' => FORMAT_PLAIN,
                'schedule_type' => schedule_on_event::identifier(),
                'schedule_offset' => 0,
                'enabled' => true,
                'recipient' => mock_recipient::class,
            ]
        );
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_with_missing_subject_format_field(): void {
        $this->setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The record data does not have required field 'subject_format'");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => context_course::instance($course->id)->id,
                ],
                'resolver_class_name' => mock_resolver::class,
                'body' => 'This is body',
                'body_format' => FORMAT_PLAIN,
                'title' => 'This is title',
                'subject' => 'This is subject',
                'schedule_type' => schedule_on_event::identifier(),
                'schedule_offset' => 0,
                'enabled' => true,
                'recipient' => mock_recipient::class,
            ]
        );
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_with_missing_subject_field(): void {
        $this->setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The record data does not have required field 'subject'");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => context_course::instance($course->id)->id,
                ],
                'resolver_class_name' => mock_resolver::class,
                'body' => 'This is body',
                'title' => 'This is title',
                'body_format' => FORMAT_MOODLE,
                'subject_format' => FORMAT_PLAIN,
                'schedule_type' => schedule_on_event::identifier(),
                'schedule_offset' => 0,
                'enabled' => true,
                'recipient' => mock_recipient::class,
            ]
        );
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_with_missing_title_field(): void {
        $this->setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The record data does not have required field 'title'");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => context_course::instance($course->id)->id,
                ],
                'resolver_class_name' => mock_resolver::class,
                'body' => 'This is body',
                'subject' => 'This is subject',
                'body_format' => FORMAT_MOODLE,
                'subject_format' => FORMAT_PLAIN,
                'schedule_type' => schedule_on_event::identifier(),
                'schedule_offset' => 0,
                'enabled' => true,
                'recipient' => mock_recipient::class,
            ]
        );
    }

    /**
     * @return void
     */
    public function test_create_custom_notification_with_missing_enabled_field(): void {
        $this->setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The record data does not have required field 'enabled'");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => context_course::instance($course->id)->id,
                ],
                'resolver_class_name' => mock_resolver::class,
                'body' => 'This is body',
                'subject' => 'This is subject',
                'body_format' => FORMAT_MOODLE,
                'subject_format' => FORMAT_PLAIN,
                'schedule_type' => schedule_on_event::identifier(),
                'schedule_offset' => 0,
                'title' => 'This is title',
                'recipient' => mock_recipient::class
            ]
        );
    }

    /**
     * This test is about making sure that passing context system to the resolver should not
     * break anything.
     *
     * @return void
     */
    public function test_create_custom_notification_in_system_context(): void {
        global $DB;
        $this->setAdminUser();

        /** @var model $notification_preference */
        $notification_preference = $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => context_system::instance()->id,
                ],
                'resolver_class_name' => mock_resolver::class,
                'body' => 'This is body',
                'subject' => 'This is subject',
                'body_format' => FORMAT_HTML,
                'subject_format' => FORMAT_PLAIN,
                'title' => 'This is title',
                'schedule_type' => schedule_on_event::identifier(),
                'schedule_offset' => 0,
                'recipient' => totara_notification_mock_recipient::class,
                'enabled' => true,
            ]
        );

        self::assertInstanceOf(model::class, $notification_preference);
        self::assertTrue($DB->record_exists(entity::TABLE, ['id' => $notification_preference->get_id()]));

        self::assertEquals('This is body', $notification_preference->get_body());
        self::assertEquals('This is subject', $notification_preference->get_subject());
        self::assertEquals(FORMAT_HTML, $notification_preference->get_body_format());
        self::assertEquals('This is title', $notification_preference->get_title());
    }

    /**
     * @return void
     */
    public function test_create_an_overridden_of_built_in_that_is_already_existing_in_the_context(): void {
        global $DB;
        $this->setAdminUser();

        $category_id = $DB->get_field('course_categories', 'id', ['issystem' => 0], MUST_EXIST);
        $context_category = context_coursecat::instance($category_id);

        $system_built_in = notification_preference_loader::get_built_in(mock_built_in::class);

        /** @var model $overridden_preference */
        $overridden_preference = $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => $context_category->id,
                ],
                'resolver_class_name' => mock_resolver::class,
                'ancestor_id' => $system_built_in->get_id(),
            ]
        );

        self::assertInstanceOf(model::class, $overridden_preference);
        self::assertTrue($DB->record_exists(entity::TABLE, ['id' => $overridden_preference->get_id()]));

        self::assertEquals(
            1,
            $DB->count_records(
                entity::TABLE,
                [
                    'context_id' => $context_category->id,
                    'resolver_class_name' => mock_resolver::class,
                ]
            )
        );

        // Now start create a same record with the mutation, which we should expect to have an exception.
        try {
            $this->resolve_graphql_mutation(
                $this->get_graphql_name(create_notification_preference::class),
                [
                    'extended_context' => [
                        'context_id' => $context_category->id,
                    ],
                    'resolver_class_name' => mock_resolver::class,
                    'ancestor_id' => $system_built_in->get_id(),
                ]
            );

            $this->fail("Expecting an exception to be thrown");
        } catch (coding_exception $e) {
            $this->assertStringContainsString(
                "Notification override already exists in the given context",
                $e->getMessage()
            );
        }
    }

    /**
     * @return void
     */
    public function test_create_an_overridden_of_built_in_at_system_context(): void {
        global $DB;
        $this->setAdminUser();

        $context_system = context_system::instance();

        // At this point there should have 1 record of the mock built in within the table at the system context.
        self::assertEquals(
            1,
            $DB->count_records(
                entity::TABLE,
                [
                    'context_id' => $context_system->id,
                    'notification_class_name' => mock_built_in::class,
                ]
            )
        );

        try {
            $this->resolve_graphql_mutation(
                $this->get_graphql_name(create_notification_preference::class),
                [
                    'extended_context' => [
                        'context_id' => $context_system->id,
                    ],
                    'resolver_class_name' => mock_resolver::class,
                    'ancestor_id' => 42,
                ]
            );
            $this->fail("Expecting an exception to be thrown");
        } catch (coding_exception $e) {
            $this->assertStringContainsString(
                "Cannot create a notification at context system with the ancestor's id set",
                $e->getMessage()
            );
        }
    }

    /**
     * @return void
     */
    public function test_create_a_built_in_notification_with_trailing_backslash(): void {
        global $DB;
        $this->setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $system_built_in = notification_preference_loader::get_built_in(mock_built_in::class);

        /** @var model $notification_preference */
        $notification_preference = $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => context_course::instance($course->id)->id,
                ],
                'resolver_class_name' => '\\totara_notification_mock_notifiable_event_resolver',
                'ancestor_id' => $system_built_in->get_id(),
                'body' => 'Overridden body',
            ]
        );

        self::assertInstanceOf(model::class, $notification_preference);

        self::assertTrue(
            $DB->record_exists(
                entity::TABLE,
                ['id' => $notification_preference->get_id()]
            )
        );

        self::assertEquals($system_built_in->get_subject(), $notification_preference->get_subject());
        self::assertEquals($system_built_in->get_title(), $notification_preference->get_title());
        self::assertEquals($system_built_in->get_body_format(), $notification_preference->get_body_format());
        self::assertNotEquals($system_built_in->get_body(), $notification_preference->get_body());
    }

    /**
     * @return void
     */
    public function test_create_a_custom_notification_with_invalid_resolver_name(): void {
        $this->setAdminUser();
        $context = context_system::instance();

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The resolver class is not a notifiable event resolver");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'resolver_class_name' => 'hello_world',
                'extended_context' => [
                    'context_id' => $context->id,
                ],
            ]
        );
    }

    /**
     * @return void
     */
    public function test_create_a_custom_notification_with_invalid_body_format(): void {
        $this->setAdminUser();

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The format value is invalid");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => context_system::instance()->id,
                ],
                'resolver_class_name' => mock_resolver::class,
                'body_format' => 42,
            ]
        );
    }

    /**
     * @return void
     */
    public function test_create_a_custom_notification_with_invalid_subject_format(): void {
        $this->setAdminUser();

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The format value is invalid");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'resolver_class_name' => mock_resolver::class,
                'extended_context' => [
                    'context_id' => context_system::instance()->id,
                ],
                'subject_format' => 42,
            ]
        );
    }


    /**
     * @return void
     */
    public function test_create_notification_preference_from_a_different_path_context(): void {
        $generator = self::getDataGenerator();
        $other_category = $generator->create_category();

        $misc_course = $generator->create_course();
        self::assertNotEquals($other_category->id, $misc_course->category);

        // Create a custom notification at course category context.
        $context_other_cat = context_coursecat::instance($other_category->id);
        $context_course = context_course::instance($misc_course->id);

        $notification_generator = generator::instance();
        $custom_category = $notification_generator->create_notification_preference(
            mock_resolver::class,
            extended_context::make_with_context($context_other_cat),
            ['recipient' => totara_notification_mock_recipient::class]
        );

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage(
            "The context path of ancestor does not appear in the context path of the overridden preference"
        );

        $this->setAdminUser();
        $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => $context_course->id,
                ],
                'resolver_class_name' => mock_resolver::class,
                'ancestor_id' => $custom_category->get_id(),
                'body' => 'This is new body',
                'subject' => 'This is new subject',
                'title' => 'This is title',
                'body_format' => FORMAT_MOODLE,
                'subject_format' => FORMAT_PLAIN,
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );
    }

    /**
     * @return void
     */
    public function test_create_duplicate_of_custom_notification(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $notification_generator = generator::instance();
        $system_custom = $notification_generator->create_notification_preference(
            mock_resolver::class,
            extended_context::make_with_context(context_system::instance()),
            ['recipient' => totara_notification_mock_recipient::class]
        );

        $context_course = context_course::instance($course->id);

        // Override at the context course
        $notification_generator->create_overridden_notification_preference(
            $system_custom,
            extended_context::make_with_context($context_course),
            ['subject' => 'Course subject']
        );

        // Try to create another custom with the graphql.
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage(
            "Notification override already exists in the given context"
        );

        $this->setAdminUser();
        $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => $context_course->id,
                ],
                'ancestor_id' => $system_custom->get_id(),
                'subject' => 'New Subject',
                'body' => 'New body',
                'resolver_class_name' => mock_resolver::class,
                'body_format' => FORMAT_MOODLE,
                'subject_format' => FORMAT_PLAIN,
            ]
        );
    }

    public function test_user_cannot_create_notification_without_manage_capability(): void {
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);

        $course = $this->getDataGenerator()->create_course();
        $system_built_in = notification_preference_loader::get_built_in(
            totara_notification_mock_built_in_notification::class
        );

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("You are not allowed to manage notification preference");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => context_course::instance($course->id)->id,
                ],
                'ancestor_id' => $system_built_in->get_id(),
                'resolver_class_name' => mock_resolver::class,
                'body' => 'This is overridden body',
            ]
        );
    }

    public function test_user_can_create_notification_with_manage_capability(): void {
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);

        $course = $this->getDataGenerator()->create_course();
        $system_built_in = notification_preference_loader::get_built_in(
            totara_notification_mock_built_in_notification::class
        );

        $role_id = builder::table('role')->where('shortname', 'user')->value('id');
        assign_capability('totara/notification:managenotifications', CAP_ALLOW, $role_id, SYSCONTEXTID, true);

        $notification_preference = $this->resolve_graphql_mutation(
            $this->get_graphql_name(create_notification_preference::class),
            [
                'extended_context' => [
                    'context_id' => context_course::instance($course->id)->id,
                ],
                'ancestor_id' => $system_built_in->get_id(),
                'resolver_class_name' => mock_resolver::class,
                'body' => 'This is overridden body',
            ]
        );

        self::assertEquals($system_built_in->get_subject(), $notification_preference->get_subject());
        self::assertEquals($system_built_in->get_title(), $notification_preference->get_title());
        self::assertEquals($system_built_in->get_body_format(), $notification_preference->get_body_format());
        self::assertNotEquals($system_built_in->get_body(), $notification_preference->get_body());
    }
}