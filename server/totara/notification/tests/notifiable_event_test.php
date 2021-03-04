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
 * @author  Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_notification
 */

use core\event\base;
use totara_comment\comment_helper;
use totara_comment\testing\generator as comment_generator;
use totara_notification\testing\generator as notification_generator;
use totara_notification\event\notifiable_event;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_notification_notifiable_event_testcase extends advanced_testcase {

    use webapi_phpunit_helper;

    /**
     * @return void
     */
    public function test_notifiable_event(): void {
        $generator = $this->getDataGenerator();
        $actor = $generator->create_user();

        /** @var comment_generator $comment_generator */
        $comment_generator = $generator->get_plugin_generator('totara_comment');

        $context_user = context_user::instance($actor->id);
        $comment_generator->add_context_for_default_resolver($context_user);

        // Mock comment event.
        $this->setUser($actor);
        $event_sink = $this->redirectEvents();

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

    /**
     * @return void
     */
    public function test_notifiable_event_with_empty_recipients(): void {
        /** @var notification_generator $generator */
        $generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $generator->include_mock_notifiable_event();

        // Set recipients to an empty array.
        totara_notification_mock_notifiable_event::set_notification_available_recipients([]);

        // Run the mutation.
        try {
            $this->resolve_graphql_type(
                'totara_notification_notifiable_event',
                'recipients',
                totara_notification_mock_notifiable_event::class
            );
            $this->fail('Exception is expected but not thrown');
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), 'Coding error detected, it must be fixed by a programmer: totara_notification_mock_notifiable_event need to define recipient');
        }
    }
}