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

use core\json\schema\collection_schema;

class field_collection extends field {
    /**
     * @var collection_schema
     */
    private $collection_schema;

    /**
     * field_collection_schema constructor.
     * @param string            $name
     * @param collection_schema $collection_schema
     * @param bool              $required
     */
    public function __construct(string $name, collection_schema $collection_schema, bool $required = true) {
        parent::__construct($name, $required);
        $this->collection_schema = $collection_schema;
    }

    /**
     * @param array $value
     * @return string|null
     */
    public function validate($value): ?string {
        $error = $this->collection_schema->validate($value);
        if (!empty($error)) {
            return $error . " of field '{$this->name}'";
        }

        return null;
    }

    /**
     * @param array $value
     * @return array
     */
    public function clean($value): array {
        return $this->collection_schema->clean($value);
    }
}