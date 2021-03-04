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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
use totara_notification\event\notifiable_event;
use totara_notification\placeholder\placeholder_option;
use totara_notification\schedule\schedule_after_event;
use totara_notification\schedule\schedule_before_event;
use totara_notification\schedule\schedule_on_event;

class totara_notification_mock_notifiable_event implements notifiable_event {
    /**
     * @var int
     */
    private $context_id;

    /**
     * @var array
     */
    private $event_data;

    /**
     * @var array
     */
    private static $placeholder_options;

    /**
     * @var int
     */
    public $timecreated;

    /**
     * @var array
     */
    private static $available_recipients = null;

    /**
     * totara_notification_mock_notifiable_event constructor.
     *
     * @param int $context_id
     * @param array $mock_event_data
     * @param int|null $time_created
     */
    public function __construct(int $context_id, array $mock_event_data = [], ?int $time_created = null) {
        $this->context_id = $context_id;
        $this->event_data = $mock_event_data;
        $this->timecreated = $time_created ?? time();
    }

    /**
     * @return string
     */
    public static function get_notification_title(): string {
        return 'Mock notifiable event';
    }

    /**
     * @return array
     */
    public static function get_notification_available_recipients(): array {
        // Return set available recipients.
        if (!is_null(static::$available_recipients)) {
            return static::$available_recipients;
        }

        // Return default available recipients.
        return [
            totara_notification_mock_recipient::class
        ];
    }

    public static function set_notification_available_recipients(array $available_recipients) {
        static::$available_recipients = $available_recipients;
    }

    /**
     * @return array
     */
    public static function get_notification_available_schedules(): array {
        return [
            schedule_on_event::class,
            schedule_before_event::class,
            schedule_after_event::class,
        ];
    }

    /**
     * @return array
     */
    public static function get_notification_default_delivery_channels(): array {
        return [];
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
     * @return array
     */
    public function get_notification_event_data(): array {
        return $this->event_data;
    }

    /**
     * @return context
     */
    public function get_context() {
        return context::instance_by_id($this->context_id);
    }

    /**
     * @param placeholder_option[] $options
     * @return void
     */
    public static function add_placeholder_options(placeholder_option ...$options): void {
        self::$placeholder_options = $options;
    }

    /**
     * @return void
     */
    public static function clear(): void {
        if (isset(self::$placeholder_options)) {
            self::$placeholder_options = [];
        }
    }
}