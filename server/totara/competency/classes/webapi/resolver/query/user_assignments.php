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
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package totara_competency
 */

namespace totara_competency\webapi\resolver\query;

use core\entity\user;
use core\orm\collection;
use core\webapi\execution_context;
use core\webapi\middleware\require_advanced_feature;
use core\webapi\middleware\require_login;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;
use totara_competency\data_providers\assignments;
use totara_competency\entity\assignment as assignment_entity;
use totara_competency\models\assignment as assignment_model;

class user_assignments implements query_resolver, has_middleware {

    /**
     * @param array $args
     * @param execution_context $ec
     * @return collection|mixed
     */
    public static function resolve(array $args, execution_context $ec) {
        return assignments::for(user::logged_in())
            ->set_filters($args['query']['filters'] ?? [])
            ->fetch_paginated($args['query']['cursor'] ?? null, $args['query']['limit'] ?? null)
            ->transform(static function (assignment_entity $assignment) {
                return assignment_model::load_by_entity($assignment);
            })
            ->get();
    }

    /**
     * @inheritDoc
     */
    public static function get_middleware(): array {
        return [
            new require_login(),
            new require_advanced_feature('competency_assignment'),
        ];
    }

}
