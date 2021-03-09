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
 * @author  Johannes Cilliers <johannes.cilliers@totaralearning.com>
 * @package engage_article
 */

use core_phpunit\testcase;
use engage_article\testing\generator as article_generator;
use engage_article\totara_engage\resource\article;
use totara_comment\comment_helper;
use totara_comment\totara_notification\recipient\owner;
use totara_comment\totara_notification\resolver\comment_created;
use totara_core\extended_context;
use totara_engage\access\access;
use totara_notification\model\notification_preference as notification_preference_model;
use totara_notification\task\process_event_queue_task;
use totara_notification\task\process_notification_queue_task;
use totara_notification\testing\generator as notification_generator;
use totara_topic\testing\generator as topic_generator;

class engage_article_totara_notification_recipient_testcase extends testcase {
    /**
     * @return void
     */
    public function test_resource_owner_recipient(): void {
        // Create notification preference with a mock recipient that will resolve
        // to user one's ID.
        $this->create_notification_preference(
            comment_created::class,
            owner::class,
        );

        $generator = $this->getDataGenerator();
        $user_one = $generator->create_user();
        $user_two = $generator->create_user();

        // Set admin as active user.
        $this->setAdminUser();

        /** @var topic_generator $topic_generator */
        $topic_generator = $generator->get_plugin_generator('totara_topic');
        $topic = $topic_generator->create_topic();

        // Set user one as active user.
        $this->setUser($user_one);

        /** @var article_generator $article_generator */
        $article_generator = $generator->get_plugin_generator('engage_article');
        $article = $article_generator->create_article([
            'access' => access::PUBLIC,
            'topics' => [$topic->get_id()],
        ]);

        comment_helper::create_comment(
            article::get_resource_type(),
            article::COMMENT_AREA,
            $article->get_id(),
            'This is content',
            FORMAT_PLAIN,
            null,
            $user_two->id
        );

        // Redirect messages.
        $message_sink = $this->redirectMessages();

        // Run tasks.
        $task = new process_event_queue_task();
        $task->execute();
        $task = new process_notification_queue_task();
        $task->execute();

        // Get messages.
        $messages = $message_sink->get_messages();

        // We should get 2 messages (one for built-in and another for our preference)
        $this->assertCount(2, $messages);

        $this->assertEquals(
            true,
            $this->user_message_exists(
                $messages,
                "A new comment created on your item\n",
                $article->get_userid()
            ),
            'Notification message not found for user'
        );

        $this->assertEquals(
            true,
            $this->user_message_exists(
                $messages,
                'Notification preference body for [' . comment_created::class . ',' . owner::class . ']',
                $article->get_userid()
            ),
            'Notification message not found for user'
        );
    }

    /**
     * @param array  $messages
     * @param string $full_message
     * @param string $to_user_id
     *
     * @return bool
     */
    private function user_message_exists(array $messages, string $full_message, string $to_user_id): bool {
        foreach ($messages as $message) {
            if ($message->fullmessage === $full_message && $message->userto->id === $to_user_id) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return notification_generator
     */
    private function get_generator(): notification_generator {
        return notification_generator::instance();
    }

    /**
     * @param string $resolver_class
     * @param string $recipient_class
     *
     * @return notification_preference_model
     */
    private function create_notification_preference(string $resolver_class,
                                                    string $recipient_class): notification_preference_model {
        return $this->get_generator()->create_notification_preference(
            $resolver_class,
            extended_context::make_with_context(context_system::instance()),
            [
                'body' => "Notification preference body for [{$resolver_class},{$recipient_class}]",
                'subject' => 'Notification preference subject',
                'body_format' => FORMAT_MOODLE,
                'title' => 'Notification preference title',
                'recipient' => $recipient_class,
            ]
        );
    }
}