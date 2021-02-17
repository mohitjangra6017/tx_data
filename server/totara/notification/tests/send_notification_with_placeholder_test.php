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

use core_user\totara_notification\placeholder\user;
use totara_notification\entity\notification_queue;
use totara_notification\manager\event_queue_manager;
use totara_notification\manager\notification_queue_manager;
use totara_notification\observer\notifiable_event_observer;
use totara_notification\placeholder\placeholder_option;
use totara_notification\testing\generator;
use totara_notification_mock_notifiable_event as mock_notifiable_event;
use totara_notification\entity\notifiable_event_queue;

class totara_notification_send_notification_with_placeholder_testcase extends advanced_testcase {
    /**
     * @return void
     */
    protected function setUp(): void {
        /** @var generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $generator->include_mock_notifiable_event();
        $generator->include_mock_single_placeholder();
    }

    /**
     * @return void
     */
    public function test_send_custom_notification_with_placeholder(): void {
        global $DB;
        $generator = self::getDataGenerator();

        // This is the owner
        $user_one = $generator->create_user();

        // This is the author
        $user_two = $generator->create_user();
        $user_two->fullname = fullname($user_two);

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $context_system = context_system::instance();

        mock_notifiable_event::add_placeholder_options(
            placeholder_option::create(
                'owner',
                user::class,
                $notification_generator->give_my_mock_lang_string('Owner'),
                function (array $event_data): user {
                    return user::from_id($event_data['owner_id']);
                }
            ),

            placeholder_option::create(
                'author',
                user::class,
                $notification_generator->give_my_mock_lang_string('Author'),
                function (array $event_data): user {
                    return user::from_id($event_data['author_id']);
                }
            )
        );

        $notification_generator->add_mock_recipient_ids_to_resolver([$user_one->id]);
        $notification_generator->create_notification_preference(
            mock_notifiable_event::class,
            $context_system->id,
            [
                'title' => 'This is custom notification',
                'subject' => 'A notification for [owner:firstname]',
                'body' =>
                    'Hello [owner:firstname], a user [author:fullname] had make your item '.
                    'to this his/her timezone [author:timezone]',
                'body_format' => FORMAT_MOODLE
            ]
        );

        $event = new mock_notifiable_event(
            $context_system->id,
            [
                'owner_id' => $user_one->id,
                'author_id' => $user_two->id
            ]
        );

        notifiable_event_observer::watch_notifiable_event($event);
        self::assertEquals(1, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $queue_manager = new event_queue_manager();
        $queue_manager->process_queues();

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));

        $sink = self::redirectMessages();
        self::assertEquals(0, $sink->count());
        self::assertEmpty($sink->get_messages());

        $notification_queue_manager = new notification_queue_manager();
        $notification_queue_manager->dispatch_queues();

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        self::assertEquals(1, $sink->count());
        $messages = $sink->get_messages();

        self::assertCount(1, $messages);
        $message = reset($messages);

        self::assertObjectHasAttribute('fullmessage', $message);
        self::assertEquals(
            "Hello {$user_one->firstname}, a user {$user_two->fullname} had make your item to this his/her timezone " .
            core_date::get_localised_timezone(core_date::get_user_timezone($user_two)),
            $message->fullmessage
        );

        self::assertObjectHasAttribute('subject', $message);
        self::assertEquals("A notification for {$user_one->firstname}", $message->subject);

        self::assertObjectHasAttribute('userto', $message);
        self::assertIsObject($message->userto);
        self::assertEquals($user_one->id, $message->userto->id);
    }

    /**
     * @return void
     */
    public function test_send_custom_notification_with_placeholder_at_lower_context(): void {
        global $DB;
        $generator = self::getDataGenerator();

        // Author user
        $user_one = $generator->create_user();
        $user_one->fullname = fullname($user_one);

        // Commenter user
        $user_two = $generator->create_user();
        $user_two->fullname = fullname($user_two);

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $notification_generator->add_mock_recipient_ids_to_resolver([$user_one->id]);

        $custom_notification = $notification_generator->create_notification_preference(
            mock_notifiable_event::class,
            context_system::instance()->id,
            [
                'subject' => 'Hello [author:firstname], a new notification for you',
                'body' => 'Hello [author:fullname], user [commenter:fullname] had created a new comemnt in your code',
                'body_format' => FORMAT_MOODLE
            ]
        );

        $course = $generator->create_course();
        $context_course = context_course::instance($course->id);

        $notification_generator->create_overridden_notification_preference(
            $custom_notification,
            $context_course->id,
            ['body' => 'User [commenter:fullname] had created a new comment in [author:firstname]\'s code']
        );

        mock_notifiable_event::add_placeholder_options(
            placeholder_option::create(
                'author',
                user::class,
                $notification_generator->give_my_mock_lang_string('Author'),
                function (array $event_data): user {
                    return user::from_id($event_data['author_id']);
                }
            ),

            placeholder_option::create(
                'commenter',
                user::class,
                $notification_generator->give_my_mock_lang_string('Commenter'),
                function (array $event_data): user {
                    return user::from_id($event_data['commenter_id']);
                }
            )
        );

        // Now start sending a message out to the user one.
        $event = new mock_notifiable_event(
            $context_course->id,
            [
                'author_id' => $user_one->id,
                'commenter_id' => $user_two->id
            ]
        );

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        notifiable_event_observer::watch_notifiable_event($event);

        self::assertEquals(1, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $event_queue_manager = new event_queue_manager();
        $event_queue_manager->process_queues();

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));

        $sink = $this->redirectMessages();

        self::assertEquals(0, $sink->count());
        self::assertEmpty($sink->get_messages());

        $notification_queue_manager = new notification_queue_manager();
        $notification_queue_manager->dispatch_queues();

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        self::assertEquals(1, $sink->count());
        $messages = $sink->get_messages();

        self::assertCount(1, $messages);
        $message = reset($messages);

        self::assertIsObject($message);

        self::assertObjectHasAttribute('fullmessage', $message);
        self::assertNotEquals(
            "Hello {$user_one->fullname}, user {$user_two->fullname} had created a new comment in your code",
            $message->fullmessage
        );

        self::assertEquals(
            "User {$user_two->fullname} had created a new comment in {$user_one->firstname}'s code",
            $message->fullmessage
        );

        self::assertObjectHasAttribute('fullmessagehtml', $message);
        self::assertNotEquals(
            text_to_html("Hello {$user_one->fullname}, user {$user_two->fullname} had created a new comment in your code"),
            $message->fullmessagehtml
        );

        self::assertEquals(
            text_to_html("User {$user_two->fullname} had created a new comment in {$user_one->firstname}'s code"),
            $message->fullmessagehtml
        );

        self::assertObjectHasAttribute('subject', $message);
        self::assertEquals(
            "Hello {$user_one->firstname}, a new notification for you",
            $message->subject
        );
    }
}