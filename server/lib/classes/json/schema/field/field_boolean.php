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
namespace core\json\schema\field;

class field_boolean extends field {
    /**
     * @var array
     */
    protected const VALID_BOOLEAN_STRING_LIKE = ['yes', 'no', 'on', 'off', 'false', 'true'];


    /**
     * @param mixed $value
     * @return string|null
     */
    public function validate($value): ?string {
        if (is_bool($value)) {
            return null;
        }

        if (is_string($value)) {
            if (in_array($value, static::VALID_BOOLEAN_STRING_LIKE)) {
                return null;
            }

            return "The boolean like string '{$value}' is invalid for field '{$this->name}'";
        }

        if (is_numeric($value)) {
            if (0 == $value || 1 == $value) {
                return null;
            }

            return "The boolean like numeric '{$value}' is invalid for field '{$this->name}'";
        }

        return "The boolean value is invalid";
    }

    /**
     * @param mixed $value
     * @return bool|null
     */
    public function clean($value): bool {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value) && !in_array($value, static::VALID_BOOLEAN_STRING_LIKE)) {
            // Treating the rest of invalid string like fields to be FALSE.
            return false;
        }

        if (is_numeric($value) && 0 != $value && 1 != $value) {
            // Treating the rest of invalid numeric like fields to be FALSE.
            return false;
        }

        return clean_param($value, PARAM_BOOL);
    }
}