<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
use totara_notification\local\helper;
use totara_notification\resolver\notifiable_event_resolver;

class totara_notification_local_helper_testcase extends advanced_testcase {
    /**
     * @return void
     */
    protected function setUp(): void {
        global $CFG;
        require_once("{$CFG->dirroot}/totara/notification/tests/fixtures/totara_notification_mock_notifiable_event.php");
    }

    /**
     * @return void
     */
    public function test_check_valid_notifiable_event_with_non_existing_class(): void {
        self::assertFalse(helper::is_valid_notifiable_event('kboom'));
    }

    /**
     * @return void
     */
    public function test_check_valid_notifiable_event_with_existing_class(): void {
        self::assertTrue(helper::is_valid_notifiable_event(totara_notification_mock_notifiable_event::class));
    }

    /**
     * @return void
     */
    public function test_get_resolver_from_invalid_event(): void {
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('Cannot get the event notifiable resolver');

        $context = context_system::instance();
        helper::get_resolver_from_notifiable_event('hello_world', $context->id, []);
    }

    /**
     * @return void
     */
    public function test_phpunit_get_resolver_from_valid_event(): void {
        $context = context_system::instance();

        $resolver = helper::get_resolver_from_notifiable_event(
            totara_notification_mock_notifiable_event::class,
            $context->id,
            ['user_data' => false]
        );

        self::assertInstanceOf(notifiable_event_resolver::class, $resolver);
    }
}