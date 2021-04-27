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
 * @package core
 */
namespace core\json\schema\field;

use coding_exception;

/**
 * For a simple array of values.
 */
class field_array extends field {
    /**
     * If the value appears for the attribute then we are expecting
     * an array of specific expected param type.
     *
     * Otherwise a mixed of expected param type.
     *
     * @var string|null
     */
    private $expected_param_type;

    /**
     * field_array constructor.
     * @param string      $name
     * @param string|null $expected_param_type
     * @param bool        $required
     */
    public function __construct(string $name, ?string $expected_param_type = null, bool $required = true) {
        parent::__construct($name, $required);
        $this->expected_param_type = $expected_param_type;
    }


    /**
     * @param array $value
     * @return string|null
     */
    public function validate($value): ?string {
        if (null === $value) {
            return null;
        }

        if (!is_array($value)) {
            return "The value for field '{$this->name}' is not an array";
        }

        foreach ($value as $single_value) {
            if (!is_scalar($single_value)) {
                return "The single value within array of field '{$this->name}' is not a scalar";
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
            if (null === $value) {
                return [];
            }

            throw new coding_exception(
                "The value for field '{$this->name}' is not an array"
            );
        }

        $name = $this->name;
        array_walk(
            $value,
            function ($single_value) use ($name) {
                if (!is_scalar($single_value)) {
                    throw new coding_exception(
                        "There is non scalar value within the array value of field '{$name}'"
                    );
                }
            }
        );

        if (null === $this->expected_param_type) {
            return $value;
        }

        $cloned_value = [];
        foreach ($value as $i => $single_value) {
            $cloned_value[$i] = clean_param($single_value, $this->expected_param_type);
        }

        return $cloned_value;
    }
}