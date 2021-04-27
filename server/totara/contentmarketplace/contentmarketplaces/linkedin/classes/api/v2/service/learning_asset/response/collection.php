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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\api\v2\service\learning_asset\response;

use contentmarketplace_linkedin\api\response\collection as base_collection;
use contentmarketplace_linkedin\api\response\pagination;
use core\json\schema\container_schema;
use core\json\schema\field\field_collection;
use core\json\schema\field\field_object;
use core\json\schema\object_container;
use core\json\schema\collection as collection_schema;

class collection extends base_collection {
    /**
     * @return container_schema
     */
    protected static function get_json_schema(): container_schema {
        return object_container::create(
            new field_collection(
                'elements',
                new collection_schema(element::get_json_schema()),
            ),
            new field_object(
                'paging',
                pagination::get_json_schema()
            ),
        );
    }

    /**
     * @return element[]
     */
    public function get_elements(): array {
        $elements = $this->json_data['elements'];

        return array_map(
            function (array $element_data): element {
                return element::create($element_data);
            },
            $elements,
        );
    }
}