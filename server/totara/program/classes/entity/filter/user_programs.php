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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Johannes Cilliers <johannes.cilliers@totaralearning.com>
 * @package totara_program
 */

namespace totara_program\entity\filter;

use core\orm\entity\filter\filter;

/**
 * Finds all programs where user is assigned.
 */
class user_programs extends filter {

    /**
     * @inheritDoc
     */
    public function apply() {
        [$select, $from, $where, $sort, $params] = \prog_get_all_programs_sql(
            $this->value,
            '',
            false,
            true,
            false,
            false
        );

        $where .= " AND prog.id = p.id";
        $this->builder->where_raw(" EXISTS ({$select} {$from} {$where})", $params);
    }


}
