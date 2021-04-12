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

use totara_core\extended_context;
use totara_notification\placeholder\placeholder_option;
use totara_notification\resolver\abstraction\scheduled_event_resolver;
use totara_notification\resolver\notifiable_event_resolver;
use totara_notification\schedule\schedule_after_event;
use totara_notification\schedule\schedule_before_event;

global $CFG;

require_once("{$CFG->libdir}/dml/array_recordset.php");
require_once("{$CFG->libdir}/dml/moodle_recordset.php");

class totara_notification_mock_scheduled_aware_event_resolver extends notifiable_event_resolver
    implements scheduled_event_resolver {
    /**
     * The key name that we want to set for the event_data for the value of setted event time.
     * @var string
     */
    public const EVENT_TIME_KEY = 'event_time';

    /**
     * @var placeholder_option[]|null
     */
    private static $placeholder_options;

    /**
     * @var array
     */
    private static $events;

    /**
     * @var array
     */
    private static $schedule_classes;

    /**
     * @var bool|null
     */
    private static $has_associated_event;

    /**
     * @return int
     */
    public function get_fixed_event_time(): int {
        if (!isset($this->event_data[static::EVENT_TIME_KEY])) {
            throw new coding_exception("Cannot find the event time within the event data");
        }

        // We are letting the native php to validate the data type at this key.
        return $this->event_data[static::EVENT_TIME_KEY];
    }

    /**
     * @return string
     */
    public static function get_notification_title(): string {
        return 'Mock scheduled aware notifiable event';
    }

    /**
     * @return array
     */
    public static function get_notification_available_recipients(): array {
        global $CFG;
        require_once("{$CFG->dirroot}/totara/notification/tests/fixtures/totara_notification_mock_recipient.php");

        return [
            totara_notification_mock_recipient::class,
        ];
    }

    /**
     * @return array
     */
    public static function get_notification_available_schedules(): array {
        if (isset(static::$schedule_classes)) {
            return static::$schedule_classes;
        }

        // Default to only before and after event.
        return [
            schedule_before_event::class,
            schedule_after_event::class,
        ];
    }

    /**
     * @return array
     */
    public static function get_notification_default_delivery_channels(): array {
        return ['email', 'popup'];
    }

    /**
     * @return placeholder_option[]
     */
    public static function get_notification_available_placeholder_options(): array {
        if (!isset(self::$placeholder_options)) {
            self::$placeholder_options = [];
        }

        return self::$placeholder_options;
    }

    /**
     * @return void
     */
    public static function clear(): void {
        if (isset(static::$placeholder_options)) {
            static::$placeholder_options = [];
        }

        if (isset(static::$events)) {
            static::$events = [];
        }

        if (isset(static::$schedule_classes)) {
            static::$schedule_classes = [];
        }

        if (isset(static::$has_associated_event)) {
            static::$has_associated_event = null;
        }
    }

    /**
     * @param placeholder_option[] $options
     * @return void
     */
    public static function add_placeholder_options(placeholder_option ...$options): void {
        self::$placeholder_options = $options;
    }

    /**
     * @param int $min_time
     * @param int $max_time
     *
     * @return moodle_recordset
     */
    public static function get_scheduled_events(int $min_time, int $max_time): moodle_recordset {
        return new array_recordset(static::$events);
    }

    /**
     * @param array $events
     * @return void
     */
    public static function set_events(array $events): void {
        static::$events = $events;
    }

    /**
     * @param string ...$scheduled_classes
     * @return void
     */
    public static function set_scheduled_classes(string ...$scheduled_classes): void {
        static::$schedule_classes = $scheduled_classes;
    }

    /**
     * @return bool
     */
    public static function uses_on_event_queue(): bool {
        if (isset(static::$has_associated_event)) {
            return static::$has_associated_event;
        }

        return parent::uses_on_event_queue(); // TODO: Change the autogenerated stub
    }

    /**
     * @param bool $value
     * @return void
     */
    public static function set_associated_notifiable_event(bool $value): void {
        static::$has_associated_event = $value;
    }

    public function get_extended_context(): extended_context {
        return extended_context::make_with_context(context_system::instance());
    }
}