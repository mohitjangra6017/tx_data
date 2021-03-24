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
 * @author Samantha Jayasinghe <samantha.jayasinghe@totaralearning.com>
 * @package totara_notification
 */


use core\orm\query\builder;
use totara_core\extended_context;
use totara_notification\webapi\resolver\mutation\delete_notification_preference;
use totara_notification\entity\notification_preference as notification_preference_entity;
use totara_notification\testing\generator;
use totara_webapi\phpunit\webapi_phpunit_helper;

/**
 * @group totara_notification
 */
class totara_notification_webapi_delete_custom_notification_preference_testcase extends advanced_testcase {
    use webapi_phpunit_helper;

    /**
     * @return void
     */
    public function test_delete_custom_notification_preference(): void {
        $this->setAdminUser();

        /** @var generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');

        $preference = $generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            extended_context::make_with_context(context_system::instance()),
            [
                'body' => 'Parent body',
                'title' => 'Parent title',
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(delete_notification_preference::class),
            [
                'id' => $preference->get_id(),
            ]
        );

        $this->assertNull(notification_preference_entity::repository()->find($preference->get_id()));
    }

    public function test_delete_child_notification_preference(): void {
        $this->setAdminUser();

        /** @var generator $generator */
        $notification_generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $generator = self::getDataGenerator();
        $custom_parent = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            extended_context::make_with_context(context_system::instance()),
            [
                'body' => 'Parent body',
                'title' => 'Parent title',
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        $course_one = $generator->create_course();
        $course_two = $generator->create_course();

        $context_course_one = context_course::instance($course_one->id);
        $context_course_two = context_course::instance($course_two->id);

        $custom_child_one = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            extended_context::make_with_context($context_course_one),
            [
                'recipient' => totara_notification_mock_recipient::class,
                'ancestor_id' => $custom_parent->get_id(),
            ]
        );

        $custom_child_two = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            extended_context::make_with_context($context_course_two),
            [
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(delete_notification_preference::class),
            [
                'id' => $custom_parent->get_id(),
            ]
        );

        //check other child preference not deleted
        $this->assertTrue(notification_preference_entity::repository()->find($custom_child_two->get_id())->exists());

        //check parent and child preference deleted
        $this->assertNull(notification_preference_entity::repository()->find($custom_child_one->get_id()));
        $this->assertNull(notification_preference_entity::repository()->find($custom_parent->get_id()));
    }

    public function test_not_allow_to_delete_non_custom_notification_preference(): void {
        $this->setAdminUser();

        /** @var generator $notification_generator */
        $notification_generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $built_in_notification = $notification_generator->add_mock_built_in_notification_for_component();

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("Cannot delete non-custom notification");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(delete_notification_preference::class),
            [
                'id' => $built_in_notification->get_id(),
            ]
        );
    }

    public function test_not_allow_to_delete_override_notification_preference(): void {
        $this->setAdminUser();

        /** @var generator $notification_generator */
        $notification_generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $generator = self::getDataGenerator();
        $custom_parent = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            extended_context::make_with_context(context_system::instance()),
            [
                'body' => 'Parent body',
                'title' => 'Parent title',
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        $course_one = $generator->create_course();

        $context_course_one = context_course::instance($course_one->id);

        $custom_child_one = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            extended_context::make_with_context($context_course_one),
            [
                'recipient' => totara_notification_mock_recipient::class,
                'ancestor_id' => $custom_parent->get_id(),
            ]
        );

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("Cannot delete notification override");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(delete_notification_preference::class),
            [
                'id' => $custom_child_one->get_id(),
            ]
        );
    }

    public function test_user_cannot_delete_without_manage_capability(): void {
        $this->setAdminUser();
        $notification_generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $custom_notification = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            extended_context::make_with_context(context_system::instance()),
            [
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("You are not allowed to manage notification preference");

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(delete_notification_preference::class),
            [
                'id' => $custom_notification->get_id(),
            ]
        );
    }

    public function test_user_can_delete_with_manage_capability(): void {
        $this->setAdminUser();
        $notification_generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $custom_notification = $notification_generator->create_notification_preference(
            totara_notification_mock_notifiable_event::class,
            extended_context::make_with_context(context_system::instance()),
            [
                'recipient' => totara_notification_mock_recipient::class,
            ]
        );

        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);
        $role_id = builder::table('role')->where('shortname', 'user')->value('id');
        assign_capability('totara/notification:managenotifications', CAP_ALLOW, $role_id, SYSCONTEXTID, true);

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(delete_notification_preference::class),
            [
                'id' => $custom_notification->get_id(),
            ]
        );

        $this->assertNull(notification_preference_entity::repository()->find($custom_notification->get_id()));
    }

}