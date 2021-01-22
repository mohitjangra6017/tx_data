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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_notification
 */
use totara_comment\comment_helper;
use totara_notification\event\notifiable_event;
use core\event\base;

class totara_notification_notifiable_event_testcase extends advanced_testcase {
    /**
     * @return void
     */
    public function test_notifiable_event(): void {
        $generator = $this->getDataGenerator();
        $actor = $generator->create_user();

        /** @var totara_comment_generator $comment_generator */
        $comment_generator = $generator->get_plugin_generator('totara_comment');

        $context_user = context_user::instance($actor->id);
        $comment_generator->add_context_for_default_resolver($context_user);

        // Mock comment event.
        $this->setUser($actor);
        $event_sink = phpunit_util::start_event_redirection();

        $comment = comment_helper::create_comment(
            'totara_comment',
            'comment_view',
            42,
            'This is content',
            FORMAT_PLAIN,
            null,
            $actor->id
        );

        $events = $event_sink->get_events();
        self::assertCount(1, $events);

        // First event
        /** @var notifiable_event|base $event */
        $event = reset($events);

        self::assertInstanceOf(notifiable_event::class, $event);
        self::assertEquals(
            ['comment_id' => $comment->get_id()],
            $event->get_notification_event_data()
        );

        $event_context = $event->get_context();

        self::assertEquals($context_user->id, $event_context->id);
        self::assertEquals(CONTEXT_USER, $event_context->contextlevel);
    }
}