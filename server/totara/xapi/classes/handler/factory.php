<?php
/**
 * This file is part of Totara Core
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
 * @package totara_xapi
 */
namespace totara_xapi\handler;

use coding_exception;
use totara_xapi\request\request;

/**
 * Static class to get the handler based on the component.
 */
class factory {
    /**
     * Preventing this class from being instantiated
     */
    private function __construct() {
    }

    /**
     * @param string $component
     * @param request $request
     * @param int|null $time_now
     * @return base_handler
     */
    public static function create_handler(
        string $component,
        request $request,
        ?int $time_now = null
    ): base_handler {
        $class_name = self::get_handler_class($component);
        if (!class_exists($class_name)) {
            throw new coding_exception("The class '{$class_name}' does not exist.");
        }

        if (!is_subclass_of($class_name, base_handler::class)) {
            throw new coding_exception(
                "The class '{$class_name}' is not an instance of " . base_handler::class
            );
        }

        return new $class_name($request, $time_now);
    }

    /**
     * @param string $component
     * @return string
     */
    private static function get_handler_class(string $component): string {
        return "{$component}\\totara_xapi\\handler\\handler";
    }
}