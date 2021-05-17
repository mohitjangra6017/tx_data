<?php
/**
 * This file is part of Totara Core
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
namespace totara_contentmarketplace\learning_object\abstraction;

use totara_contentmarketplace\learning_object\abstraction\metadata\model;

abstract class resolver {
    /**
     * resolver constructor.
     */
    final public function __construct() {
        // Prevent complicate construction.
    }

    /**
     * Finding the learning object record via id.
     *
     * @param int $id
     * @return model|null
     */
    abstract public function find(int $id): ?model;

    /**
     * @return string
     */
    public static function get_component(): string {
        $class_name = static::class;
        $parts = explode('\\', $class_name);

        return reset($parts);
    }

    // This is where the rest of getter methods should be:
    // + get_view
    // + get_completions
    // + get_backup_plan
    // + get_restore_plan
}