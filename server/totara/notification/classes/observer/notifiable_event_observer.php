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
namespace totara_notification\observer;

use coding_exception;
use totara_core\event\notifiable_event;

/**
 * Event observer for notifiable event interface.
 */
final class notifiable_event_observer {
    /**
     * @var notifiable_event
     */
    private static $event;

    /**
     * notification_observer constructor.
     */
    private function __construct() {
    }

    /**
     * @param notifiable_event $event
     */
    public static function watch_notifiable_event(notifiable_event $event): void {
        if (!$event instanceof notifiable_event) {
            debugging('The Instance is not from notifiable_event');
        }

        if (defined('PHPUNIT_TEST') && PHPUNIT_TEST) {
            self::set_event($event);
        }

        // TODO put data into queue.
    }

    /**
     * @return notifiable_event
     */
    public static function get_event(): notifiable_event {
        if (defined('PHPUNIT_TEST') && PHPUNIT_TEST) {
            return self::$event;
        }

        throw new coding_exception('get_event() is only used in PHPUNIT test');
    }

    /**
     * @param notifiable_event $event
     */
    private static function set_event(notifiable_event $event) {
        if (defined('PHPUNIT_TEST') && PHPUNIT_TEST) {
            self::$event = $event;
            return;
        }

        throw new coding_exception('set_event() is only used in PHPUNIT test');
    }
}