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
 * @author  Cody Finegan <cody.finegan@totaralearning.com>
 * @package totara_notification
 */

use totara_notification\local\schedule_helper;
use totara_notification\schedule\schedule_after_event;
use totara_notification\schedule\schedule_before_event;
use totara_notification\schedule\schedule_on_event;
use totara_notification_mock_notifiable_event as mock_notifiable_event;

class totara_notification_local_schedule_helper_testcase extends advanced_testcase {
    /**
     * @return void
     */
    public function test_get_available_schedules_for_event(): void {
        $expected = [
            'ON_EVENT',
            'BEFORE_EVENT',
            'AFTER_EVENT',
        ];

        $schedules = schedule_helper::get_available_schedules_for_event(mock_notifiable_event::class);
        self::assertSame($expected, $schedules);

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('Event class name is an invalid notifiable event');

        schedule_helper::get_available_schedules_for_event('invalid_event');
    }

    /**
     * @return void
     */
    public function test_get_schedule_class_from_offset(): void {
        $test_cases = [
            -5 => schedule_before_event::class,
            0 => schedule_on_event::class,
            10 => schedule_after_event::class,
        ];

        foreach ($test_cases as $offset => $expected) {
            self::assertSame($expected, schedule_helper::get_schedule_class_from_offset($offset));
        }
    }

    /**
     * @return void
     */
    public function test_get_schedule_class_from_type(): void {
        $test_cases = [
            'ON_EVENT' => schedule_on_event::class,
            'BEFORE_EVENT' => schedule_before_event::class,
            'AFTER_EVENT' => schedule_after_event::class,
        ];

        foreach ($test_cases as $type => $expected) {
            self::assertSame($expected, schedule_helper::get_schedule_class_from_type($type));
        }

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("Unknown schedule type of 'invalid_type' provided");

        schedule_helper::get_schedule_class_from_type('invalid_type');
    }

    /**
     * @return void
     */
    public function test_get_human_readable_schedule_label(): void {
        $test_cases = [
            -1 => get_string('schedule_label_before_event_singular', 'totara_notification', 1),
            -5 => get_string('schedule_label_before_event', 'totara_notification', 5),
            0 => get_string('schedule_label_on_event', 'totara_notification', 0),
            1 => get_string('schedule_label_after_event_singular', 'totara_notification', 1),
            5 => get_string('schedule_label_after_event', 'totara_notification', 5),
        ];

        foreach ($test_cases as $offset => $expected) {
            self::assertSame($expected, schedule_helper::get_human_readable_schedule_label($offset));
        }
    }

    /**
     * @return void
     */
    public function test_get_schedule_identifier(): void {
        $test_cases = [
            -5 => 'BEFORE_EVENT',
            0 => 'ON_EVENT',
            10 => 'AFTER_EVENT',
        ];

        foreach ($test_cases as $offset => $expected) {
            self::assertSame($expected, schedule_helper::get_schedule_identifier($offset));
        }
    }

    /**
     * @return void
     */
    public function test_get_schedule_offset(): void {
        $test_cases = [
            -5 => 5,
            0 => 0,
            5 => 5,
        ];

        foreach ($test_cases as $offset => $expected) {
            self::assertSame($expected, schedule_helper::get_schedule_offset($offset));
        }
    }

    /**
     * @return void
     */
    public function test_calculate_schedule_timestamp(): void {
        $base_timestamp = 1614732640;

        $test_cases = [
            -5 => 1614300640,
            0 => 1614732640,
            5 => 1615164640,
        ];

        foreach ($test_cases as $offset => $expected) {
            self::assertSame($expected, schedule_helper::calculate_schedule_timestamp($base_timestamp, $offset));
        }
    }

    /**
     * @return void
     */
    public function test_convert_schedule_offset_for_storage(): void {
        $test_cases = [
            -5 => ['BEFORE_EVENT', 5],
            0 => ['ON_EVENT', 0],
            5 => ['AFTER_EVENT', 5],
        ];

        foreach ($test_cases as $expected => $test_data) {
            self::assertSame($expected, schedule_helper::convert_schedule_offset_for_storage(...$test_data));
        }
    }

    /**
     * @return void
     */
    protected function setUp(): void {
        global $CFG;
        require_once("{$CFG->dirroot}/totara/notification/tests/fixtures/totara_notification_mock_notifiable_event.php");
    }
}