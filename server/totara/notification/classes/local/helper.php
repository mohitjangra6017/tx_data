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
use core_component;
use totara_notification\event\notifiable_event;
use totara_notification\notification\built_in_notification;
use totara_notification\resolver\notifiable_event_resolver;
use totara_notification_mock_notifiable_event;
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
     * @return string
     */
    public static function get_component_of_event_class_name(string $event_class_name): string {
        $event_class_name = ltrim($event_class_name, '\\');
        if (!self::is_valid_notifiable_event($event_class_name)) {
            throw new coding_exception('The event class name is not a notifiable event');
        }

        if (defined('PHPUNIT_TEST') && PHPUNIT_TEST) {
            $mock_class = 'totara_notification_mock_notifiable_event';
            if (class_exists($mock_class) && $mock_class === $event_class_name) {
                return 'totara_notification';
            }
        }

        $parts = explode('\\', $event_class_name);
        $component = reset($parts);

        $component = clean_param($component, PARAM_COMPONENT);
        $component_directory = null;

        if (!empty($component)) {
            // If it is a valid component within the system, its directory must had been
            // exist, and should be a valid dir path. Otherwise, its directory will not appear
            // from the result.
            $component_directory = core_component::get_component_directory($component);
        }

        if (empty($component) || empty($component_directory)) {
            throw new coding_exception("Cannot find the component from the event class name");
        }

        return $component;
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
            throw new coding_exception("Event class name is an invalid notifiable event");
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
        if (!class_exists($resolver_classname)) {
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

    /**
     * @param string $resolver_class_name
     * @return string
     */
    public static function get_notifiable_event_from_resolver(string $resolver_class_name): string {
        global $CFG;

        if (!is_subclass_of($resolver_class_name, notifiable_event_resolver::class)) {
            throw new coding_exception("The resolver class name is not a child of " . notifiable_event_resolver::class);
        }

        $resolver_class_name = ltrim($resolver_class_name, '\\');
        if (defined('PHPUNIT_TEST') && PHPUNIT_TEST) {
            // We are in test environment. Check that if the resolver class name is equal
            // to the mock event notifiable resolver or not.
            if ('totara_notification_mock_notifiable_event_resolver' === $resolver_class_name) {
                require_once(
                    "{$CFG->dirroot}/totara/notification/tests/fixtures/totara_notification_mock_notifiable_event.php"
                );

                return totara_notification_mock_notifiable_event::class;
            }
        }

        $parts = explode("\\", $resolver_class_name);
        $component = reset($parts);
        $event_name = end($parts);

        $notifiable_event_class_name = "{$component}\\event\\{$event_name}";
        if (!self::is_valid_notifiable_event($notifiable_event_class_name)) {
            throw new coding_exception(
                "Cannot find the resolver for notifiable event '{$resolver_class_name}'"
            );
        }

        return $notifiable_event_class_name;
    }

    /**
     * We are invoking the function {@see notifiable_event::get_notification_title()} to get
     * the human readable name
     *
     * @param string $event_class_name
     * @return string
     */
    public static function get_human_readable_event_name(string $event_class_name): string {
        if (!self::is_valid_notifiable_event($event_class_name)) {
            throw new coding_exception("Event class name is an invalid notifiable event");
        }

        return call_user_func([$event_class_name, 'get_notification_title']);
    }

    /**
     * @param string $event_class_name
     * @return string
     */
    public static function get_human_readable_plugin_name(string $event_class_name): string {
        $component = self::get_component_of_event_class_name($event_class_name);

        if (get_string_manager()->string_exists('pluginname', $component)) {
            $plugin_name = get_string('pluginname', $component);
        } else {
            // If component dose not define pluginname in langstring, we just fallback to the name of component, then
            // put debugging here to let dev know they need to define the pluginname for each plugin.
            $plugin_name = $component;
            debugging("pluginnanme need to be defined in langstring for the {$plugin_name}", DEBUG_DEVELOPER);
        }

        return $plugin_name;
    }
}