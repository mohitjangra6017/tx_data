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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Fabian Derschatta <fabian.derschatta@totaralearning.com>
 * @package totara_competency
 */

use core\collection;
use core\orm\query\builder;
use mod_perform\entity\activity\subject_instance as subject_instance_entity;
use mod_perform\models\activity\subject_instance;
use totara_competency\expand_task;
use totara_competency\models\assignment as assignment_model;
use totara_competency\performelement_linked_review\competency_assignment;
use totara_competency\testing\generator as competency_generator;
use totara_core\advanced_feature;
use totara_core\relationship\relationship;

/**
 * @group totara_competency
 */
class totara_competency_perform_linked_competencies_content_testcase extends advanced_testcase {

    public function test_load_with_empty_content_items_collection() {
        $user = $this->getDataGenerator()->create_user();
        $dummy_subject_instance = subject_instance::load_by_entity(new subject_instance_entity([
            'id' => 123456,
            'subject_user_id' => $user->id,
        ]));

        $this->setUser($user);

        $content_type = new competency_assignment(context_system::instance());

        $result = $content_type->load_content_items($dummy_subject_instance, collection::new([]), null, time());

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_load_competency_items_which_do_not_exist() {
        $user = $this->getDataGenerator()->create_user();
        $dummy_subject_instance = subject_instance::load_by_entity(new subject_instance_entity([
            'id' => 123456,
            'subject_user_id' => $user->id,
        ]));
        $nonexistent_competency_content_items = collection::new([
            ['content_id' => -1],
            ['content_id' => -2],
        ]);

        $this->setUser($user);

        $content_type = new competency_assignment(context_system::instance());

        $result = $content_type->load_content_items($dummy_subject_instance, $nonexistent_competency_content_items, null, time());

        $this->assertEmpty($result);
    }

    public function test_load_competency_items() {
        $user1 = $this->getDataGenerator()->create_user();
        $dummy_subject_instance = subject_instance::load_by_entity(new subject_instance_entity([
            'id' => 123456,
            'subject_user_id' => $user1->id,
        ]));

        $competency1 = $this->generator()->create_competency();
        $competency2 = $this->generator()->create_competency();

        $assignment_generator = $this->generator()->assignment_generator();
        $assignment1 = $assignment_generator->create_user_assignment($competency1->id, $user1->id);
        $assignment2 = $assignment_generator->create_user_assignment($competency2->id, $user1->id);

        (new expand_task(builder::get_db()))->expand_all();

        $this->setUser($user1);

        $created_at = time();

        $content_items = collection::new([
            ['content_id' => $assignment1->id],
            ['content_id' => 666],
            ['content_id' => $assignment2->id],
        ]);

        $content_type = new competency_assignment(context_system::instance());

        $result = $content_type->load_content_items($dummy_subject_instance, $content_items, null, $created_at);

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
            'can_rate' => false,
            'rating' => null,
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
            'can_rate' => false,
            'rating' => null,
        ];

        $this->assertEquals($expected_content_third_item, $actual_third_item);
    }

    public function test_feature_disabled() {
        $user1 = $this->getDataGenerator()->create_user();
        $dummy_subject_instance = subject_instance::load_by_entity(new subject_instance_entity([
            'id' => 123456,
            'subject_user_id' => $user1->id,
        ]));

        $competency1 = $this->generator()->create_competency();
        $competency2 = $this->generator()->create_competency();

        $assignment_generator = $this->generator()->assignment_generator();
        $assignment1 = $assignment_generator->create_user_assignment($competency1->id, $user1->id);
        $assignment2 = $assignment_generator->create_user_assignment($competency2->id, $user1->id);

        (new expand_task(builder::get_db()))->expand_all();

        $this->setUser($user1);

        $content_items = collection::new([
            ['content_id' => $assignment1->id],
            ['content_id' => 666],
            ['content_id' => $assignment2->id],
        ]);

        advanced_feature::disable('competency_assignment');

        $content_type = new competency_assignment(context_system::instance());
        $result = $content_type->load_content_items($dummy_subject_instance, $content_items, null, time());

        $this->assertEmpty($result);
    }

    public function test_get_display_settings() {
        $display_settings = competency_assignment::get_display_settings([]);
        $subject_relationship = relationship::load_by_idnumber('subject');

        $this->assertEquals(
            [get_string('enable_performance_rating', 'totara_competency') => get_string('no')],
            $display_settings
        );

        $display_settings = competency_assignment::get_display_settings([
            'enable_rating' => false
        ]);

        $this->assertEquals(
            [get_string('enable_performance_rating', 'totara_competency') => get_string('no')],
            $display_settings
        );

        $display_settings = competency_assignment::get_display_settings([
            'enable_rating' => true
        ]);

        $this->assertEquals(
            [get_string('enable_performance_rating', 'totara_competency') => get_string('yes')],
            $display_settings
        );

        $display_settings = competency_assignment::get_display_settings([
            'enable_rating' => true,
            'rating_relationship' => $subject_relationship->id,
        ]);

        $this->assertEquals(
            [
                get_string('enable_performance_rating', 'totara_competency') => get_string('yes'),
                get_string('enable_performance_rating_participant', 'totara_competency') => $subject_relationship->get_name(),
            ],
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