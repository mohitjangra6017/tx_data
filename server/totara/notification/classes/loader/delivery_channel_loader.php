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
 * @author  Cody Finegan <cody.finegan@totaralearning.com>
 * @package totara_notification
 */

namespace totara_notification\loader;

use core_component;
use totara_notification\delivery\channel\delivery_channel;

/**
 * Class delivery_channel_helper
 *
 * @package totara_notification\local
 */
final class delivery_channel_loader {
    /**
     * @var array
     */
    private static $definitions;

    /**
     * Returns a list of all available delivery channel classes.
     *
     * @return array
     */
    public static function get_built_in_classes(): array {
        if (null === self::$definitions) {
            $plugin_names = core_component::get_plugin_list('message');
            $plugin_names = array_keys($plugin_names);

            foreach ($plugin_names as $plugin_name) {
                $class = "message_{$plugin_name}\\totara_notification\\delivery\\channel\\delivery_channel";
                if (class_exists($class)) {
                    self::$definitions[] = $class;
                }
            }
        }

        return self::$definitions;
    }

    /**
     * Return a list of all delivery channels, in their default state.
     *
     * @return delivery_channel[]
     */
    public static function get_defaults(): array {
        /** @var delivery_channel[] $defaults */
        $defaults = [];
        foreach (self::get_built_in_classes() as $built_in_class) {
            /** @var delivery_channel $channel */
            $channel = call_user_func([$built_in_class, 'make']);
            $defaults[$channel->component] = $channel;
        }

        return self::sort_channels($defaults);
    }

    /**
     * @param string $resolver_class_name
     * @return delivery_channel[]
     */
    public static function get_for_event_resolver(string $resolver_class_name): array {
        $defaults = self::get_defaults();
        $default_enabled_keys = call_user_func([$resolver_class_name, 'get_notification_default_delivery_channels']);

        if (is_array($default_enabled_keys)) {
            foreach ($default_enabled_keys as $default_enabled_key) {
                if (!isset($defaults[$default_enabled_key])) {
                    debugging("Unknown default delivery channel '${default_enabled_key} for '${resolver_class_name}'");
                    continue;
                }
                $defaults[$default_enabled_key]->set_enabled(true);
            }
        }

        return $defaults;
    }

    /**
     * Convert the delivery channel list into the collection of delivery channels.
     *
     * @param string $resolver_class_name
     * @param array $delivery_channel_list
     * @return delivery_channel[]
     */
    public static function get_from_list(string $resolver_class_name, array $delivery_channel_list): array {

        // Load the initial list up for the notifiable event
        $channels = self::get_for_event_resolver($resolver_class_name);

        // Mark any channels listed as enabled
        $changed = [];
        foreach ($delivery_channel_list as $component) {
            // We can have empty entries, since the split also includes boundaries.
            if (empty($component) || !isset($channels[$component])) {
                continue;
            }

            $channels[$component]->set_enabled(true);
            $changed[] = $component;
        }

        // Now mark any that we didn't touch as disabled
        foreach ($channels as $channel) {
            if (!in_array($channel->component, $changed)) {
                $channel->set_enabled(false);
            }
        }

        return self::sort_channels($channels);
    }

    /**
     * Override the collected definitions. This is a helper method for unit tests,
     * if called outside a unit test it will throw an exception.
     *
     * @param array|null $definitions
     */
    public static function set_definitions(?array $definitions): void {
        // For unit tests we hijack, we want to load the mock fixtures instead of real classes
        if (!defined('PHPUNIT_TEST') && PHPUNIT_TEST) {
            throw new \coding_exception('Can only override the delivery channel definitions inside a unit test.');
        }

        self::$definitions = $definitions;
    }

    /**
     * Sort the delivery channels based on their display_order preference.
     *
     * @param delivery_channel[] $channels
     * @return delivery_channel[]
     */
    private static function sort_channels(array $channels): array {
        uasort($channels, function ($channel_a, $channel_b) {
            return $channel_a->display_order <=> $channel_b->display_order;
        });

        return $channels;
    }
}