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
 * @author Matthias Bonk <matthias.bonk@totaralearning.com>
 * @package hierarchy_goal
 */

use core_phpunit\testcase;
use hierarchy_goal\entity\goal_item_history;
use hierarchy_goal\entity\scale_value;
use hierarchy_goal\helpers\goal_helper;
use hierarchy_goal\personal_goal_assignment_type;

global $CFG;
require_once($CFG->dirroot . '/totara/hierarchy/prefix/goal/lib.php');

/**
 * @group hierarchy_goal
 */
class hierarchy_goal_goal_helper_testcase extends testcase {

    public function test_get_goal_scale_value_at_timestamp(): void {
        [$user, $goal1, $goal2] = $this->create_goals();

        $now = time();
        $an_hour_ago = $now - HOURSECS;
        $a_day_ago = $now - DAYSECS;
        $a_week_ago = $now - WEEKSECS;

        /** @var scale_value $created_scale_value */
        $created_scale_value = scale_value::repository()->where('name', 'Created')->one(true);
        /** @var scale_value $finished_scale_value */
        $finished_scale_value = scale_value::repository()->where('name', 'Finished')->one(true);

        self::assertEquals($created_scale_value, goal_helper::get_goal_scale_value_at_timestamp(goal::SCOPE_PERSONAL, $goal1->id, $now));
        self::assertEquals($created_scale_value, goal_helper::get_goal_scale_value_at_timestamp(goal::SCOPE_PERSONAL, $goal1->id, $now + 100));
        self::assertNull(goal_helper::get_goal_scale_value_at_timestamp(goal::SCOPE_PERSONAL, $goal1->id, $an_hour_ago));

        // Goal 2 doesn't have a scale, so it should always return null.
        self::assertNull(goal_helper::get_goal_scale_value_at_timestamp(goal::SCOPE_PERSONAL, $goal2->id, $now));
        self::assertNull(goal_helper::get_goal_scale_value_at_timestamp(goal::SCOPE_PERSONAL, $goal2->id, $an_hour_ago));

        // Manipulate DB to make the 'Created' scale value assignment older.
        goal_item_history::repository()
            ->where('scalevalueid', $created_scale_value->id)
            ->update(['timemodified' => $a_day_ago]);
        self::assertEquals($created_scale_value, goal_helper::get_goal_scale_value_at_timestamp(goal::SCOPE_PERSONAL, $goal1->id, $now));
        self::assertEquals($created_scale_value, goal_helper::get_goal_scale_value_at_timestamp(goal::SCOPE_PERSONAL, $goal1->id, $an_hour_ago));

        // Update the goal status.
        $generator = self::getDataGenerator();
        /** @var \totara_hierarchy\testing\generator $hierarchy_generator */
        $hierarchy_generator = $generator->get_plugin_generator('totara_hierarchy');
        $hierarchy_generator->update_personal_goal_user_scale_value($user->id, $goal1->id, $finished_scale_value->id);
        $now = time();
        self::assertEquals($finished_scale_value, goal_helper::get_goal_scale_value_at_timestamp(goal::SCOPE_PERSONAL, $goal1->id, $now));
        self::assertEquals($finished_scale_value, goal_helper::get_goal_scale_value_at_timestamp(goal::SCOPE_PERSONAL, $goal1->id, $now + 100));
        self::assertEquals($created_scale_value, goal_helper::get_goal_scale_value_at_timestamp(goal::SCOPE_PERSONAL, $goal1->id, $an_hour_ago));
        self::assertNull(goal_helper::get_goal_scale_value_at_timestamp(goal::SCOPE_PERSONAL, $goal1->id, $a_week_ago));

        // Returns null for non-existent goal id.
        self::assertNull(goal_helper::get_goal_scale_value_at_timestamp(goal::SCOPE_PERSONAL, - 1, time()));
    }

    private function create_goals(): array {
        self::setAdminUser();

        $generator = self::getDataGenerator();
        /** @var \totara_hierarchy\testing\generator $hierarchy_generator */
        $hierarchy_generator = $generator->get_plugin_generator('totara_hierarchy');

        $type = personal_goal_assignment_type::self()->get_value();

        $user = $generator->create_user();

        $scale_values = [
            1 => ['name' => 'Finished', 'proficient' => 1, 'sortorder' => 1, 'default' => 0],
            2 => ['name' => 'Started', 'proficient' => 0, 'sortorder' => 2, 'default' => 0],
            3 => ['name' => 'Created', 'proficient' => 0, 'sortorder' => 3, 'default' => 1]
        ];
        $scale1 = $hierarchy_generator->create_scale('goal', ['name' => 'goal_scale1'], $scale_values);
        $goal1 = $hierarchy_generator->create_personal_goal($user->id, [
            'name' => "goal1",
            'assigntype' => $type,
            'scaleid' => $scale1->id,
            'scalevalueid' => scale_value::repository()->where('name', 'Created')->one(true)->id
        ]);

        // goal2 doesn't have a scale.
        $goal2 = $hierarchy_generator->create_personal_goal($user->id, [
            'name' => "goal1",
            'assigntype' => $type
        ]);

        return [$user, $goal1, $goal2];
    }
}