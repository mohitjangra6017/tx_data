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

require_once(__DIR__ . '/../../../totara/core/tests/language_pack_faker_trait.php');

use core_phpunit\testcase;
use engage_article\testing\generator as article_generator;
use totara_comment\testing\generator as comment_generator;
use totara_core\extended_context;
use totara_engage\access\access;
use totara_notification\entity\notification_queue;
use totara_notification\manager\notification_queue_manager;
use totara_notification\task\process_event_queue_task;
use totara_notification\task\process_notification_queue_task;
use totara_notification\testing\generator;
use totara_notification_mock_notifiable_event_resolver as mock_resolver;

class totara_notification_notification_queue_manager_testcase extends testcase {
    use language_pack_faker_trait;

    /**
     * @return void
     */
    protected function setUp(): void {
        $notification_generator = generator::instance();
        $notification_generator->include_mock_notifiable_event_resolver();
        $notification_generator->add_mock_built_in_notification_for_component();
    }

    /**
     * @return void
     */
    public function test_dispatch_valid_queues_with_invalid_queues(): void {
        global $DB;

        $generator = self::getDataGenerator();
        $user_one = $generator->create_user();
        $context_user = context_user::instance($user_one->id);

        $notification_generator = generator::instance();
        $notification_generator->add_mock_recipient_ids_to_resolver([$user_one->id]);

        $system_built_in = $notification_generator->add_mock_built_in_notification_for_component();
        $notification_generator->include_mock_recipient();

        // Create a valid queue.
        $valid_queue = new notification_queue();
        $valid_queue->set_decoded_event_data(['message' => 'This is message']);
        $valid_queue->notification_preference_id = $system_built_in->get_id();
        $valid_queue->scheduled_time = 10;
        $valid_queue->set_extended_context(extended_context::make_with_context($context_user));
        $valid_queue->save();


        // Create an invalid queue. To create an invalid record, we need to first
        // create the preference then delete it.
        $custom_preference = $notification_generator->create_notification_preference(
            mock_resolver::class,
            extended_context::make_with_context($context_user),
            ['recipient' => totara_notification_mock_recipient::class]
        );

        $invalid_queue = new notification_queue();
        $invalid_queue->set_decoded_event_data(['message' => 'This is invalid message']);
        $invalid_queue->notification_preference_id = $custom_preference->get_id();
        $invalid_queue->scheduled_time = 10;
        $invalid_queue->set_extended_context(extended_context::make_with_context($context_user));
        $invalid_queue->save();

        $custom_preference->delete();

        $trace = $notification_generator->get_test_progress_trace();
        $manager = new notification_queue_manager($trace);

        // 2 records at this point.
        self::assertEquals(2, $DB->count_records(notification_queue::TABLE));

        $sink = $this->redirectMessages();
        self::assertEquals(0, $sink->count());
        self::assertEmpty($sink->get_messages());

        $manager->dispatch_queues(15);

        // Check the queue is empty after sending the notifications. errors will add into the trace
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        // There is one message sent out
        $notifications = $sink->get_messages();
        self::assertCount(1, $notifications);

        self::assertNotEmpty($notifications);
        self::assertCount(1, $notifications);

        $first_notification = reset($notifications);
        self::assertIsObject($first_notification);
        self::assertObjectHasAttribute('fullmessage', $first_notification);
        self::assertEquals(
            totara_notification_mock_built_in_notification::get_default_body()->out(),
            $first_notification->fullmessage
        );

        self::assertObjectHasAttribute('subject', $first_notification);
        self::assertEquals(
            totara_notification_mock_built_in_notification::get_default_subject()->out(),
            $first_notification->subject
        );


        // There should be only one error message for fail record
        $error_messages = $trace->get_messages();
        self::assertNotEmpty($error_messages);
        self::assertCount(1, $error_messages);

        $first_message = reset($error_messages);
        self::assertEquals(
            "The notification preference record with id '{$invalid_queue->notification_preference_id}' does not exist",
            $first_message
        );
    }

    public function test_dispatch_respects_recipients_language() {
        $fake_language = 'xz';
        $this->add_fake_language_pack($fake_language, [
            'totara_comment' => [
                'comment_created' => 'Fake language - Comment created subject',
                'notification_comment_created_body' => 'Fake language - Comment created body',
            ],
        ]);

        $generator = self::getDataGenerator();
        $user1 = $generator->create_user();
        $user2 = $generator->create_user(['lang' => $fake_language]);

        /** @var article_generator $article_generator */
        $article_generator = $generator->get_plugin_generator('engage_article');
        $article_user1 = $article_generator->create_article([
            'userid' => $user1->id,
            'access' => access::PUBLIC,
        ]);
        $article_user2 = $article_generator->create_article([
            'userid' => $user2->id,
            'access' => access::PUBLIC,
        ]);

        /** @var comment_generator $comment_generator */
        $comment_generator = $generator->get_plugin_generator('totara_comment');
        $comment_generator->create_comment(
            $article_user1->get_id(),
            'engage_article',
            $article_user1::COMMENT_AREA,
            'This is a comment',
            FORMAT_PLAIN,
            $user2->id
        );
        $comment_generator->create_comment(
            $article_user2->get_id(),
            'engage_article',
            $article_user2::COMMENT_AREA,
            'This is a comment',
            FORMAT_PLAIN,
            $user1->id
        );

        $sink = self::redirectMessages();
        $task = new process_event_queue_task();
        $task->execute();
        $task = new process_notification_queue_task();
        $task->execute();

        self::assertEquals(2, $sink->count());
        $actual = array_map(function (stdClass $message) {
            return [
                'userid' => $message->useridto,
                'subject' => $message->subject,
                'body' => trim($message->fullmessage),
            ];
        }, $sink->get_messages());
        self::assertEqualsCanonicalizing([
            [
                'userid' => (int)$user1->id,
                'subject' => 'Comment created',
                'body' => 'A new comment created on your item',
            ],
            [
                'userid' => (int)$user2->id,
                'subject' => 'Fake language - Comment created subject',
                'body' => 'Fake language - Comment created body',
            ],
        ], $actual);
    }

    /**
     * @return void
     */
    public function test_dispatch_skips_disabled_preferences(): void {
        global $DB;

        $generator = self::getDataGenerator();
        $user_one = $generator->create_user();
        $context_user = context_user::instance($user_one->id);

        $notification_generator = generator::instance();
        $notification_generator->add_mock_recipient_ids_to_resolver([$user_one->id]);

        $system_built_in = $notification_generator->add_mock_built_in_notification_for_component();
        $notification_generator->include_mock_recipient();

        // Create a valid queue.
        $valid_queue = new notification_queue();
        $valid_queue->set_decoded_event_data(['message' => 'This is message']);
        $valid_queue->notification_preference_id = $system_built_in->get_id();
        $valid_queue->scheduled_time = 10;
        $valid_queue->set_extended_context(extended_context::make_with_context($context_user));
        $valid_queue->save();

        // Create an invalid queue. To create an invalid record, we need to first
        // create the preference then delete it.
        $disabled_preference = $notification_generator->create_notification_preference(
            mock_resolver::class,
            extended_context::make_with_context($context_user),
            ['recipient' => totara_notification_mock_recipient::class, 'enabled' => false]
        );

        $invalid_queue = new notification_queue();
        $invalid_queue->set_decoded_event_data(['message' => 'This is invalid message']);
        $invalid_queue->notification_preference_id = $disabled_preference->get_id();
        $invalid_queue->scheduled_time = 10;
        $invalid_queue->set_extended_context(extended_context::make_with_context($context_user));
        $invalid_queue->save();

        $trace = $notification_generator->get_test_progress_trace();
        $manager = new notification_queue_manager($trace);

        // 2 records at this point.
        self::assertEquals(2, $DB->count_records(notification_queue::TABLE));

        $sink = $this->redirectMessages();
        self::assertEquals(0, $sink->count());
        self::assertEmpty($sink->get_messages());

        $manager->dispatch_queues(15);

        // Check the queue is empty after sending the notifications. errors will add into the trace
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        // There is one message sent out
        $notifications = $sink->get_messages();
        self::assertCount(1, $notifications);

        self::assertNotEmpty($notifications);
        self::assertCount(1, $notifications);

        $first_notification = reset($notifications);
        self::assertIsObject($first_notification);
        self::assertObjectHasAttribute('fullmessage', $first_notification);
        self::assertEquals(
            totara_notification_mock_built_in_notification::get_default_body()->out(),
            $first_notification->fullmessage
        );

        self::assertObjectHasAttribute('subject', $first_notification);
        self::assertEquals(
            totara_notification_mock_built_in_notification::get_default_subject()->out(),
            $first_notification->subject
        );
    }
}