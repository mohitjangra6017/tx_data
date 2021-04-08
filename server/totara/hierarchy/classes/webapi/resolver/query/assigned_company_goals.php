<?php
/*
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
 * @author Murali Nair <murali.nair@totaralearning.com>
 * @package totara_hierarchy
 */

namespace totara_hierarchy\webapi\resolver\query;

use context_system;
use context_user;
use core\entity\user;

use core\pagination\cursor;
use core\webapi\execution_context;
use core\webapi\query_resolver;
use core\webapi\middleware\require_advanced_feature;
use core\webapi\middleware\require_login;
use core\webapi\resolver\has_middleware;

use hierarchy_goal\assignment_type_extended;
use hierarchy_goal\company_goal_assignment;
use hierarchy_goal\company_goal_assignment_type;
use hierarchy_goal\data_providers\goal_data_provider;
use hierarchy_goal\data_providers\assigned_company_goals as assigned_company_goals_provider;
use hierarchy_goal\entity\company_goal_assignment_type_extended;

/**
 * Handles the "totara_hierarchy_assigned_company_goals" GraphQL query.
 */
class assigned_company_goals implements query_resolver, has_middleware {
    /**
     * {@inheritdoc}
     */
    public static function resolve(array $args, execution_context $ec) {
        $input = $args['input'] ?? [];
        $result = self::get_goal_user_assignments($input);
        $result['items'] = self::create_aggregated($result['items']);

        return $result;
    }

    /**
     * Retrieves goal user assignments.
     *
     * @param array $input incoming query parameters.
     *
     * @return array the current page of goal user assignments.
     */
    private static function get_goal_user_assignments(array $input): array {
        $logged_on_user_id = user::logged_in()->id;

        $filters = $input['filters'] ?? [];
        $target_user_id = $filters['user_id'] ?? null;
        if (!$target_user_id) {
            $target_user_id = $logged_on_user_id;
            $filters['user_id'] = $target_user_id;
        }
        self::authorize($logged_on_user_id, $target_user_id);

        $order_by = $input['order_by'] ?? 'userid';
        $order_dir = $input['order_dir'] ?? 'ASC';
        $result_size = $input['result_size'] ?? goal_data_provider::DEFAULT_PAGE_SIZE;

        $enc_cursor = $input['cursor'] ?? null;
        $cursor = $enc_cursor ? cursor::decode($enc_cursor) : null;

        $type = $filters['assignment_type'] ?? null;
        if ($type) {
            $filters['assignment_type'] = company_goal_assignment_type::by_name($type)
                ->get_value();
        }

        return assigned_company_goals_provider::create()
            ->set_page_size($result_size)
            ->set_filters($filters)
            ->set_order($order_by, $order_dir)
            ->fetch_paginated($cursor);
    }

    /**
     * Updates the assigned company goals with their assignment types..
     *
     * @param company_goal_assignment[] $assignments assignments.
     *
     * @return company_goal_assignment[] the updated goal assignments.
     */
    private static function create_aggregated(array $assignments): array {
        $extended = [];

        foreach ($assignments as $assignment) {
            $goal = $assignment->goal;
            $user = $assignment->user;

            $types = company_goal_assignment_type_extended::repository()
                ->where('userid', $user->id)
                ->where('goalid', $goal->id)
                ->get()
                ->map_to(
                    function (company_goal_assignment_type_extended $raw): assignment_type_extended {
                        $type = company_goal_assignment_type::by_value($raw->assigntype);

                        return assignment_type_extended::create_company_goal_assignment_type(
                            $type, $raw
                        );
                    }
                )
                ->all();

            $extended[] = new company_goal_assignment(
                $assignment->id, $goal, $user, $types
            );
        }

        return $extended;
    }

    /**
     * Checks the user's authorization.
     *
     * @param int $logged_on_user_id currently logged on user.
     * @param int $target_user_id user whose goals are to be retrieved.
     */
    private static function authorize(int $logged_on_user_id, int $target_user_id): void {
        if (has_capability('totara/hierarchy:viewallgoals', context_system::instance())) {
            return;
        }

        if ($logged_on_user_id === $target_user_id) {
            $context = context_user::instance($logged_on_user_id);
            require_capability('totara/hierarchy:viewowncompanygoal', $context);
        } else {
            $context = context_user::instance($target_user_id);
            require_capability('totara/hierarchy:viewstaffcompanygoal', $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function get_middleware(): array {
        return [
            new require_advanced_feature('goals'),
            new require_login()
        ];
    }
}
