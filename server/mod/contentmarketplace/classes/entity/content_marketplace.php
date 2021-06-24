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
 * @package mod_contentmarketplace
 */
namespace mod_contentmarketplace\entity;

use core\orm\entity\entity;
use stdClass;

/**
 * Entity class represent for table "ttr_contentmarketplace"
 *
 * @property int    $id
 * @property int    $course
 * @property string $name
 * @property string $learning_object_marketplace_component
 * @property int    $learning_object_id
 * @property int    $time_modified
 * @property int    $completion_condition
 */
class content_marketplace extends entity {
    /**
     * @var string
     */
    public const TABLE = 'contentmarketplace';

    /**
     * @var string
     */
    public const UPDATED_TIMESTAMP = 'time_modified';

    /**
     * @var bool
     */
    public const SET_UPDATED_WHEN_CREATED = true;

    /**
     * Using PHP dark magic to convert boolean-like integer into boolean data type.
     *
     * @param bool $value
     * @return bool
     */
    protected function get_completion_on_launch_attribute(bool $value): bool {
        return $value;
    }
}