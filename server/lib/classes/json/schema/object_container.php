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
 * For Object json.
 * @example
 * {
 *  "field_1": 15,
 *  "field_2": true,
 *  "field_3": "Hello world"
 * }
 */
class object_container implements container_schema {
    /**
     * @var field[]
     */
    private $fields;

    /**
     * object_schema constructor.
     * @param field[] $fields
     */
    protected function __construct(array $fields) {
        $this->fields = $fields;
    }

    /**
     * @param field ...$fields
     * @return object_container
     */
    public static function create(field ...$fields): object_container {
        if (empty($fields)) {
            throw new coding_exception(
                'Cannot instantiate the object container with empty fields'
            );
        }

        return new static($fields);
    }

    /**
     * @param array $value
     * @return string|null
     */
    public function validate($value): ?string {
        if (null === $value) {
            // We are treating null as data not appearing, meaning no validation for null.
            return null;
        }

        if (!is_array($value)) {
            return "The value for object schema is not an array";
        }

        foreach ($this->fields as $field) {
            $name = $field->get_name();
            if (!array_key_exists($name, $value)) {
                if ($field->is_required()) {
                    return "Missing field '{$name}' in the json object";
                }

                continue;
            }

            $error = $field->validate($value[$name]);
            if (null !== $error) {
                return $error . " within the json object";
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
            throw new coding_exception("Only expect the parameter value to be array");
        }

        $cloned_value = $value;
        foreach ($this->fields as $field) {
            $name = $field->get_name();
            if (!array_key_exists($name, $cloned_value)) {
                if ($field->is_required()) {
                    throw new coding_exception("Missing field '{$name}' within the json object");
                }

                continue;
            }

            $field_value = $cloned_value[$name];
            $cloned_value[$name] = $field->clean($field_value);
        }

        return $cloned_value;
    }
}