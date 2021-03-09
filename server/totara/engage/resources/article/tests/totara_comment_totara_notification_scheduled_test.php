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

use core_phpunit\testcase;
use engage_article\testing\generator as article_generator;
use engage_article\totara_engage\resource\article;
use totara_comment\comment;
use totara_comment\entity\comment as entity;
use totara_comment\testing\generator as comment_generator;
use totara_comment\totara_notification\recipient\owner;
use totara_comment\totara_notification\resolver\comment_created;
use totara_comment_mock_comment_created_notification as mock_notification;
use totara_core\extended_context;
use totara_engage\access\access;
use totara_notification\entity\notifiable_event_queue;
use totara_notification\entity\notification_queue;
use totara_notification\local\schedule_helper;
use totara_notification\manager\event_queue_manager;
use totara_notification\manager\scheduled_event_manager;
use totara_notification\schedule\schedule_after_event;
use totara_notification\testing\generator as notification_generator;

class engage_article_totara_comment_totara_notification_scheduled_testcase extends testcase {
    /**
     * @param int      $article_id
     * @param int      $user_id
     * @param int|null $time_created
     *
     * @return comment
     */
    private function create_comment(int $article_id, int $user_id, ?int $time_created = null): comment {
        global $DB;

        /** @var comment_generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_comment');

        // At this point, the event should be disabled, hence we can be sured that no notification to be queue up.
        $comment = $generator->create_comment(
            $article_id,
            'engage_article',
            article::COMMENT_AREA,
            'This is content',
            FORMAT_PLAIN,
            $user_id
        );

        if (!empty($time_created)) {
            $record = new stdClass();
            $record->id = $comment->get_id();
            $record->timecreated = $time_created;

            $DB->update_record(entity::TABLE, $record);
            $comment = comment:: from_id($record->id);
        }

        return $comment;
    }

    /**
     * @return stdClass
     */
    private function create_user(): stdClass {
        $generator = self::getDataGenerator();
        return $generator->create_user();
    }

    /**
     * @param int $user_id
     * @return article
     */
    private function create_article(int $user_id): article {
        /** @var article_generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('engage_article');

        // Default access to public so that everyone can comment on it.
        return $generator->create_article(
            [
                'userid' => $user_id,
                'access' => access::PUBLIC,
            ]
        );
    }

    /**
     * @return void
     */
    protected function setUp(): void {
        global $CFG;
        require_once("{$CFG->dirroot}/totara/comment/tests/fixtures/totara_comment_mock_comment_created_notification.php");

        /** @var notification_generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $generator->purge_built_in_notifications();
    }

    /**
     * @return void
     */
    public function test_one_notifiable_event_with_no_built_in_or_custom_notification_preferences(): void {
        global $DB;

        $user_one = $this->create_user();
        $user_two = $this->create_user();

        $article = $this->create_article($user_one->id);
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));

        $this->create_comment($article->get_id(), $user_two->id);

        // There should be one event queues but no notification queue despite of processing the event
        // as all the built int notifications are not yet existing
        self::assertEquals(1, $DB->count_records(notifiable_event_queue::TABLE));

        $event_manager = new event_queue_manager();
        $event_manager->process_queues();

        // There are no preferences to queue up.
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $scheduled_manager = new scheduled_event_manager();
        $scheduled_manager->execute(time(), time());

        // There are no preferences to queue up.
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));
    }

    /**
     * @return void
     */
    public function test_one_event_that_support_on_after_without_built_in_preference(): void {
        global $DB;

        /** @var notification_generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');

        $user_one = $this->create_user();
        $user_two = $this->create_user();

        $article = $this->create_article($user_one->id);
        $extended_context = extended_context::make_with_context($article->get_context());

        $custom_one = $generator->create_notification_preference(
            comment_created::class,
            $extended_context,
            [
                'recipient' => owner::class,
                'schedule_offset' => schedule_after_event::default_value(10),
            ]
        );

        $custom_two = $generator->create_notification_preference(
            comment_created::class,
            $extended_context,
            [
                'recipient' => owner::class,
                'schedule_offset' => schedule_after_event::default_value(5),
            ]
        );

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        $now = time();

        // Create two comments with one that happened 6 days ago and one happened in 11 days ago.
        $this->create_comment($article->get_id(), $user_two->id, $now - schedule_helper::days_to_seconds(6));
        $this->create_comment($article->get_id(), $user_two->id, $now - schedule_helper::days_to_seconds(11));

        // There should be 2 event queued but no notification queue despite of processing the event
        // as all the built int notifications are not yet existing
        self::assertEquals(2, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $event_manager = new event_queue_manager();
        $event_manager->process_queues();

        // There are no preferences to queue up, because there are none on_event preferences
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $scheduled_manager = new scheduled_event_manager();
        $scheduled_manager->execute(
            $now,
            $now - schedule_helper::days_to_seconds(2)
        );

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(2, $DB->count_records(notification_queue::TABLE));

        self::assertEquals(
            1,
            $DB->count_records(
                notification_queue::TABLE,
                ['notification_preference_id' => $custom_one->get_id()]
            )
        );

        self::assertEquals(
            1,
            $DB->count_records(
                notification_queue::TABLE,
                ['notification_preference_id' => $custom_two->get_id()]
            )
        );
    }

    /**
     * @return void
     */
    public function test_mixed_built_in_and_custom_notification(): void {
        global $DB;

        /** @var notification_generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        mock_notification::set_default_schedule_offset(schedule_after_event::default_value(10));

        $built_in = $generator->add_mock_built_in_notification_for_component(
            mock_notification::class,
            'totara_comment'
        );

        $custom_preference = $generator->create_notification_preference(
            comment_created::class,
            extended_context::make_system(),
            [
                'recipient' => owner::class,
                'schedule_offset' => schedule_after_event::default_value(5),
            ]
        );

        $user_one = $this->create_user();
        $user_two = $this->create_user();

        $article = $this->create_article($user_one->id);
        $now = time();

        // Create two comments with one that happened 6 days ago and one happened in 11 days ago.
        $this->create_comment($article->get_id(), $user_two->id, $now - schedule_helper::days_to_seconds(6));
        $this->create_comment($article->get_id(), $user_two->id, $now - schedule_helper::days_to_seconds(11));

        // There should be 2 event queued but no notification queue despite of processing the event
        // as all the built int notifications are not yet existing
        self::assertEquals(2, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $event_manager = new event_queue_manager();
        $event_manager->process_queues();

        // There are no preferences to queue up, because there are none on_event preferences
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $scheduled_manager = new scheduled_event_manager();
        $scheduled_manager->execute(
            $now,
            $now - schedule_helper::days_to_seconds(2)
        );

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(2, $DB->count_records(notification_queue::TABLE));

        self::assertEquals(
            1,
            $DB->count_records(
                notification_queue::TABLE,
                ['notification_preference_id' => $built_in->get_id()]
            )
        );

        self::assertEquals(
            1,
            $DB->count_records(
                notification_queue::TABLE,
                ['notification_preference_id' => $custom_preference->get_id()]
            )
        );
    }

    /**
     * @return void
     */
    public function test_mixed_custom_notification_and_built_in(): void {
        global $DB;

        /** @var notification_generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        mock_notification::set_default_schedule_offset(schedule_after_event::default_value(5));

        $built_in = $generator->add_mock_built_in_notification_for_component(
            mock_notification::class,
            'totara_comment'
        );

        $custom_preference = $generator->create_notification_preference(
            comment_created::class,
            extended_context::make_system(),
            [
                'recipient' => owner::class,
                'schedule_offset' => schedule_after_event::default_value(10),
            ]
        );

        $user_one = $this->create_user();
        $user_two = $this->create_user();

        $article = $this->create_article($user_one->id);
        $now = time();

        // Create two comments with one that happened 6 days ago and one happened in 11 days ago.
        $this->create_comment($article->get_id(), $user_two->id, $now - schedule_helper::days_to_seconds(6));
        $this->create_comment($article->get_id(), $user_two->id, $now - schedule_helper::days_to_seconds(11));

        // There should be 2 event queued but no notification queue despite of processing the event
        // as all the built int notifications are not yet existing
        self::assertEquals(2, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $event_manager = new event_queue_manager();
        $event_manager->process_queues();

        // There are no preferences to queue up, because there are none on_event preferences
        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(0, $DB->count_records(notification_queue::TABLE));

        $scheduled_manager = new scheduled_event_manager();
        $scheduled_manager->execute(
            $now,
            $now - schedule_helper::days_to_seconds(2)
        );

        self::assertEquals(0, $DB->count_records(notifiable_event_queue::TABLE));
        self::assertEquals(2, $DB->count_records(notification_queue::TABLE));

        self::assertEquals(
            1,
            $DB->count_records(
                notification_queue::TABLE,
                ['notification_preference_id' => $built_in->get_id()]
            )
        );

        self::assertEquals(
            1,
            $DB->count_records(
                notification_queue::TABLE,
                ['notification_preference_id' => $custom_preference->get_id()]
            )
        );
    }
}