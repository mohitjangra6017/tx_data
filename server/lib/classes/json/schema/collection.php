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
 * @package core
 */
namespace core\json\schema;

use coding_exception;
use core\json\schema\field\field;

/**
 * The collection schema is only a collection of the same json object. It is not a collection
 * of mixed json object.
 */
class collection implements collection_schema {
    /**
     * @var object_container
     */
    private $object_container;

    private $field_name;

    /**
     * collection_schema constructor.
     * @param object_container $object_container
     */
    public function __construct(object_container $object_container) {
        $this->object_container = $object_container;
    }

    /**
     * @param field ...$fields
     * @return collection
     */
    public static function create_from_fields_of_obj_container(field ...$fields): collection {
        return new static(
            object_container::create(...$fields)
        );
    }

    /**
     * @param array|null $value
     * @return string|null
     */
    public function validate($value): ?string {
        if (null === $value) {
            // We are treating null as data not appearing, meaning no validation for null.
            return null;
        }

        if (!is_array($value)) {
            return "The value collection is not an array";
        }

        foreach ($value as $index => $single_obj_value) {
            $error = $this->object_container->validate($single_obj_value);
            if (!empty($error)) {
                $error .= " at index {$index}";
                return $error;
            }
        }

        return null;
    }

    /**
     * @param array $value
     * @return array
     */
    public function clean($value): array {
        if (!is_array($value)) {
            throw new coding_exception('Expects the parameter value to be an array');
        }

        $cloned_value = $value;
        foreach ($value as $i => $single_obj_value) {
            $cloned_value[$i] = $this->object_container->clean($single_obj_value);
        }

        return $cloned_value;
    }
}