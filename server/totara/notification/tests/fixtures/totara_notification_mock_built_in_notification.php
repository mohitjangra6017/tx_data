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
use totara_notification\notification\built_in_notification;

class totara_notification_mock_built_in_notification extends built_in_notification {
    /**
     * @var lang_string|null
     */
    private static $body;

    /**
     * @var lang_string|null
     */
    private static $subject;

    /**
     * @return string
     */
    public static function get_event_class_name(): string {
        global $CFG;
        $event_class = totara_notification_mock_notifiable_event::class;

        if (!class_exists($event_class)) {
            require_once("{$CFG->dirroot}/totara/notification/tests/fixtures/totara_notification_mock_notifiable_event.php");
        }

        return totara_notification_mock_notifiable_event::class;
    }

    /**
     * @return string
     */
    public static function get_title(): string {
        return 'Mock built in notification';
    }

    /**
     * @return string
     */
    public static function get_recipient_name(): string {
        return 'martin_garrix';
    }

    /**
     * @return lang_string
     */
    public static function get_default_body(): lang_string {
        if (isset(self::$body)) {
            return self::$body;
        }

        // I could not be bothered to create a new lang_string.
        return new lang_string('pluginname', 'totara_notification');
    }

    /**
     * @return lang_string
     */
    public static function get_default_subject(): lang_string {
        if (isset(self::$subject)) {
            return self::$subject;
        }

        // I could not be bothered to create a new lang_string.
        return new lang_string('pluginname', 'totara_notification');
    }

    /**
     * @param lang_string $mock_body
     * @return void
     */
    public static function set_default_body(lang_string $mock_body): void {
        self::$body = $mock_body;
    }

    /**
     * @param lang_string $mock_subject
     * @return void
     */
    public static function set_default_subject(lang_string $mock_subject): void {
        self::$subject = $mock_subject;
    }

    /**
     * @return void
     */
    public static function clear(): void {
        if (isset(self::$body)) {
            self::$body = null;
        }

        if (isset(self::$subject)) {
            self::$subject = null;
        }
    }
}