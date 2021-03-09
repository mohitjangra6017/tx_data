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
 * @package engage_article
 */

use engage_article\testing\generator as article_generator;
use totara_comment\totara_notification\resolver\comment_created;
use totara_comment\testing\generator as comment_generator;
use totara_comment\totara_notification\notification\comment_created_notification;
use totara_comment\totara_notification\recipient\owner;
use totara_engage\access\access;
use totara_notification\entity\notifiable_event_queue;
use totara_notification\entity\notification_queue;
use totara_notification\loader\notification_preference_loader;
use totara_notification\local\schedule_helper;
use totara_notification\manager\event_queue_manager;
use totara_notification\manager\notification_queue_manager;
use totara_notification\manager\scheduled_event_manager;
use totara_notification\schedule\schedule_after_event;
use totara_notification\testing\generator as notification_generator;
use core_phpunit\testcase;
use totara_core\extended_context;

class engage_article_totara_comment_totara_notification_scheduled_on_created_testcase extends testcase {
    /**
     * @return void
     */
    public function test_sending_notification_on_created_time(): void {
        global $DB;

        $generator = self::getDataGenerator();

        $user_one = $generator->create_user();
        $user_two = $generator->create_user();

        /** @var article_generator $article_generator */
        $article_generator = $generator->get_plugin_generator('engage_article');
        $article = $article_generator->create_article([
            'userid' => $user_one->id,
            'access' => access::PUBLIC,
        ]);

        // Create a custom notification preference that are set to 3 days after the event.
        $notification_generator = notification_generator::instance();
        $custom_preference = $notification_generator->create_notification_preference(
            comment_created::class,
            extended_context::make_system(),
            [
                'schedule_offset' => schedule_after_event::default_value(3),
                'recipient' => owner::class,
                'body' => 'Boom !!',
                'subject' => 'BOOM !!',
                'body_format' => FORMAT_PLAIN,
                'subject_format' => FORMAT_PLAIN,
            ]
        );

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        /** @var comment_generator $comment_generator */
        $comment_generator = $generator->get_plugin_generator('totara_comment');
        $comment_generator->create_comment(
            $article->get_id(),
            'engage_article',
            $article::COMMENT_AREA,
            'This is a comment',
            FORMAT_PLAIN,
            $user_two->id
        );

        self::assertEquals(1, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $event_manager = new event_queue_manager();
        $event_manager->process_queues();

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(1, $DB->count_records(notification_queue::TABLE));

        self::assertFalse(
            $DB->record_exists(
                notification_queue::TABLE,
                ['notification_preference_id' => $custom_preference->get_id()]
            )
        );

        // Only on event had been created.
        $built_in_preference = notification_preference_loader::get_built_in(comment_created_notification::class);
        self::assertTrue(
            $DB->record_exists(
                notification_queue::TABLE,
                ['notification_preference_id' => $built_in_preference->get_id()]
            )
        );

        $sink = $this->redirectMessages();
        self::assertEquals(0, $sink->count());
        self::assertEmpty($sink->get_messages());

        $now = time();
        // Now we move the time now to 3 days from now and run the manager.
        $scheduled_manager = new scheduled_event_manager();
        $scheduled_manager->execute(
            $now + schedule_helper::days_to_seconds(3) + HOURSECS,
            $now
        );

        // Check that there is another notification created for after event.
        self::assertEquals(2, $DB->count_records(notification_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));

        self::assertTrue(
            $DB->record_exists(
                notification_queue::TABLE,
                ['notification_preference_id' => $custom_preference->get_id()]
            )
        );

        self::assertTrue(
            $DB->record_exists(
                notification_queue::TABLE,
                ['notification_preference_id' => $built_in_preference->get_id()]
            )
        );

        self::assertEquals(0, $sink->count());
        self::assertEmpty($sink->get_messages());

        $notification_manager = new notification_queue_manager();
        $notification_manager->dispatch_queues($now + 4);

        self::assertEquals(1, $sink->count());
        self::assertNotEmpty($sink->get_messages());

        $notification_manager->dispatch_queues($now + schedule_helper::days_to_seconds(3));
        self::assertEquals(2, $sink->count());
    }
}