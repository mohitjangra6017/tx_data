<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
 * @author Nathan Lewis <nathan.lewis@totaralearning.com>
 * @package mod_perform
 */

namespace mod_perform\webapi\resolver\query;

use core\webapi\execution_context;
use core\webapi\middleware\require_advanced_feature;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;

class user implements query_resolver, has_middleware {
    /**
     * {@inheritdoc}
     */
    public static function resolve(array $args, execution_context $ec) {
        $userid = $args['userid'];

        return \core\entities\user::repository()->where('id', $userid)->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function get_middleware(): array {
        return [
            new require_advanced_feature('performance_activities'),
        ];
    }
}