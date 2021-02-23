<?php

use core\orm\collection;
use core\orm\query\builder;
use totara_competency\expand_task;
use totara_competency\models\assignment as assignment_model;
use totara_competency\models\profile\proficiency_value;
use totara_competency\testing\generator as competency_generator;
use totara_competency\webapi\resolver\query\perform_linked_competencies;

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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Fabian Derschatta <fabian.derschatta@totaralearning.com>
 * @package totara_competency
 */

class totara_competency_perform_linked_competencies_testcase extends advanced_testcase {

    public function test_load_with_empty_content_items_collection() {
        $user = $this->getDataGenerator()->create_user();

        $this->setUser($user);

        $query_instance = $this->get_test_dummy_class();

        $collection = new collection([]);

        $result = $query_instance->test_resolve($user->id, $collection);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_load_competency_items_which_do_not_exist() {
        $user = $this->getDataGenerator()->create_user();

        $this->setUser($user);

        $created_at = time();

        // Not using the actual entity to reduce dependency on perform
        $fake_content = [
            (object) [
                'content_id' => 1,
                'created_at' => $created_at,
            ],
            (object) [
                'content_id' => 2,
                'created_at' => $created_at,
            ]
        ];

        $collection = new collection($fake_content);

        $query_instance = $this->get_test_dummy_class();
        $result = $query_instance->test_resolve($user->id, $collection);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
        foreach ($result as $item) {
            $this->assertEquals(
                [
                    'progress' => null,
                    'achievement' => proficiency_value::empty_value()
                ],
                $item
            );
        }
    }

    public function test_load_competency_items() {
        $user1 = $this->getDataGenerator()->create_user();

        $competency1 = $this->generator()->create_competency();
        $competency2 = $this->generator()->create_competency();

        $assignment_generator = $this->generator()->assignment_generator();
        $assignment1 = $assignment_generator->create_user_assignment($competency1->id, $user1->id);
        $assignment2 = $assignment_generator->create_user_assignment($competency2->id, $user1->id);

        (new expand_task(builder::get_db()))->expand_all();

        $this->setUser($user1);

        $created_at = time();

        // Not using the actual entity to reduce dependency on perform
        $fake_content = [
            (object) [
                'content_id' => $assignment1->id,
                'created_at' => $created_at,
            ],
            (object) [
                'content_id' => 666,
                'created_at' => $created_at,
            ],
            (object) [
                'content_id' => $assignment2->id,
                'created_at' => $created_at,
            ],
        ];

        $collection = new collection($fake_content);

        $query_instance = $this->get_test_dummy_class();
        $result = $query_instance->test_resolve($user1->id, $collection);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertCount(3, $result);

        $first_item = array_shift($result);
        $second_item = array_shift($result);
        $third_item = array_shift($result);

        $this->assertArrayHasKey('progress', $first_item);
        $this->assertInstanceOf(assignment_model::class, $first_item['progress']);
        /** @var assignment_model $progress */
        $progress = $first_item['progress'];
        $this->assertEquals($assignment1->id, $progress->get_id());
        $this->assertArrayHasKey('achievement', $first_item);
        $this->assertInstanceOf(proficiency_value::class, $first_item['achievement']);

        $this->assertEquals(
            [
                'progress' => null,
                'achievement' => proficiency_value::empty_value()
            ],
            $second_item
        );

        $this->assertArrayHasKey('progress', $third_item);
        $this->assertInstanceOf(assignment_model::class, $third_item['progress']);
        /** @var assignment_model $progress */
        $progress = $third_item['progress'];
        $this->assertEquals($assignment2->id, $progress->get_id());
        $this->assertArrayHasKey('achievement', $third_item);
        $this->assertInstanceOf(proficiency_value::class, $third_item['achievement']);
    }

    private function get_test_dummy_class() {
        return new class() extends perform_linked_competencies {
            public function test_resolve($user_id, $collection) {
                return self::query_content($user_id, $collection);
            }
        };
    }

    /**
     * Get competeny specific generator
     *
     * @return competency_generator
     */
    protected function generator() {
        return competency_generator::instance();
    }

}