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
namespace totara_notification\factory;

use cache;
use cache_loader;
use core_component;
use totara_core\extended_context;
use totara_notification\resolver\notifiable_event_resolver;
use totara_notification\resolver\resolver_helper;

class notifiable_event_resolver_factory {
    /**
     * @var string
     */
    public const MAP_KEY = 'map';

    /**
     * notifiable_event_resolver_factory constructor.
     * Prevent the construction
     */
    private function __construct() {
    }

    /**
     * Returns the cache loader for totara_notification::notifiable_resolver_map
     * @return cache
     */
    public static function get_cache_loader(): cache_loader {
        return cache::make('totara_notification', 'notifiable_resolver_map');
    }

    /**
     * @return void
     */
    public static function load_map(): void {
        $loader = static::get_cache_loader();
        $map = $loader->get(static::MAP_KEY);

        if (!is_array($map)) {
            // If it is an array and it is empty, meaning that we make
            // it empty for the test. Otherwise the cache was never
            // initialised beforehand.
            $map = [];

            // Add core sub system first.
            $core_resolver_classes = core_component::get_namespace_classes(
                'totara_notification\\resolver',
                notifiable_event_resolver::class,
                'core'
            );

            $map['core'] = $core_resolver_classes;

            // Populate the plugins.
            $plugin_types = core_component::get_plugin_types();
            $plugin_types = array_keys($plugin_types);

            foreach ($plugin_types as $plugin_type) {
                $plugin_names = core_component::get_plugin_list($plugin_type);
                $plugin_names = array_keys($plugin_names);

                foreach ($plugin_names as $plugin_name) {
                    $component = "{$plugin_type}_{$plugin_name}";
                    $resolver_classes = core_component::get_namespace_classes(
                        'totara_notification\\resolver',
                        notifiable_event_resolver::class,
                        $component
                    );

                    if (empty($resolver_classes)) {
                        continue;
                    }

                    $map[$component] = $resolver_classes;
                }
            }

            $loader->set(static::MAP_KEY, $map);
        }
    }

    /**
     * @param extended_context|null $extended_context
     * @return array
     */
    public static function get_resolver_classes(?extended_context $extended_context = null): array {
        static::load_map();

        $cache = static::get_cache_loader();
        $map = $cache->get(static::MAP_KEY, MUST_EXIST);

        if (!is_null($extended_context) && !$extended_context->is_natural_context()) {
            // Skip the loop if context level is system level.
            if ($extended_context->get_context()->contextlevel === CONTEXT_SYSTEM) {
                return array_merge(...array_values($map));
            }

            foreach ($map as $component => $notifiable_events) {
                foreach ($notifiable_events as $notifiable_event) {
                    if (!$notifiable_event::supports_context($extended_context)) {
                        // Remove the notifiable event that does not support extended context.
                        unset($map[$component][$notifiable_event]);
                    }
                }
            }
        }

        return array_merge(...array_values($map));
    }

    /**
     * @param extended_context|null $extended_context
     * @return array
     */
    public static function get_scheduled_resolver_classes(?extended_context $extended_context = null): array {
        $classes = static::get_resolver_classes($extended_context);
        return array_filter(
            $classes,
            function (string $cls): bool {
                return resolver_helper::is_valid_scheduled_event_resolver($cls);
            }
        );
    }

}