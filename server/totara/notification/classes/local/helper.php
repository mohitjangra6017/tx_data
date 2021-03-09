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
namespace totara_notification\local;

use coding_exception;
use totara_notification\event\notifiable_event;
use totara_notification\notification\built_in_notification;
use totara_notification\recipient\recipient;
use totara_notification\resolver\notifiable_event_resolver;
use totara_notification\resolver\resolver_helper;

class helper {
    /**
     * helper constructor.
     * Preventing this class from instantiation.
     */
    private function __construct() {
    }

    /**
     * @param string $event_class_name
     * @return bool
     */
    public static function is_valid_notifiable_event(string $event_class_name): bool {
        return is_a($event_class_name, notifiable_event::class, true);
    }

    /**
     * @param string $built_in_notification_class_name
     * @return bool
     */
    public static function is_valid_built_in_notification(string $built_in_notification_class_name): bool {
        if (!class_exists($built_in_notification_class_name)) {
            return false;
        }

        return is_subclass_of($built_in_notification_class_name, built_in_notification::class);
    }

    /**
     * @param string $resolver_class_name
     * @return array
     */
    public static function get_component_of_recipients(string $resolver_class_name): array {
        if (!resolver_helper::is_valid_event_resolver($resolver_class_name)) {
            throw new coding_exception("Resolver class is an invalid notifiable event resolver");
        }

        /**
         * @see notifiable_event_resolver::get_notification_available_recipients()
         * @var string[] $recipients
         */
        $recipients = call_user_func([$resolver_class_name, 'get_notification_available_recipients']);
        if (count($recipients) == 0) {
            throw new coding_exception("Class {$resolver_class_name} need to define recipient");
        }

        return $recipients;
    }

    /**
     * @param string $recipient_class
     * @return bool
     */
    public static function is_valid_recipient_class(string $recipient_class): bool {
        return is_a($recipient_class, recipient::class, true);
    }
}