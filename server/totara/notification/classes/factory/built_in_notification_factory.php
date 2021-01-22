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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_notification
 */
namespace totara_notification\factory;

use coding_exception;
use core_component;
use totara_notification\event\notifiable_event;
use totara_notification\local\helper;
use totara_notification\notification\built_in_notification;

/**
 * Factory class to create the customized notification based on the component.
 */
final class built_in_notification_factory {
    /**
     * An array of all the class names that extend the class {@see built_in_notification}
     * A hash map of component's name and a list of notification within that classes.
     *
     * @var array
     */
    private static $built_in_notification_classes;

    /**
     * notification_factory constructor.
     */
    private function __construct() {
    }

    /**
     * Build the cache map for the list of notification classes and return it.
     * @return array
     */
    private static function get_map(): array {
        if (!isset(self::$built_in_notification_classes)) {
            self::$built_in_notification_classes = [];
        }

        if (empty(self::$built_in_notification_classes)) {
            $plugin_types = core_component::get_plugin_types();
            $plugin_types = array_keys($plugin_types);

            foreach ($plugin_types as $plugin_type) {
                $plugin_names = core_component::get_plugin_list($plugin_type);
                $plugin_names = array_keys($plugin_names);

                foreach ($plugin_names as $plugin_name) {
                    $component = "{$plugin_type}_{$plugin_name}";
                    $classes = core_component::get_namespace_classes(
                        'totara_notification\\notification',
                        built_in_notification::class,
                        $component
                    );

                    if (empty($classes)) {
                        continue;
                    }

                    self::$built_in_notification_classes[$component] = $classes;
                }
            }
        }

        return self::$built_in_notification_classes;
    }

    /**
     * Returning an array of all the notification classes implemented in the system.
     *
     * @param string|null $component Whether we should return the notification classes within the component only or not.
     * @return string[]
     */
    public static function get_notification_classes(?string $component = null): array {
        $map = self::get_map();
        if (!empty($component)) {
            return isset($map[$component]) ? $map[$component] : [];
        }

        $return_classes = [];

        foreach ($map as $component => $classes) {
            $return_classes = array_merge($return_classes, $classes);
        }

        return $return_classes;
    }

    /**
     * @param string $event_classname
     * @return string[]
     */
    public static function get_notification_classes_of_notifiable_event(string $event_classname): array {
        if (!class_exists($event_classname)) {
            throw new coding_exception(
                'The argument event class name does not exist in the system'
            );
        } else if (!helper::is_valid_notifiable_event($event_classname)) {
            throw new coding_exception(
                "Expecting the argument event class name to implement interface " . notifiable_event::class
            );
        }

        $notification_classes = self::get_notification_classes();
        $result = [];

        foreach ($notification_classes as $notification_class) {
            /**
             * @var string $event_name
             * @see built_in_notification::get_event_class_name()
             */
            $event_name = call_user_func([$notification_class, 'get_event_class_name']);
            if ($event_name === $event_classname) {
                $result[] = $notification_class;
            }
        }

        return $result;
    }

    /**
     * @return void
     */
    public static function phpunit_reset_map(): void {
        if (!defined('PHPUNIT_TEST') || !PHPUNIT_TEST) {
            debugging(
                "Please do not call the function 'totara_notification\\factory\\notification_factory::phpunit_reset_map' " .
                "outside of phpunit test environment",
                DEBUG_DEVELOPER
            );

            return;
        }

        self::$built_in_notification_classes = [];
    }

    /**
     * @param string $component
     * @param string $notification_class
     *
     * @return void
     */
    public static function phpunit_add_notification_class(string $component, string $notification_class): void {
        if (!defined('PHPUNIT_TEST') || !PHPUNIT_TEST) {
            debugging(
                "Please do not call the function ".
                "'totara_notification\\factory\\notification_factory::phpunit_add_notification_class' " .
                "outside of phpunit test environment",
                DEBUG_DEVELOPER
            );

            return;
        }

        if (!class_exists($notification_class)) {
            throw new coding_exception("The added notification class does not exist in the system");
        }

        if (!is_subclass_of($notification_class, built_in_notification::class)) {
            throw new coding_exception(
                "Only able to add a child of " . built_in_notification::class
            );
        }

        // Build the map first.
        self::get_map();
        if (!isset(self::$built_in_notification_classes[$component])) {
            self::$built_in_notification_classes[$component] =  [];
        }

        self::$built_in_notification_classes[$component][] = $notification_class;
    }
}