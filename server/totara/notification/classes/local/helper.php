<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
use totara_notification\resolver\notifiable_event_resolver;
use totara_notification_mock_notifiable_event_resolver;

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
        if (!class_exists($event_class_name)) {
            return false;
        }

        $interfaces = class_implements($event_class_name);

        if (!is_array($interfaces)) {
            // More likely the event class name does not exist in the system.
            return false;
        }

        return in_array(notifiable_event::class, $interfaces);
    }

    /**
     * @param string $event_class_name
     * @param int    $context_id
     * @param array  $event_data
     *
     * @return notifiable_event_resolver
     */
    public static function get_resolver_from_notifiable_event(string $event_class_name,
                                                              int $context_id, array $event_data): notifiable_event_resolver {
        global $CFG;
        if (!helper::is_valid_notifiable_event($event_class_name)) {
            throw new coding_exception("Cannot get the event notifiable resolver");
        }

        $event_class_name = ltrim($event_class_name, '\\');

        if (defined('PHPUNIT_TEST') && PHPUNIT_TEST) {
            // We are in test environment. Check that if the event class name is equal
            // to the mock event notifiable event or not.
            if ('totara_notification_mock_notifiable_event' === $event_class_name) {
                require_once(
                    "{$CFG->dirroot}/totara/notification/tests/fixtures/totara_notification_mock_notifiable_event_resolver.php"
                );

                return new totara_notification_mock_notifiable_event_resolver($context_id, $event_data);
            }
        }

        $parts = explode("\\", $event_class_name);

        $component = reset($parts);
        $resolver_name = end($parts);

        $resolver_classname = "{$component}\\totara_notification\\resolver\\{$resolver_name}";
        if (!class_exists($resolver_name)) {
            throw new coding_exception(
                "Cannot find the resolver for notifiable event '{$event_class_name}'"
            );
        }

        /**
         * This is metadata programming, which we are going to invoke the construction of
         * {@see notifiable_event_resolver::__construct()}
         */
        return new $resolver_classname($context_id, $event_data);
    }
}