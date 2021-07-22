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
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\core_json\structure;

use contentmarketplace_linkedin\core_json\data_format\contribution_type;
use core\json\structure\structure;
use core\json\type;

class contributor extends structure {
    /**
     * @return array
     */
    public static function get_definition(): array {
        $locale_string_definition = locale_string::get_definition();
        return [
            'type' => type::OBJECT,
            'properties' => [
                'name' => $locale_string_definition,
                'urn' => [
                    'type' => type::STRING,
                ],
                'contributionType' => [
                    'type' => type::STRING,
                    'format' => contribution_type::get_name(),
                ],
                'authorDetails' => [
                    'type' => type::OBJECT,
                    'properties' => [
                        'lastName' => $locale_string_definition,
                        'firstName' => $locale_string_definition,
                    ],
                ],
            ],
            'required' => ['contributionType', 'name', 'urn'],
            structure::ADDITIONAL_PROPERTIES => false,
        ];
    }
}