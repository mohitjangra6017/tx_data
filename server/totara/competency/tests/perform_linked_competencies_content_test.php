<?php

use core\orm\query\builder;
use totara_competency\expand_task;
use totara_competency\models\assignment as assignment_model;
use totara_competency\performelement_linked_review\competency_assignment;
use totara_competency\testing\generator as competency_generator;
use totara_core\advanced_feature;

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

class totara_competency_perform_linked_competencies_content_testcase extends advanced_testcase {

    public function test_load_with_empty_content_items_collection() {
        $user = $this->getDataGenerator()->create_user();

        $this->setUser($user);

        $content_type = new competency_assignment(context_system::instance());

        $result = $content_type->load_content_items($user->id, [], time());

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_load_competency_items_which_do_not_exist() {
        $user = $this->getDataGenerator()->create_user();

        $this->setUser($user);

        $content_type = new competency_assignment(context_system::instance());

        $result = $content_type->load_content_items($user->id, [1, 2], time());

        $this->assertEmpty($result);
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

        $content_ids = [$assignment1->id, 666, $assignment2->id];

        $content_type = new competency_assignment(context_system::instance());

        $result = $content_type->load_content_items($user1->id, $content_ids, $created_at);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);

        $actual_first_item = array_filter($result, function (array $item) use ($assignment1) {
            return $item['id'] == $assignment1->id;
        });
        $actual_first_item = array_shift($actual_first_item);

        $expected_assignment1 = assignment_model::load_by_id($assignment1->id);

        $expected_scale_values1 = $expected_assignment1->get_assignment_specific_scale()->values->sort('sortorder', 'asc', false);
        $expected_scale1 = [];
        foreach ($expected_scale_values1 as $expected_scale_value) {
            $expected_scale1[] = [
                'id' => $expected_scale_value->id,
                'name' => $expected_scale_value->name,
                'proficient' => (bool) $expected_scale_value->proficient,
                'sort_order' => $expected_scale_value->sortorder,
            ];
        }

        $expected_content_first_item = [
            'id' => $expected_assignment1->get_id(),
            'competency' => [
                'id' => $expected_assignment1->get_competency()->id,
                'display_name' => $expected_assignment1->get_competency()->display_name,
                'description' => $expected_assignment1->get_competency()->description,
            ],
            'assignment' => [
                'reason_assigned' => $expected_assignment1->get_reason_assigned(),
            ],
            'achievement' => [
                'id' => 0,
                'name' => get_string('no_value_achieved', 'totara_competency'),
                'proficient' => false,
            ],
            'scale_values' => $expected_scale1,
        ];

        $this->assertEquals($expected_content_first_item, $actual_first_item);

        $actual_third_item = array_filter($result, function (array $item) use ($assignment2) {
            return $item['id'] == $assignment2->id;
        });
        $actual_third_item = array_shift($actual_third_item);

        $expected_assignment2 = assignment_model::load_by_id($assignment2->id);

        $expected_scale_values2 = $expected_assignment2->get_assignment_specific_scale()->values->sort('sortorder', 'asc', false);
        $expected_scale2 = [];
        foreach ($expected_scale_values2 as $expected_scale_value) {
            $expected_scale2[] = [
                'id' => $expected_scale_value->id,
                'name' => $expected_scale_value->name,
                'proficient' => (bool) $expected_scale_value->proficient,
                'sort_order' => $expected_scale_value->sortorder,
            ];
        }

        $expected_content_third_item = [
            'id' => $expected_assignment2->get_id(),
            'competency' => [
                'id' => $expected_assignment2->get_competency()->id,
                'display_name' => $expected_assignment2->get_competency()->display_name,
                'description' => $expected_assignment2->get_competency()->description,
            ],
            'assignment' => [
                'reason_assigned' => $expected_assignment2->get_reason_assigned(),
            ],
            'achievement' => [
                'id' => 0,
                'name' => get_string('no_value_achieved', 'totara_competency'),
                'proficient' => false,
            ],
            'scale_values' => $expected_scale2,
        ];

        $this->assertEquals($expected_content_third_item, $actual_third_item);
    }

    public function test_feature_disabled() {
        $user1 = $this->getDataGenerator()->create_user();

        $competency1 = $this->generator()->create_competency();
        $competency2 = $this->generator()->create_competency();

        $assignment_generator = $this->generator()->assignment_generator();
        $assignment1 = $assignment_generator->create_user_assignment($competency1->id, $user1->id);
        $assignment2 = $assignment_generator->create_user_assignment($competency2->id, $user1->id);

        (new expand_task(builder::get_db()))->expand_all();

        $this->setUser($user1);

        $content_ids = [$assignment1->id, 666, $assignment2->id];

        advanced_feature::disable('competency_assignment');

        $content_type = new competency_assignment(context_system::instance());
        $result = $content_type->load_content_items($user1->id, $content_ids, time());

        $this->assertEmpty($result);
    }

    public function test_get_display_settings() {
        $display_settings = competency_assignment::get_display_settings([]);

        $this->assertEquals(
            [],
            // ['Show rating' => 'Disabled'],
            $display_settings
        );

        $display_settings = competency_assignment::get_display_settings([
            // 'show_rating' => false
        ]);

        $this->assertEquals(
            [],
            // ['Show rating' => 'Disabled'],
            $display_settings
        );

        $display_settings = competency_assignment::get_display_settings([
            // 'show_rating' => true
        ]);

        $this->assertEquals(
            [],
            // ['Show rating' => 'Enabled'],
            $display_settings
        );
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