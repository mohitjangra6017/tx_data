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
 * @author Johannes Cilliers <johannes.cilliers@totaralearning.com>
 * @package container_workspace
 */

use container_workspace\discussion\discussion;
use container_workspace\discussion\discussion_helper;
use container_workspace\member\member;
use container_workspace\workspace;
use core\json_editor\node\paragraph;
use totara_comment\comment_helper;
use totara_comment\event\comment_created;
use totara_comment\totara_notification\recipient\owner;
use totara_core\extended_context;
use totara_notification\model\notification_preference as notification_preference_model;
use totara_notification\task\process_event_queue_task;
use totara_notification\task\process_notification_queue_task;
use container_workspace\testing\generator as workspace_generator;
use totara_notification\testing\generator as notification_generator;

class container_workspace_notification_recipient_testcase extends advanced_testcase {

    /**
     * @return void
     */
    public function test_discussion_owner_recipient(): void {
        // Create notification preference with owner recipient that will resolve
        // to user one's ID.
        /** @var notification_preference_model $notification_preference */
        $this->create_notification_preference(
            comment_created::class,
            owner::class
        );

        $generator = $this->getDataGenerator();
        $user_one = $generator->create_user();
        $user_two = $generator->create_user();
        $user_three = $generator->create_user();

        // Set user one as active user.
        $this->setUser($user_one);

        // Create workspace.
        /** @var workspace_generator $workspace_generator */
        $workspace_generator = $generator->get_plugin_generator('container_workspace');
        $workspace = $workspace_generator->create_workspace();

        // Set user two as active user.
        $this->setUser($user_two);
        member::join_workspace($workspace, $user_two->id);

        // Create discussion.
        $discussion = discussion_helper::create_discussion(
            $workspace,
            json_encode([
                'type' => 'doc',
                'content' => [paragraph::create_json_node_from_text("This is the discussion")],
            ]),
            null,
            FORMAT_JSON_EDITOR
        );

        // Create comment as user_three in discussion.
        member::join_workspace($workspace, $user_three->id);
        comment_helper::create_comment(
            workspace::get_type(),
            discussion::AREA,
            $discussion->get_id(),
            'This is content',
            FORMAT_PLAIN,
            null,
            $user_three->id
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
                $user_two->id
            ),
            'Notification message not found for user'
        );

        $this->assertEquals(
            true,
            $this->user_message_exists(
                $messages,
                'Notification preference body for [' . comment_created::class . ',' . owner::class . ']',
                $user_two->id
            ),
            'Notification message not found for user'
        );
    }

    /**
     * @param array $messages
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

    private function get_generator() {
        /** @var generator $generator */
        return $this->getDataGenerator()->get_plugin_generator('totara_notification');
    }

    /**
     * @param string $event_class
     * @param string $recipient_class
     *
     * @return mixed
     */
    private function create_notification_preference(string $event_class, string $recipient_class) {
        return $this->get_generator()->create_notification_preference(
            $event_class,
            extended_context::make_with_context(context_system::instance()),
            [
                'body' => "Notification preference body for [{$event_class},{$recipient_class}]",
                'subject' => 'Notification preference subject',
                'body_format' => FORMAT_MOODLE,
                'title' => 'Notification preference title',
                'recipient' => $recipient_class
            ]
        );
    }
}