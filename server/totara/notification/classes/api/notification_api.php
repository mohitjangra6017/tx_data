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

namespace totara_notification\api;

use core_component;
use totara_notification\notification\notification;

final class notification_api {
    /**
     * notification_api constructor.
     */
    private function __construct() {
    }

    /**
     * Get all subclasses for the notification.
     * @return array
     */
    public static function get_notifications(): array {
        $list = [];
        foreach (core_component::get_component_list() as $plugin) {
            $plugin_names = array_keys($plugin);

            foreach ($plugin_names as $plugin_name) {
                $classes = core_component::get_namespace_classes(
                    'totara_notification',
                    notification::class,
                    $plugin_name
                );

                if (!empty($classes)) {
                    foreach ($classes as $class) {
                        // Todo add strict initialization for each class
                        $list[] = new $class();
                    }
                }
            }
        }

        return $list;
    }
}