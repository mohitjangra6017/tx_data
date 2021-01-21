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
namespace totara_notification\notification;

/**
 * Factory class to create the customized notification based on the component.
 */
final class notification_factory {
    /**
     * notification_factory constructor.
     */
    private function __construct() {
    }

    /**
     * @param string $component
     * @return array
     */
    public static function create_notifications_by_component(string $component): array {
        $classes = \core_component::get_namespace_classes(
            'totara_notification',
            notification::class,
            $component
        );

        $list = [];
        if (empty($classes)) {
            debugging(`{$component} has not have customized notfication.`);
            return $list;
        }

        foreach ($classes as $class) {
            // Todo add strict initialization for each class
            $list[] = new $class();
        }

        return $list;
    }
}