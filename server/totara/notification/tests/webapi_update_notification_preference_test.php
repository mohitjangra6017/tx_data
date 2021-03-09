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

use core\json_editor\helper\document_helper;
use core\json_editor\node\paragraph;
use core\json_editor\node\text;
use core_phpunit\testcase;
use core_user\totara_notification\placeholder\user;
use totara_core\extended_context;
use totara_notification\builder\notification_preference_builder;
use totara_notification\json_editor\node\placeholder;
use totara_notification\loader\notification_preference_loader;
use totara_notification\local\schedule_helper;
use totara_notification\model\notification_preference as model;
use totara_notification\placeholder\placeholder_option;
use totara_notification\schedule\schedule_after_event;
use totara_notification\schedule\schedule_before_event;
use totara_notification\schedule\schedule_on_event;
use totara_notification\testing\generator;
use totara_notification\webapi\resolver\mutation\update_notification_preference;
use totara_notification_mock_built_in_notification as mock_built_in;
use totara_notification_mock_notifiable_event_resolver as mock_resolver;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_notification_webapi_update_notification_preference_testcase extends testcase {
    use webapi_phpunit_helper;

    /**
     * @return void
     */
    protected function setUp(): void {
        $notification_generator = generator::instance();

        $notification_generator->include_mock_notifiable_event_resolver();
        $notification_generator->add_mock_built_in_notification_for_component();
        $notification_generator->include_mock_recipient();
    }

    /**
     * @return void
     */
    public function test_update_notification_preference_without_title(): void {
        $system_built_in = notification_preference_loader::get_built_in(mock_built_in::class);

        self::assertNotEquals('Newly updated body', $system_built_in->get_body());
        self::assertNotEquals('Newly updated subject', $system_built_in->get_subject());

        $this->setAdminUser();

        /** @var model $preference */
        $preference = $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $system_built_in->get_id(),
                'body' => 'Newly updated body',
                'subject' => 'Newly updated subject',
            ]
        );

        self::assertInstanceOf(model::class, $preference);
        self::assertEquals($system_built_in->get_id(), $preference->get_id());

        $system_built_in->refresh();

        self::assertEquals('Newly updated body', $system_built_in->get_body());
        self::assertEquals('Newly updated subject', $system_built_in->get_subject());
    }

    /**
     * @return void
     */
    public function test_update_notification_preference_of_built_in_with_title(): void {
        $this->setAdminUser();
        $system_built_in = notification_preference_loader::get_built_in(
            totara_notification_mock_built_in_notification::class
        );

        // Start updating the notification preference with title.
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The title of overridden notification preference cannot be updated");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $system_built_in->get_id(),
                'title' => 'Kaboom martin garrix',
            ]
        );
    }

    /**
     * @return void
     */
    public function test_update_notification_preference_of_non_overridden_custom_with_title(): void {
        $this->setAdminUser();

        $generator = generator::instance();
        $custom_notification = $generator->create_notification_preference(
            mock_resolver::class,
            extended_context::make_with_context(context_system::instance()),
            [
                'title' => 'This is custom title',
                'body' => 'This is custom body',
                'subject' => 'This is custom subject',
                'body_format' => FORMAT_MOODLE,
                'subject_format' => FORMAT_PLAIN,
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        self::assertEquals('This is custom title', $custom_notification->get_title());
        self::assertEquals('This is custom body', $custom_notification->get_body());
        self::assertEquals('This is custom subject', $custom_notification->get_subject());
        self::assertEquals(FORMAT_MOODLE, $custom_notification->get_body_format());
        self::assertEquals(FORMAT_PLAIN, $custom_notification->get_subject_format());

        self::assertNotEquals('Updated title', $custom_notification->get_title());

        // Run mutation to update the custom notification.
        /** @var model $updated_notification */
        $updated_notification = $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $custom_notification->get_id(),
                'title' => 'Updated title',
            ]
        );

        self::assertInstanceOf(model::class, $updated_notification);
        self::assertEquals($custom_notification->get_id(), $updated_notification->get_id());

        $custom_notification->refresh();
        self::assertEquals('Updated title', $custom_notification->get_title());
    }

    /**
     * @return void
     */
    public function test_update_notification_preference_of_overridden_custom_with_title(): void {
        $this->setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $notification_generator = generator::instance();
        $system_custom = $notification_generator->create_notification_preference(
            mock_resolver::class,
            extended_context::make_with_context(context_system::instance()),
            [
                'title' => 'This is custom title',
                'body' => 'This is custom body',
                'subject' => 'This is custom subject',
                'body_format' => FORMAT_MOODLE,
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        // Note that the generator's api allow us to set the title.
        $course_custom = $notification_generator->create_overridden_notification_preference(
            $system_custom,
            extended_context::make_with_context(context_course::instance($course->id)),
            [
                'body' => 'course body',
                'subject' => 'course subject',
            ]
        );

        self::assertEquals('course body', $course_custom->get_body());
        self::assertEquals('course subject', $course_custom->get_subject());
        self::assertEquals(FORMAT_MOODLE, $course_custom->get_body_format());
        self::assertEquals('This is custom title', $course_custom->get_title());

        // Run mutation to update the custom notification.
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The title of overridden notification preference cannot be updated");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $course_custom->get_id(),
                'title' => 'Updated title',
            ]
        );
    }

    /**
     * @return void
     */
    public function test_update_notification_preference_of_non_overridden_custom_with_reset_title(): void {
        $this->setAdminUser();

        $generator = generator::instance();
        $custom_notification = $generator->create_notification_preference(
            mock_resolver::class,
            extended_context::make_with_context(context_system::instance()),
            [
                'title' => 'This is custom title',
                'body' => 'This is custom body',
                'subject' => 'This is custom subject',
                'body_format' => FORMAT_MOODLE,
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("Cannot reset the title of notification of custom notification that does not have parent");

        // Run mutation to update the custom notification.
        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $custom_notification->get_id(),
                'title' => null,
            ]
        );
    }

    /**
     * @return void
     */
    public function test_reset_notification_preference_body_with_empty_string(): void {
        $generator = generator::instance();
        $generator->add_string_body_to_mock_built_in_notification('This is built-in body');

        $this->setAdminUser();
        $system_built_in = notification_preference_loader::get_built_in(
            totara_notification_mock_built_in_notification::class
        );

        self::assertEquals('This is built-in body', $system_built_in->get_body());

        $builder = notification_preference_builder::from_exist_model($system_built_in);
        $builder->set_body('This is overridden body');

        $builder->save();
        $system_built_in->refresh();

        self::assertNotEquals('This is built-in body', $system_built_in->get_body());
        self::assertEquals('This is overridden body', $system_built_in->get_body());

        // Run the mutation.
        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $system_built_in->get_id(),
                'body' => null,
            ]
        );

        $system_built_in->refresh();

        self::assertNotEquals('This is overridden body', $system_built_in->get_body());
        self::assertEquals('This is built-in body', $system_built_in->get_body());
    }

    /**
     * @return void
     */
    public function test_reset_notification_preference_subject_with_empty_string(): void {
        $generator = generator::instance();
        $generator->add_string_subject_to_mock_built_in_notification('This is built-in subject');

        $this->setAdminUser();
        $system_built_in = notification_preference_loader::get_built_in(
            totara_notification_mock_built_in_notification::class
        );

        self::assertEquals('This is built-in subject', $system_built_in->get_subject());

        $builder = notification_preference_builder::from_exist_model($system_built_in);
        $builder->set_subject('This is overridden subject');

        $builder->save();
        $system_built_in->refresh();

        self::assertNotEquals('This is built-in subject', $system_built_in->get_subject());
        self::assertEquals('This is overridden subject', $system_built_in->get_subject());

        // Run the mutation.
        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $system_built_in->get_id(),
                'subject' => null,
            ]
        );

        $system_built_in->refresh();

        self::assertNotEquals('This is overridden subject', $system_built_in->get_subject());
        self::assertEquals('This is built-in subject', $system_built_in->get_subject());
    }

    /**
     * @return void
     */
    public function test_update_notification_preference_with_invalid_body_format(): void {
        $this->setAdminUser();
        $system_built_in = notification_preference_loader::get_built_in(
            totara_notification_mock_built_in_notification::class
        );

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The format value is invalid");

        // Run the mutation.
        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $system_built_in->get_id(),
                'body_format' => 42,
            ]
        );
    }

    /**
     * @return void
     */
    public function test_update_notification_preference_with_invalid_subject_format(): void {
        $this->setAdminUser();
        $system_built_in = notification_preference_loader::get_built_in(
            totara_notification_mock_built_in_notification::class
        );

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("The format value is invalid");

        // Run the mutation.
        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $system_built_in->get_id(),
                'subject_format' => 42,
            ]
        );
    }

    /**
     * @return void
     */
    public function test_update_notification_preference_without_providing_fields(): void {
        $this->setAdminUser();
        $generator = generator::instance();

        $notification = $generator->create_notification_preference(
            mock_resolver::class,
            extended_context::make_with_context(context_system::instance()),
            [
                'body' => 'Custom body',
                'body_format' => FORMAT_MOODLE,
                'subject' => 'Custom subject',
                'subject_format' => FORMAT_PLAIN,
                'title' => 'Custom title',
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        self::assertEquals('Custom body', $notification->get_body());
        self::assertEquals('Custom subject', $notification->get_subject());
        self::assertEquals('Custom title', $notification->get_title());

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $notification->get_id(),
                'body' => 'updated body',
            ]
        );

        $notification->refresh();

        self::assertNotEquals('Custom body', $notification->get_body());
        self::assertEquals('updated body', $notification->get_body());
        self::assertEquals('Custom subject', $notification->get_subject());
        self::assertEquals('Custom title', $notification->get_title());
    }

    /**
     * @return void
     */
    public function test_update_notification_preference_for_schedule(): void {
        $this->setAdminUser();
        $generator = generator::instance();

        $notification = $generator->create_notification_preference(
            mock_resolver::class,
            extended_context::make_with_context(context_system::instance()),
            [
                'body' => 'Custom body',
                'body_format' => FORMAT_MOODLE,
                'subject' => 'Custom subject',
                'title' => 'Custom title',
                'schedule_offset' => 0,
                'subject_format' => FORMAT_PLAIN,
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        self::assertEquals('Custom body', $notification->get_body());
        self::assertEquals('Custom subject', $notification->get_subject());
        self::assertEquals('Custom title', $notification->get_title());
        self::assertEquals(0, $notification->get_schedule_offset());

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $notification->get_id(),
                'schedule_offset' => 10,
                'schedule_type' => schedule_after_event::identifier(),
            ]
        );

        $notification->refresh();

        self::assertNotEquals(0, $notification->get_schedule_offset());
        self::assertEquals(schedule_helper::days_to_seconds(10), $notification->get_schedule_offset());

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $notification->get_id(),
                'schedule_offset' => 5,
                'schedule_type' => schedule_before_event::identifier(),
            ]
        );

        $notification->refresh();

        self::assertNotEquals(schedule_helper::days_to_seconds(10), $notification->get_schedule_offset());
        self::assertEquals(schedule_helper::days_to_seconds(-5), $notification->get_schedule_offset());

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $notification->get_id(),
                'schedule_offset' => 0,
                'schedule_type' => schedule_on_event::identifier(),
            ]
        );

        $notification->refresh();

        self::assertNotEquals(schedule_helper::days_to_seconds(-5), $notification->get_schedule_offset());
        self::assertEquals(0, $notification->get_schedule_offset());
    }

    /**
     * @return void
     */
    public function test_update_custom_notification_body_with_format_json_editor(): void {
        $generator = generator::instance();
        $context_system = context_system::instance();

        totara_notification_mock_notifiable_event_resolver::add_placeholder_options(
            placeholder_option::create(
                'user',
                user::class,
                $generator->give_my_mock_lang_string('User'),
                function (): void {
                    // The test is not about this function - so no point to write one.
                    throw new coding_exception("Do not call to this function in unit test");
                }
            )
        );

        $preference = $generator->create_notification_preference(
            mock_resolver::class,
            extended_context::make_with_context(context_system::instance()),
            [
                'body_format' => FORMAT_PLAIN,
                'body' => 'This is body',
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        self::assertEquals(FORMAT_PLAIN, $preference->get_body_format());
        self::assertEquals('This is body', $preference->get_body());

        $this->setAdminUser();
        $updated_body = document_helper::json_encode_document(
            document_helper::create_document_from_content_nodes([
                paragraph::create_json_node_with_content_nodes([
                    text::create_json_node_from_text('Hello '),
                    placeholder::create_node_from_key_and_label('user:firstname', 'User first name'),
                ]),
            ])
        );

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $preference->get_id(),
                'body_format' => FORMAT_JSON_EDITOR,
                'body' => $updated_body,
            ]
        );

        // Refresh with the newly updated field
        $preference->refresh();
        self::assertNotEquals(FORMAT_PLAIN, $preference->get_body_format());
        self::assertEquals(FORMAT_JSON_EDITOR, $preference->get_body_format());

        self::assertNotEquals('This is body', $preference->get_body());
        self::assertEquals($updated_body, $preference->get_body());
    }

    /**
     * @return void
     */
    public function test_update_notification_preference_with_valid_recipient(): void {
        $this->setAdminUser();
        $system_built_in = notification_preference_loader::get_built_in(mock_built_in::class);

        // Run the mutation.
        try {
            $this->resolve_graphql_mutation(
                $this->get_graphql_name(update_notification_preference::class),
                [
                    'id' => $system_built_in->get_id(),
                    'recipient' => totara_notification_mock_recipient::class,
                ]
            );
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function test_update_notification_preference_with_invalid_recipient(): void {
        $this->setAdminUser();
        $system_built_in = notification_preference_loader::get_built_in(mock_built_in::class);

        // Run the mutation.
        try {
            $this->resolve_graphql_mutation(
                $this->get_graphql_name(update_notification_preference::class),
                [
                    'id' => $system_built_in->get_id(),
                    'recipient' => 'totara_non\\existent\\recipient\\class',
                ]
            );
            $this->fail('Exception is expected but not thrown');
        } catch (Exception $e) {
            self::assertEquals(
                $e->getMessage(),
                'Coding error detected, it must be fixed by a programmer: ' .
                'totara_non\existent\recipient\class is not predefined recipient class'
            );
        }
    }
}