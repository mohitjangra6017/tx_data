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
use totara_comment\event\comment_created;
use totara_comment\resolver_factory;
use totara_notification\observer\notifiable_event_observer;

defined('MOODLE_INTERNAL') || die();

class totara_notification_notifiable_event_testcase extends advanced_testcase {

    public function test_notifiable_event(): void {
        $generator = $this->getDataGenerator();

        $actor = $generator->create_user();
        $user = $generator->create_user();

        // Mock comment event.
        $this->setUser($user);

        $comment = comment_helper::create_comment(
            'totara_comment',
            'comment_view',
            42,
            'This is content',
            FORMAT_PLAIN,
            null,
            $actor->id
        );

        $resolver = resolver_factory::create_resolver('totara_comment');
        $context_id = $resolver->get_context_id(42, 'comment_view');
        $context = context::instance_by_id($context_id);

        (comment_created::from_comment($comment, $context))->trigger();

        $event = notifiable_event_observer::get_event();
        self::assertNotEmpty($event);
        self::assertNotNull($event);
    }
}