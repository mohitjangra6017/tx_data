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
 * @package totara_oauth2
 */
namespace totara_oauth2\local;

use coding_exception;

class http_helper {
    /**
     * Preventing instantiation
     */
    private function __construct() {
    }

    /**
     * @param array $values
     * @return array
     */
    private static function trim_response_headers_values(array $values): array {

    }

    /**
     * @param mixed|array $value
     * @return mixed|array
     */
    public static function normalize_response_header_value($value) {
        if (is_array($value) && empty($value)) {
            throw new coding_exception("Header value cannot be an empty array");
        }

        if (!is_array($value)) {
            $value = [$value];
        }

        return self::trim_response_headers_values($value);
    }

    /**
     * @param string|mixed $header_name
     * @return void
     */
    public static function assert_header($header_name): void {
        if (!is_string($header_name)) {
            throw new coding_exception("The header name must be a string");
        } else if (empty($header_name)) {
            throw new coding_exception("Header name cannot be empty");
        }
    }
}