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

use totara_notification\model\notification_event_data;
use totara_notification\resolver\abstraction\scheduled_event_resolver;
use totara_notification\resolver\notifiable_event_resolver;
use totara_notification\schedule\schedule_after_event;
use totara_notification\schedule\schedule_before_event;
use totara_notification\schedule\schedule_on_event;

class totara_notification_mock_scheduled_event_with_on_event_resolver extends notifiable_event_resolver
    implements scheduled_event_resolver {

    /**
     * @var string
     */
    public const EVENT_TIME_KEY = 'event_time_key';

    /**
     * @var notification_event_data[]|null
     */
    private static $events;

    /**
     * @return string
     */
    public static function get_notification_title(): string {
        return 'Mock event with on_event time';
    }

    /**
     * @return int|null
     */
    public function get_fixed_event_time(): ?int {
        return $this->event_data[static::EVENT_TIME_KEY] ?? null;
    }

    /**
     * @return string[]
     */
    public static function get_notification_available_recipients(): array {
        global $CFG;
        require_once(
            "{$CFG->dirroot}/totara/notification/tests/fixtures/totara_notification_mock_recipient.php"
        );

        return [
            totara_notification_mock_recipient::class,
        ];
    }

    /**
     * @return array
     */
    public static function get_notification_default_delivery_channels(): array {
        return [];
    }

    /**
     * @return array
     */
    public static function get_notification_available_placeholder_options(): array {
        return [];
    }

    /**
     * @param int $min_time
     * @param int $max_time
     *
     * @return notification_event_data[]
     */
    public static function get_scheduled_events(int $min_time, int $max_time): array {
        if (!isset(static::$events)) {
            return [];
        }

        return static::$events;
    }

    /**
     * @param notification_event_data ...$events
     * @return void
     */
    public static function set_events(notification_event_data ...$events): void {
        static::$events = $events;
    }

    /**
     * @return void
     */
    public static function clear(): void {
        if (isset(static::$events)) {
            static::$events = [];
        }
    }

    /**
     * @return string[]
     */
    public static function get_notification_available_schedules(): array {
        return [
            schedule_on_event::class,
            schedule_before_event::class,
            schedule_after_event::class,
        ];
    }
}