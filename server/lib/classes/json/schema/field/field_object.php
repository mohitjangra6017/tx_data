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

use core\json\schema\object_container;

/**
 * An object within another object.
 */
class field_object extends field {
    /**
     * @var object_container
     */
    private $object_container;

    /**
     * field_object_schema constructor.
     * @param string           $name
     * @param object_container $object_container
     * @param bool             $required
     */
    public function __construct(string $name, object_container $object_container, bool $required = true) {
        parent::__construct($name, $required);
        $this->object_container = $object_container;
    }

    /**
     * @param array $value
     * @return string|null
     */
    public function validate($value): ?string {
        $error = $this->object_container->validate($value);
        if (!empty($error)) {
            $error .= " at field '{$this->name}'";
        }

        return $error;
    }

    /**
     * @param array $value
     * @return array
     */
    public function clean($value): array {
        return $this->object_container->clean($value);
    }
}