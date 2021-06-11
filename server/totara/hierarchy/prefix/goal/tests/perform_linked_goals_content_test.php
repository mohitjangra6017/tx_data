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
use hierarchy_goal\performelement_linked_review\company_goal_assignment;
use hierarchy_goal\performelement_linked_review\goal_assignment_content_type;
use hierarchy_goal\performelement_linked_review\personal_goal_assignment;
use mod_perform\constants;
use performelement_linked_review\content_type_factory;
use performelement_linked_review\testing\generator as linked_review_generator;
use totara_core\advanced_feature;
use totara_core\relationship\relationship;

/**
 * @group hierarchy_goal
 */
class hierarchy_goal_perform_linked_goals_content_testcase extends testcase {

    protected function setUp(): void {
        if (!core_component::get_plugin_directory('mod', 'perform')
            || !core_component::get_plugin_directory('performelement', 'linked_review')
        ) {
            self::markTestSkipped('Perform or the linked review element plugin is not installed');
        }
        parent::setUp();
    }

    public function goal_content_type_data_provider(): array {
        return [
            [personal_goal_assignment::class],
            [company_goal_assignment::class],
        ];
    }

    /**
     * @dataProvider goal_content_type_data_provider
     * @param string|goal_assignment_content_type $goal_content_type_class
     */
    public function test_get_display_settings(string $goal_content_type_class): void {
        $display_settings = $goal_content_type_class::get_display_settings([]);
        $subject_relationship = relationship::load_by_idnumber('subject');

        self::assertEquals(
            ['Ability to change goal status during activity' => 'No'],
            $display_settings
        );

        $display_settings = $goal_content_type_class::get_display_settings([
            'enable_status_change' => false
        ]);

        self::assertEquals(
            ['Ability to change goal status during activity' => 'No'],
            $display_settings
        );

        $display_settings = $goal_content_type_class::get_display_settings([
            'enable_status_change' => true
        ]);

        self::assertEquals(
            ['Ability to change goal status during activity' => 'Yes'],
            $display_settings
        );

        $display_settings = $goal_content_type_class::get_display_settings([
            'enable_status_change' => true,
            'status_change_relationship' => $subject_relationship->id,
        ]);

        self::assertEquals(
            [
                'Ability to change goal status during activity' => 'Yes',
                'Change of goal status participant' => $subject_relationship->get_name(),
            ],
            $display_settings
        );
    }

    public function test_is_enabled(): void {
        self::assertTrue(personal_goal_assignment::is_enabled());
        self::assertTrue(company_goal_assignment::is_enabled());

        $enabled_content_types = content_type_factory::get_all_enabled();
        self::assertContainsEquals(personal_goal_assignment::class, $enabled_content_types->to_array());
        self::assertContainsEquals(company_goal_assignment::class, $enabled_content_types->to_array());

        advanced_feature::disable('goals');

        self::assertFalse(personal_goal_assignment::is_enabled());
        self::assertFalse(company_goal_assignment::is_enabled());

        $enabled_content_types = content_type_factory::get_all_enabled();
        self::assertNotContainsEquals(personal_goal_assignment::class, $enabled_content_types->to_array());
        self::assertNotContainsEquals(company_goal_assignment::class, $enabled_content_types->to_array());
    }

    public function goal_content_type_names_data_provider(): array {
        return [
            ['personal_goal', 'Personal Goal'],
            ['company_goal', 'Company Goal'],
        ];
    }

    /**
     * @dataProvider goal_content_type_names_data_provider
     * @param string $content_type_identifier
     * @param string $content_type_display_name
     */
    public function test_saving_element(string $content_type_identifier, string $content_type_display_name): void {
        $subject_relationship = relationship::load_by_idnumber(constants::RELATIONSHIP_SUBJECT);
        $manager_relationship = relationship::load_by_idnumber(constants::RELATIONSHIP_MANAGER);

        $element1_input_data = [
            'content_type' => $content_type_identifier,
            'content_type_settings' => [
                'enable_status_change' => false,
                'status_change_relationship' => null,
            ],
            'selection_relationships' => [$subject_relationship->id],
        ];
        $element1 = linked_review_generator::instance()->create_linked_review_element($element1_input_data);
        $element1_output_data = json_decode($element1->data, true);
        unset($element1_output_data['components'], $element1_output_data['compatible_child_element_plugins']);
        self::assertEquals([
            'content_type' => $content_type_identifier,
            'content_type_settings' => [
                'enable_status_change' => false,
                'status_change_relationship' => null,
            ],
            'selection_relationships' => [$subject_relationship->id],
            'selection_relationships_display' => [
                [
                    'id' => $subject_relationship->id,
                    'name' => $subject_relationship->name
                ],
            ],
            'content_type_display' => $content_type_display_name,
            'content_type_settings_display' => [
                [
                    'title' => 'Ability to change goal status during activity',
                    'value' => 'No',
                ],
            ]
        ], $element1_output_data);

        $element2_input_data = [
            'content_type' => $content_type_identifier,
            'content_type_settings' => [
                'enable_status_change' => true,
                'status_change_relationship' => $manager_relationship->id,
            ],
            'selection_relationships' => [$manager_relationship->id],
        ];
        $element2 = linked_review_generator::instance()->create_linked_review_element($element2_input_data);
        $element2_output_data = json_decode($element2->data, true);
        unset($element2_output_data['components'], $element2_output_data['compatible_child_element_plugins']);
        self::assertEquals([
            'content_type' => $content_type_identifier,
            'content_type_settings' => [
                'enable_status_change' => true,
                'status_change_relationship' => $manager_relationship->id,
                'status_change_relationship_name' => $manager_relationship->get_name(),
            ],
            'selection_relationships' => [$manager_relationship->id],
            'selection_relationships_display' => [
                [
                    'id' => $manager_relationship->id,
                    'name' => $manager_relationship->name
                ],
            ],
            'content_type_display' => $content_type_display_name,
            'content_type_settings_display' => [
                [
                    'title' => 'Ability to change goal status during activity',
                    'value' => 'Yes',
                ],
                [
                    'title' => 'Change of goal status participant',
                    'value' => $manager_relationship->name,
                ],
            ]
        ], $element2_output_data);
    }
}