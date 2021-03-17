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
namespace totara_notification\delivery;

use totara_notification\delivery\channel\delivery_channel;

class channel_helper {
    /**
     * channel_helper constructor.
     * Preventing this class from instantiation.
     */
    private function __construct() {
    }

    /**
     * Checks whether the given class name is the valid delivery channel class or not.
     * @param string $delivery_channel_class_name
     * @return bool
     */
    public static function is_valid_delivery_channel_class(string $delivery_channel_class_name): bool {
        return is_a($delivery_channel_class_name, delivery_channel::class, true);
    }

    /**
     * Checks whether the given string identifier is the valid delivery channel or not.
     * By checking its static class built-up from the identifier string.
     *
     * @param string $component_name
     * @return bool
     */
    public static function is_valid_delivery_channel(string $component_name): bool {
        return self::is_valid_delivery_channel_class(
            "message_{$component_name}\\totara_notification\\delivery\\channel\\delivery_channel"
        );
    }
}