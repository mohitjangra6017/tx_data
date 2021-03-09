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

use core_phpunit\testcase;
use totara_core\extended_context;
use totara_notification\testing\generator;
use totara_notification_mock_notifiable_event_resolver as mock_resolver;
use totara_notification_mock_recipient as mock_recipient;

class totara_notification_notification_preference_testcase extends testcase {
    /**
     * @return void
     */
    protected function setUp(): void {
        $generator = generator::instance();
        $generator->include_mock_recipient();
        $generator->include_mock_notifiable_event_resolver();
    }

    /**
     * @return void
     */
    public function test_check_is_before_event(): void {
        $generator = generator::instance();
        $extended_context = extended_context::make_system();

        $preference = $generator->create_notification_preference(
            mock_resolver::class,
            $extended_context,
            [
                'recipient' => mock_recipient::class,
                'schedule_offset' => -1,
            ]
        );

        self::assertFalse($preference->is_on_event());
    }

    /**
     * @return void
     */
    public function test_check_is_on_event(): void {
        $generator = generator::instance();
        $extended_context = extended_context::make_system();

        $preference = $generator->create_notification_preference(
            mock_resolver::class,
            $extended_context,
            [
                'recipient' => mock_recipient::class,
                'schedule_offset' => 0,
            ]
        );

        self::assertTrue($preference->is_on_event());
    }

    /**
     * @return void
     */
    public function test_check_is_after_event(): void {
        $generator = generator::instance();
        $extended_context = extended_context::make_system();

        $preference = $generator->create_notification_preference(
            mock_resolver::class,
            $extended_context,
            [
                'recipient' => mock_recipient::class,
                'schedule_offset' => 3,
            ]
        );

        self::assertFalse($preference->is_on_event());
    }
}