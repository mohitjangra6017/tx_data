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
use totara_notification\resolver\notifiable_event_resolver;

class totara_notification_mock_notifiable_event_resolver extends notifiable_event_resolver {
    /**
     * @var Closure|null
     */
    private static $recipient_ids_resolver;

    /**
     * @var array|null
     */
    private static $available_recipients;

    /**
     * @var array|null
     */
    private static $placeholder_options;

    /**
     * @param callable $recipient_ids_resolver
     * @return void
     */
    public static function set_recipient_ids_resolver(callable $recipient_ids_resolver): void {
        if (!isset(self::$recipient_ids_resolver)) {
            // PHP-7.4 compatible - do not ask.
            self::$recipient_ids_resolver = null;
        }

        self::$recipient_ids_resolver = Closure::fromCallable($recipient_ids_resolver);
    }

    /**
     * @return void
     */
    public static function clear(): void {
        if (isset(self::$recipient_ids_resolver)) {
            self::$recipient_ids_resolver = null;
        }

        if (isset(self::$available_recipients)) {
            self::$available_recipients = [];
        }

        if (isset(self::$placeholder_options)) {
            self::$placeholder_options = [];
        }
    }

    /**
     * @param string $recipient_name
     * @return array
     */
    public function get_recipient_ids(string $recipient_name): array {
        if (!isset(self::$recipient_ids_resolver)) {
            return [];
        }

        // Let the native php handle the miss-matched type returned from callback - i'm tired.
        return self::$recipient_ids_resolver->__invoke();
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
            totara_notification_mock_recipient::class,
        ];
    }

    /**
     * @param string[] $available_recipients
     * @return void
     */
    public static function set_notification_available_recipients(array $available_recipients): void {
        static::$available_recipients = $available_recipients;
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
     * @param placeholder_option[] $options
     * @return void
     */
    public static function add_placeholder_options(placeholder_option ...$options): void {
        self::$placeholder_options = $options;
    }

    public function get_extended_context(): extended_context {
        return extended_context::make_with_id($this->event_data['expected_context_id']);
    }
}