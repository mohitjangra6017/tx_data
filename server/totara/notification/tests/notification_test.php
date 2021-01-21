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

defined('MOODLE_INTERNAL') || die();

use totara_notification\api\notification_api;
use totara_notification\notification\notification_factory;

class totara_notification_notification_testcase extends advanced_testcase {

    private $comment_notifications;

    public function setUp(): void {
        parent::setUp();
        $this->comment_notifications = notification_factory::create_notifications_by_component('totara_comment');
    }

    public function tearDown(): void {
        $this->comment_notifications = null;
        parent::tearDown();
    }

    public function test_get_notifications(): void {
        $notifications = notification_api::get_notifications();

        self::assertNotEmpty($notifications);
        self::assertCount(1, $notifications);
        $notification = reset($notifications);

        self::assertCount(1, $notifications);
        $comment_notification = reset($this->comment_notifications);
        self::assertEquals($comment_notification, $notification);
    }

    public function test_notification(): void {
        $notification = reset($this->comment_notifications);
        $notification->set_body('body');
        $notification->set_subject('subject');

        self::assertEquals('body', $notification->get_body());
        self::assertEquals('subject', $notification->get_subject());
    }
}