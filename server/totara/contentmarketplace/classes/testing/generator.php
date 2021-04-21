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
 * @package totara_contentmarketplace
 */
namespace totara_contentmarketplace\testing;

use coding_exception;
use core\testing\component_generator;
use totara_core\http\response;
use totara_core\http\response_code;

/**
 * @method static generator instance()
 */
class generator extends component_generator {
    /**
     * @param array $json_data
     * @param int   $code
     * @param array $response_header
     *
     * @return response
     */
    public function create_json_response(
        array $json_data,
        int $code = response_code::OK,
        array $response_header = []
    ): response {
        $document = json_encode($json_data);
        if (empty($document)) {
            throw new coding_exception("Cannot encode the json document");
        }

        return new response(
            $document,
            $code,
            $response_header,
            'application/json; charset=utf-8'
        );
    }
}