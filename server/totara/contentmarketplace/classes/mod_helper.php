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
namespace totara_contentmarketplace;

use core\orm\query\builder;

/**
 * This class is a bridge between the contentmarketplace totara plugin and the module contentmarketplace.
 * Hence, the coupling within this class is discouraged to be rewritten in somewhere else.
 * Please keep it in one place, as this is a bit of a workaround.
 */
class mod_helper {
    /**
     * Given the instance id of table "ttr_contentnmarketplace" this function will try to
     * yield the completion condition field.
     *
     * @param int $instance_id
     * @return int
     */
    public static function get_completion_condition(int $instance_id): ?int {
        $db = builder::get_db();
        return $db->get_field(
            "contentmarketplace",
            "completion_condition",
            ["id" => $instance_id],
            MUST_EXIST
        );
    }
}