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
namespace totara_notification\webapi\resolver\query;

use core\webapi\execution_context;
use core\webapi\middleware\require_login;
use core\webapi\middleware\require_user_capability;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;
use totara_notification\factory\notifiable_event_factory;

final class component_configurations implements query_resolver, has_middleware {
    /**
     * @param array $args
     * @param execution_context $ec
     * @return mixed|void
     */
    public static function resolve(array $args, execution_context $ec): array {
        return self::format_component_configurations();
    }

    /**
     * @inheritDoc
     */
    public static function get_middleware(): array {
        return [
            new require_login(),
            new require_user_capability('moodle/site:config'),
        ];
    }

    /**
     * Format component configurations to make the return data to FE.
     *
     * @return array
     */
    private static function format_component_configurations(): array {
        $ret = [];
        $events_map = notifiable_event_factory::get_notifiable_events_group_by_component();
        foreach (array_keys($events_map) as $component) {
            if (get_string_manager()->string_exists('pluginname', $component)) {
                $plugin_name = get_string('pluginname', $component);
            } else {

                // If component dose not define pluginname in langstring, we just fallback to the name of component, then
                // put debugging here to let dev know they need to define the pluginname for each plugin.
                $plugin_name = $component;
                debugging("pluginnanme need to be defined in langstring for the {$plugin_name}", DEBUG_DEVELOPER);
            }

            $maps['component'] = $plugin_name;
            $maps['notifiable_event_configurations'] = $events_map[$component];
            $ret[] = $maps;
        }

        // Ideally it will be never reached, but if dev do not implement any notifiable event, it will fallback to here.
        if (empty($ret)) {
            return [
                [
                    'component' => '',
                    'notifiable_event_configurations' => []
                ]
            ];
        }
        return $ret;
    }
}