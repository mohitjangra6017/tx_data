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

use totara_notification\builder\notification_preference_builder;
use totara_notification\loader\notification_preference_loader;
use totara_notification\model\notification_preference as model;
use totara_notification\testing\generator;
use totara_notification\webapi\resolver\mutation\update_notification_preference;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_notification_webapi_update_notification_preference_testcase extends advanced_testcase {
    use webapi_phpunit_helper;

    /**
     * @return void
     */
    protected function setUp(): void {
        $generator = self::getDataGenerator();

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $notification_generator->include_mock_notifiable_event();
        $notification_generator->add_mock_built_in_notification_for_component();
    }

    /**
     * @return void
     */
    public function test_update_notification_preference_without_title(): void {
        $system_built_in = notification_preference_loader::get_built_in(
            totara_notification_mock_built_in_notification::class
        );

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

        /** @var generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $custom_notification = $generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            context_system::instance()->id,
            [
                'title' => 'This is custom title',
                'body' => 'This is custom body',
                'subject' => 'This is custom subject',
                'body_format' => FORMAT_MOODLE,
            ]
        );

        self::assertEquals('This is custom title', $custom_notification->get_title());
        self::assertEquals('This is custom body', $custom_notification->get_body());
        self::assertEquals('This is custom subject', $custom_notification->get_subject());
        self::assertEquals(FORMAT_MOODLE, $custom_notification->get_body_format());

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

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $system_custom = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            context_system::instance()->id,
            [
                'title' => 'This is custom title',
                'body' => 'This is custom body',
                'subject' => 'This is custom subject',
                'body_format' => FORMAT_MOODLE,
            ]
        );

        // Note that the generator's api allow us to set the title.
        $course_custom = $notification_generator->create_overridden_notification_preference(
            $system_custom,
            context_course::instance($course->id)->id,
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

        /** @var generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $custom_notification = $generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            context_system::instance()->id,
            [
                'title' => 'This is custom title',
                'body' => 'This is custom body',
                'subject' => 'This is custom subject',
                'body_format' => FORMAT_MOODLE,
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
        /** @var generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
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
        /** @var generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
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
    public function test_update_notification_preference_without_providing_fields(): void {
        $this->setAdminUser();

        /** @var generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $notification = $generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            context_system::instance()->id,
            [
                'body' => 'Custom body',
                'body_format' => FORMAT_MOODLE,
                'subject' => 'Custom subject',
                'title' => 'Custom title',
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

        /** @var generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $notification = $generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            context_system::instance()->id,
            [
                'body' => 'Custom body',
                'body_format' => FORMAT_MOODLE,
                'subject' => 'Custom subject',
                'title' => 'Custom title',
                'schedule_offset' => 0,
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
                'schedule_type' => \totara_notification\schedule\schedule_after_event::identifier(),
            ]
        );

        $notification->refresh();

        self::assertNotEquals(0, $notification->get_schedule_offset());
        self::assertEquals(10, $notification->get_schedule_offset());

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $notification->get_id(),
                'schedule_offset' => 5,
                'schedule_type' => \totara_notification\schedule\schedule_before_event::identifier(),
            ]
        );

        $notification->refresh();

        self::assertNotEquals(10, $notification->get_schedule_offset());
        self::assertEquals(-5, $notification->get_schedule_offset());

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_notification_preference::class),
            [
                'id' => $notification->get_id(),
                'schedule_offset' => 0,
                'schedule_type' => \totara_notification\schedule\schedule_on_event::identifier(),
            ]
        );

        $notification->refresh();

        self::assertNotEquals(-5, $notification->get_schedule_offset());
        self::assertEquals(0, $notification->get_schedule_offset());
    }
}