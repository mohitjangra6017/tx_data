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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
namespace totara_notification\factory;

use coding_exception;
use core_component;
use totara_notification\event\notifiable_event;
use totara_notification\local\helper;

class notifiable_event_factory {
    /**
     * A cache map of the component against the list of events.
     *
     * @var array
     */
    private static $event_classes;

    /**
     * event_factory constructor.
     * Prevent the construction of this class
     */
    private function __construct() {
    }

    /**
     * @return array
     */
    private static function get_map(): array {
        if (!isset(self::$event_classes)) {
            self::$event_classes = [];
        }

        if (empty(self::$event_classes)) {
            // Add core sub system first.
            $core_event_classes = core_component::get_namespace_classes('event', notifiable_event::class, 'core');
            if (!empty($core_event_classes)) {
                self::$event_classes['core'] = $core_event_classes;
            }

            // Populate the plugins.
            $plugin_types = core_component::get_plugin_types();
            $plugin_types = array_keys($plugin_types);

            foreach ($plugin_types as $plugin_type) {
                $plugin_names = core_component::get_plugin_list($plugin_type);
                $plugin_names = array_keys($plugin_names);

                foreach ($plugin_names as $plugin_name) {
                    $component = "{$plugin_type}_{$plugin_name}";

                    /** @var array $event_classes */
                    $event_classes = core_component::get_namespace_classes(
                        'event',
                        notifiable_event::class,
                        $component
                    );

                    if (empty($event_classes)) {
                        continue;
                    }

                    self::$event_classes[$component] = $event_classes;
                }
            }
        }

        return self::$event_classes;
    }

    /**
     * @return void
     */
    public static function phpunit_reset_map(): void {
        if (!defined('PHPUNIT_TEST') || !PHPUNIT_TEST) {
            debugging(
                "Please do not call the function 'totara_notification\\factory\\notifiable_event_factory::phpunit_reset_map' " .
                "outside of phpunit test environment",
                DEBUG_DEVELOPER
            );

            return;
        }

        self::$event_classes = [];
    }

    /**
     * @param string $component
     * @param string $class_name
     *
     * @return void
     */
    public static function phpunit_add_notifiable_event_class(string $component, string $class_name): void {
        if (!defined('PHPUNIT_TEST') || !PHPUNIT_TEST) {
            debugging(
                "Please do not call the function " .
                "'totara_notification\\factory\\notifiable_event_factory::phpunit_add_notifiable_event_class' " .
                "outside of phpunit test environment",
                DEBUG_DEVELOPER
            );

            return;
        }

        if (!helper::is_valid_notifiable_event($class_name)) {
            throw new coding_exception(
                "Expecting the event class to implement interface " . notifiable_event::class
            );
        }

        // Construct the map.
        self::get_map();
        if (!isset(self::$event_classes[$component])) {
            self::$event_classes[$component] = [];
        }

        self::$event_classes[$component][] = $class_name;
    }

    /**
     * Returning a list of notifiable events. If $component is given, then only notifiable events
     * within that component are returned. Otherwise all the notifiable events within the system.
     *
     * @param string|null $component
     * @return string[]
     */
    public static function get_notifiable_events(?string $component = null): array {
        $classes_map = self::get_map();
        if (!empty($component)) {
            return isset($classes_map[$component]) ? $classes_map[$component] :  [];
        }

        $return_classes = [];
        foreach ($classes_map as $component => $event_classes) {
            $return_classes = array_merge($return_classes, $event_classes);
        }

        return $return_classes;
    }
}