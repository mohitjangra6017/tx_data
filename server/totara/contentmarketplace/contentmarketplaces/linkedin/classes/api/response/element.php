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
namespace contentmarketplace_linkedin\api\response;

use contentmarketplace_linkedin\exception\json_validation_exception;
use core\json\schema\object_container;

abstract class element implements result {
    /**
     * The cleaned and validated json data.
     * @var array
     */
    protected $json_data;

    /**
     * element constructor.
     * @param array $json_data
     */
    public function __construct(array $json_data) {
        $this->json_data = $json_data;
    }

    /**
     * Return the URN from element data.
     * @return string
     */
    abstract public function get_urn(): string;

    /**
     * Returns the json schema for a single element object.
     * @return object_container
     */
    abstract public static function get_json_schema(): object_container;

    /**
     * @param array $json_data
     * @return element
     */
    public static function create(array $json_data): element {
        $schema = static::get_json_schema();
        $error = $schema->validate($json_data);

        if (!empty($error)) {
            throw new json_validation_exception($error);
        }

        $json_data = $schema->clean($json_data);
        return new static($json_data);
    }
}