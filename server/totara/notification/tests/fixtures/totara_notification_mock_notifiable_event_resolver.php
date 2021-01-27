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
use totara_notification\resolver\notifiable_event_resolver;

class totara_notification_mock_notifiable_event_resolver extends notifiable_event_resolver {
    /**
     * @var Closure|null
     */
    private static $recipient_ids_resolver;

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
    public static function clear_callbacks(): void {
        if (isset(self::$recipient_ids_resolver)) {
            self::$recipient_ids_resolver = null;
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
}