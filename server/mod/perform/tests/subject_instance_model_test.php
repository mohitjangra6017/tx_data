<?php
/**
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
 * @author Jaron Steenson <jaron.steenson@totaralearning.com>
 * @package mod_perform
 * @category test
 */

use core_phpunit\testcase;
use mod_perform\entity\activity\subject_instance as subject_instance_entity;
use mod_perform\models\due_date;
use mod_perform\models\activity\subject_instance;

use mod_perform\testing\generator;

/**
 * @group perform
 */
class mod_perform_subject_instance_model_testcase extends testcase {

    /**
     * @param int $extra_instance_count
     * @dataProvider get_instance_count_provider
     */
    public function test_get_instance_count(int $extra_instance_count): void {
        $this->setAdminUser();

        /** @var \mod_perform\testing\generator $perform_generator */
        $perform_generator = generator::instance();

        $config = \mod_perform\testing\activity_generator_configuration::new()
            ->set_number_of_activities(1)
            ->set_number_of_tracks_per_activity(1)
            ->set_number_of_users_per_user_group_type(1);

        $perform_generator->create_full_activities($config)->first();

        /** @var subject_instance_entity $subject_instance_entity */
        $subject_instance_entity = subject_instance_entity::repository()->order_by('id')->first();

        $i = 0;
        $now = time();
        while ($extra_instance_count > $i) {
            $extra_subject_instance = new subject_instance_entity();
            $extra_subject_instance->track_user_assignment_id = $subject_instance_entity->track_user_assignment_id;
            $extra_subject_instance->subject_user_id = $subject_instance_entity->subject_user_id;
            $extra_subject_instance->created_at = $now + ($i + 1); // Force a decent gap between created at times.
            $extra_subject_instance->save();

            $i++;
        }

        $last_instance_entity = $extra_subject_instance ?? $subject_instance_entity;

        $first_instance_count = (new subject_instance($subject_instance_entity))->get_instance_count();
        $last_instance_count = (new subject_instance($last_instance_entity))->get_instance_count();

        self::assertEquals(1, $first_instance_count);
        self::assertEquals($extra_instance_count + 1, $last_instance_count);
    }

    public function get_instance_count_provider(): array {
        return [
            'Single' => [0],
            'Double' => [1],
            'Triple' => [2],
        ];
    }

    public function test_overdue(): void {
        $this->setAdminUser();

        $generator = generator::instance();
        $activity = $generator->create_activity_in_container();

        $user_tz = new DateTimeZone(core_date::get_user_timezone());
        $due_dates = [
            new DateTimeImmutable('now', $user_tz),
            new DateTimeImmutable('2 days', $user_tz),
            new DateTimeImmutable('-3 days', $user_tz),
            null // ie no due date.
        ];

        [$due_today, $due_in_future, $overdue, $no_due_date] = array_map(
            function (?DateTimeImmutable $due_date) use ($activity, $generator): subject_instance {
                $subject_instance = $generator->create_subject_instance([
                    'activity_id' => $activity->id,
                    'subject_is_participating' => true,
                    'include_questions' => false,
                    'subject_user_id' => $this->getDataGenerator()->create_user()->id
                ]);

                if ($due_date) {
                    $subject_instance->due_date = $due_date->getTimestamp();
                    $subject_instance->save();
                }

                return subject_instance::load_by_entity($subject_instance);
            },
            $due_dates
        );

        $testcases = [
            'no due date' => [$no_due_date, false, null, null],
            'due today' => [$due_today, true, false, 0],
            'due in future' => [$due_in_future, true, false, 2],
            'overdue' => [$overdue, true, true, 3]
        ];

        foreach ($testcases as $id => $testcase) {
            [$subject_instance_model, $has_due_date, $is_overdue, $expected_interval] = $testcase;

            $due_date = $subject_instance_model->due_on;
            if (!$has_due_date) {
                $this->assertNull($due_date);
                continue;
            }

            $this->assertEquals($is_overdue, $due_date->is_overdue(), "[$id] wrong overdue value");
            $this->assertEquals(
                [$expected_interval, due_date::INTERVAL_IN_DAYS],
                $due_date->get_interval_to_or_past_due_date(),
                "[$id] wrong interval details"
            );
        }
    }
}
