<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTDvs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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
 * @author  Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package mod_perform
 */

namespace mod_perform\webapi\resolver\mutation;

use core\webapi\execution_context;
use core\webapi\mutation_resolver;
use core\webapi\middleware\require_advanced_feature;
use core\webapi\resolver\has_middleware;
use mod_perform\webapi\middleware\require_activity;
use mod_perform\webapi\middleware\require_manage_capability;

/**
 * Update the relationships for the manual participant roles.
 *
 * @package mod_perform\webapi\resolver\mutation
 */
class update_manual_participants implements mutation_resolver, has_middleware {

    /**
     * {@inheritdoc}
     */
    public static function resolve(array $args, execution_context $ec) {
        // The require_activity middleware loads the activity and passes it along via the args
        $activity = $args['activity'];

        // TODO: Actually save the data in TL-26143
        return [
            'activity' => $activity,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function get_middleware(): array {
        return [
            new require_advanced_feature('performance_activities'),
            require_activity::by_activity_id('activity_id', true),
            require_manage_capability::class
        ];
    }

}
