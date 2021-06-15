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

use core\collection;
use core\date_format;
use core\webapi\formatter\field\date_field_formatter;
use core_phpunit\testcase;
use hierarchy_goal\entity\company_goal;
use hierarchy_goal\entity\company_goal_assignment as company_goal_assignment_entity;
use hierarchy_goal\entity\personal_goal as personal_goal_entity;
use hierarchy_goal\entity\scale_value;
use hierarchy_goal\performelement_linked_review\company_goal_assignment;
use hierarchy_goal\performelement_linked_review\goal_assignment_content_type;
use hierarchy_goal\performelement_linked_review\personal_goal_assignment;
use hierarchy_goal\personal_goal_assignment_type;
use mod_perform\constants;
use mod_perform\entity\activity\element as element_entity;
use mod_perform\entity\activity\participant_instance;
use mod_perform\entity\activity\participant_section;
use mod_perform\entity\activity\section_relationship;
use mod_perform\entity\activity\subject_instance as subject_instance_entity;
use mod_perform\models\activity\element;
use mod_perform\models\activity\participant_instance as participant_instance_model;
use mod_perform\models\activity\section;
use mod_perform\models\activity\section_element;
use mod_perform\models\activity\subject_instance;
use mod_perform\testing\generator as perform_generator;
use performelement_linked_review\content_type_factory;
use performelement_linked_review\models\linked_review_content;
use performelement_linked_review\models\linked_review_content as linked_review_content_model;
use performelement_linked_review\testing\generator as linked_review_generator;
use totara_core\advanced_feature;
use totara_core\relationship\relationship;
use totara_hierarchy\testing\generator as hierarchy_generator;
use totara_job\job_assignment;

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

    /**
     * @return string[][]
     */
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

    /**
     * @return string[][]
     */
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

    /**
     * @dataProvider goal_content_type_data_provider
     * @param string|goal_assignment_content_type $goal_content_type_class
     */
    public function test_load_with_empty_content_items_collection(string $goal_content_type_class): void {
        $user = self::getDataGenerator()->create_user();
        $dummy_subject_instance = subject_instance::load_by_entity(new subject_instance_entity([
            'id' => 123456,
            'subject_user_id' => $user->id,
        ]));

        self::setUser($user);

        $content_type = new $goal_content_type_class(context_system::instance());

        $result = $content_type->load_content_items(
            $dummy_subject_instance,
            collection::new([]),
            null,
            true,
            time()
        );

        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    public function test_load_personal_goal_items(): void {
        [$user, $goal1, $goal2] = $this->create_personal_goals();

        $dummy_subject_instance = subject_instance::load_by_entity(new subject_instance_entity([
            'id' => 123456,
            'subject_user_id' => $user->id,
        ]));
        self::setUser($user);

        $created_at = time();

        $content_items = collection::new([
            ['content_id' => $goal1->id],
            ['content_id' => - 123],
            ['content_id' => $goal2->id],
        ]);

        $content_type = new personal_goal_assignment(context_system::instance());

        $result = $content_type->load_content_items(
            $dummy_subject_instance,
            $content_items,
            null,
            true,
            $created_at
        );

        self::assertIsArray($result);
        self::assertCount(2, $result);

        $goal1_result_item = array_filter($result, static function (array $item) use ($goal1) {
            return (int)$item['id'] === (int)$goal1->id;
        });
        $goal1_result_item = array_shift($goal1_result_item);

        /** @var personal_goal_entity $expected_goal1 */
        $expected_goal1 = personal_goal_entity::repository()->find($goal1->id);

        // Goal1 has a scale and a scale value.
        $expected_scale_values1 = $expected_goal1
            ->scale
            ->values
            ->sort('sortorder', 'desc', false);
        $expected_scale1 = [];
        foreach ($expected_scale_values1 as $expected_scale_value) {
            $expected_scale1[] = [
                'id' => $expected_scale_value->id,
                'name' => $expected_scale_value->name,
                'proficient' => (bool) $expected_scale_value->proficient,
                'sort_order' => $expected_scale_value->sortorder,
            ];
        }

        $formatted_targetdate = (new date_field_formatter(date_format::FORMAT_DATE, context_system::instance()))
            ->format($goal1->targetdate);

        $expected_content_goal1 = [
            'id' => $goal1->id,
            'goal' => [
                'id' => $goal1->id,
                'display_name' => $goal1->name,
                'description' => $goal1->description,
            ],
            'status' => [
                'id' => $goal1->scalevalueid,
                'name' => 'Created',
            ],
            'scale_values' => $expected_scale1,
            'target_date' => $formatted_targetdate,
            'can_change_status' => false,
            'can_view_status' => true,
            'status_change' => [ // TODO update this when implementing changing status
                'id' => $goal1->scalevalueid,
                'name' => 'Created',
            ],
        ];
        self::assertEquals($expected_content_goal1, $goal1_result_item);

        $goal2_result_item = array_filter($result, static function (array $item) use ($goal2) {
            return (int)$item['id'] === (int)$goal2->id;
        });
        $goal2_result_item = array_shift($goal2_result_item);

        $formatted_targetdate = (new date_field_formatter(date_format::FORMAT_DATE, context_system::instance()))
            ->format($goal2->targetdate);

        // Goal2 doesn't have a scale.
        $expected_content_goal2 = [
            'id' => $goal2->id,
            'goal' => [
                'id' => $goal2->id,
                'display_name' => $goal2->name,
                'description' => $goal2->description,
            ],
            'status' => null,
            'scale_values' => null,
            'target_date' => $formatted_targetdate,
            'can_change_status' => false,
            'can_view_status' => true,
            'status_change' => null,
        ];
        self::assertEquals($expected_content_goal2, $goal2_result_item);
    }

    public function test_load_company_goal_items(): void {
        [$user, $goal1, $goal2] = $this->create_company_goals();

        $dummy_subject_instance_user_1 = subject_instance::load_by_entity(new subject_instance_entity([
            'id' => 123456,
            'subject_user_id' => $user->id,
        ]));
        self::setUser($user);

        $created_at = time();
        /** @var company_goal_assignment_entity $goal_assignment_goal1 */
        $goal_assignment_goal1 = company_goal_assignment_entity::repository()
            ->where('userid', $user->id)
            ->where('goalid', $goal1->id)
            ->one(true);
        /** @var company_goal_assignment_entity $goal_assignment_goal2 */
        $goal_assignment_goal2 = company_goal_assignment_entity::repository()
            ->where('userid', $user->id)
            ->where('goalid', $goal2->id)
            ->one(true);

        $content_items = collection::new([
            ['content_id' => $goal_assignment_goal1->id],
            ['content_id' => - 123],
            ['content_id' => $goal_assignment_goal2->id],
        ]);

        $content_type = new company_goal_assignment(context_system::instance());

        $result = $content_type->load_content_items(
            $dummy_subject_instance_user_1,
            $content_items,
            null,
            true,
            $created_at
        );

        self::assertIsArray($result);
        self::assertCount(2, $result);

        $goal1_result_item = array_filter($result, static function (array $item) use ($goal_assignment_goal1) {
            return (int)$item['id'] === (int)$goal_assignment_goal1->id;
        });
        $goal1_result_item = array_shift($goal1_result_item);

        /** @var company_goal $expected_goal1 */
        $expected_scale_values1 = $goal_assignment_goal1
            ->scale_value
            ->scale
            ->values
            ->sort('sortorder', 'desc', false);
        $expected_scale1 = [];
        foreach ($expected_scale_values1 as $expected_scale_value) {
            $expected_scale1[] = [
                'id' => $expected_scale_value->id,
                'name' => $expected_scale_value->name,
                'proficient' => (bool) $expected_scale_value->proficient,
                'sort_order' => $expected_scale_value->sortorder,
            ];
        }

        $formatted_target_date = (new date_field_formatter(date_format::FORMAT_DATE, context_system::instance()))
            ->format($goal1->targetdate);

        $expected_content_goal1 = [
            'id' => $goal_assignment_goal1->id,
            'goal' => [
                'id' => $goal_assignment_goal1->id,
                'display_name' => $goal1->fullname,
                'description' => $goal1->description,
            ],
            'status' => [
                'id' => $goal_assignment_goal1->scalevalueid,
                'name' => 'Started',
            ],
            'scale_values' => $expected_scale1,
            'target_date' => $formatted_target_date,
            'can_change_status' => false,
            'can_view_status' => true,
            'status_change' => [ // TODO update this when implementing changing status
                'id' => $goal_assignment_goal1->scalevalueid,
                'name' => 'Started',
            ],
        ];
        self::assertEquals($expected_content_goal1, $goal1_result_item);
    }

    public function test_get_goal_status_permissions(): void {
        $data = $this->create_activity_data();
        $participant_instance = participant_instance_model::load_by_entity($data->manager_participant_instance1);
        $subject_relationship = relationship::load_by_idnumber('subject')->id;
        $manager_relationship = relationship::load_by_idnumber('manager')->id;
        self::setUser($data->manager_user);

        $element = new element_entity($data->section_element->element_id);
        $element_data = [
            'content_type' => 'totara_competency',
            'content_type_settings' => [
                'enable_status_change' => false,
                'status_change_relationship' => null,
            ],
            'selection_relationships' => [$subject_relationship],
        ];
        $element->data = json_encode($element_data);
        $element->save();

        $content_items = linked_review_content_model::get_existing_selected_content(
            $data->section_element->id,
            $data->subject_instance1->id
        );

        // Can't view status as false is passed in for 'view other responses'.
        // Can't change status as it is disabled on the element.
        [$can_view, $can_change] = goal_assignment_content_type::get_goal_status_permissions(
            $content_items,
            $data->manager_participant_section1,
            false
        );
        $this->assertFalse($can_view);
        $this->assertFalse($can_change);

        // Can view status as true is passed in for 'view other responses'.
        [$can_view, $can_change] = goal_assignment_content_type::get_goal_status_permissions(
            $content_items,
            $data->manager_participant_section1,
            true
        );
        $this->assertTrue($can_view);
        $this->assertFalse($can_change);

        $element_data['content_type_settings']['enable_status_change'] = true;
        $element_data['content_type_settings']['status_change_relationship'] = $subject_relationship;
        $element->data = json_encode($element_data);
        $element->save();
        // Refresh content items, so they include the updated element data.
        $content_items = linked_review_content_model::get_existing_selected_content(
            $data->section_element->id,
            $data->subject_instance1->id
        );

        // Can't change status as user is not of the status_change_relationship
        [$can_view, $can_change] = goal_assignment_content_type::get_goal_status_permissions(
            $content_items,
            $data->manager_participant_section1,
            true
        );
        $this->assertTrue($can_view);
        $this->assertFalse($can_change);

        $element_data['content_type_settings']['enable_status_change'] = true;
        $element_data['content_type_settings']['status_change_relationship'] = $manager_relationship;
        $element->data = json_encode($element_data);
        $element->save();
        $content_items = linked_review_content_model::get_existing_selected_content(
            $data->section_element->id,
            $data->subject_instance1->id
        );

        // Can change status as it is the correct relationship
        [$can_view, $can_change] = goal_assignment_content_type::get_goal_status_permissions(
            $content_items,
            $data->manager_participant_section1,
            true
        );
        $this->assertTrue($can_view);
        $this->assertTrue($can_change);

        // Can view status even when passing in false for viewing other responses.
        [$can_view, $can_change] = goal_assignment_content_type::get_goal_status_permissions(
            $content_items,
            $data->manager_participant_section1,
            false
        );
        $this->assertTrue($can_view);
        $this->assertTrue($can_change);

        $section_relationship = section_relationship::repository()
            ->where('core_relationship_id', $participant_instance->core_relationship_id)
            ->where('section_id', $data->section_element->section_id)
            ->get()
            ->first();
        $section_relationship->delete();
        $content_items = linked_review_content_model::get_existing_selected_content(
            $data->section_element->id,
            $data->subject_instance1->id
        );

        // Can't change status as the relationship doesn't exist on the section.
        [$can_view, $can_change] = goal_assignment_content_type::get_goal_status_permissions(
            $content_items,
            $data->manager_participant_section1,
            true
        );
        $this->assertTrue($can_view);
        $this->assertFalse($can_change);

        // Re-create relationship.
        $section_relationship = new section_relationship($section_relationship->to_array());
        $section_relationship->save();
        $content_items = linked_review_content_model::get_existing_selected_content(
            $data->section_element->id,
            $data->subject_instance1->id
        );
        [$can_view, $can_change] = goal_assignment_content_type::get_goal_status_permissions(
            $content_items,
            $data->manager_participant_section1,
            true
        );
        $this->assertTrue($can_view);
        $this->assertTrue($can_change);

        // Can't change status as there is no participant section record.
        participant_section::repository()->delete();
        $content_items = linked_review_content_model::get_existing_selected_content(
            $data->section_element->id,
            $data->subject_instance1->id
        );
        [$can_view, $can_change] = goal_assignment_content_type::get_goal_status_permissions(
            $content_items,
            $data->manager_participant_section1,
            true
        );
        $this->assertTrue($can_view);
        $this->assertFalse($can_change);
    }

    /**
     * @param stdClass|null $user
     * @return array
     */
    private function create_personal_goals(stdClass $user = null): array {
        self::setAdminUser();

        $generator = self::getDataGenerator();
        /** @var hierarchy_generator $hierarchy_generator */
        $hierarchy_generator = $generator->get_plugin_generator('totara_hierarchy');

        $type = personal_goal_assignment_type::self()->get_value();

        $user = $user ?? $generator->create_user();

        $scale = $this->create_scale($hierarchy_generator);
        $goal1 = $hierarchy_generator->create_personal_goal($user->id, [
            'name' => "goal1",
            'assigntype' => $type,
            'scaleid' => $scale->id,
            'scalevalueid' => scale_value::repository()->where('name', 'Created')->one(true)->id
        ]);

        // goal2 doesn't have a scale.
        $goal2 = $hierarchy_generator->create_personal_goal($user->id, [
            'name' => "goal1",
            'assigntype' => $type
        ]);

        return [$user, $goal1, $goal2];
    }

    /**
     * @param hierarchy_generator $hierarchy_generator
     * @return stdClass
     */
    private function create_scale(hierarchy_generator $hierarchy_generator): stdClass {
        $scale_values = [
            1 => ['name' => 'Finished', 'proficient' => 1, 'sortorder' => 1, 'default' => 0],
            2 => ['name' => 'Started', 'proficient' => 0, 'sortorder' => 2, 'default' => 0],
            3 => ['name' => 'Created', 'proficient' => 0, 'sortorder' => 3, 'default' => 1]
        ];
        return $hierarchy_generator->create_scale('goal', ['name' => 'goal_scale1'], $scale_values);
    }

    /**
     * @return array
     */
    private function create_company_goals(): array {
        $generator = self::getDataGenerator();
        /** @var hierarchy_generator $hierarchy_generator */
        $hierarchy_generator = $generator->get_plugin_generator('totara_hierarchy');
        $user1 = $generator->create_user();
        $user2 = $generator->create_user();

        $scale = $this->create_scale($hierarchy_generator);
        $framework = $hierarchy_generator->create_goal_frame(['name' => 'frame1', 'scaleid' => $scale->id]);

        // Assign just user1.
        $goal1 = $hierarchy_generator->create_goal(['fullname' => 'goal1', 'frameworkid' => $framework->id]);
        $goal2 = $hierarchy_generator->create_goal(['fullname' => 'goal2', 'frameworkid' => $framework->id]);
        $hierarchy_generator->goal_assign_individuals($goal1->id, [$user1->id, $user2->id]);
        $hierarchy_generator->goal_assign_individuals($goal2->id, [$user1->id, $user2->id]);

        // Update the scale value for user1.
        $hierarchy_generator->update_company_goal_user_scale_value(
            $user1->id,
            $goal1->id,
            scale_value::repository()->where('name', 'Started')->one(true)->id
        );

        return [$user1, $goal1, $goal2];
    }

    protected function create_activity_data() {
        self::setAdminUser();

        $another_user = self::getDataGenerator()->create_user(['firstname' => 'Another', 'lastname' => 'User']);
        $manager_user = self::getDataGenerator()->create_user(['firstname' => 'Manager', 'lastname' => 'User']);
        $subject_user = self::getDataGenerator()->create_user(['firstname' => 'Subject', 'lastname' => 'User']);

        /** @var job_assignment $manager_ja */
        $manager_ja = job_assignment::create([
            'userid' => $manager_user->id,
            'idnumber' => 'ja02',
        ]);

        job_assignment::create([
            'userid' => $subject_user->id,
            'idnumber' => 'ja01',
            'managerjaid' => $manager_ja->id
        ]);

        [, $goal1, $goal2] = $this->create_personal_goals($subject_user);

        $perform_generator = perform_generator::instance();
        $activity = $perform_generator->create_activity_in_container(['activity_name' => 'Test activity']);
        $section = $perform_generator->create_section($activity);
        $manager_section_relationship = $perform_generator->create_section_relationship(
            $section,
            ['relationship' => constants::RELATIONSHIP_MANAGER]
        );
        $subject_section_relationship = $perform_generator->create_section_relationship(
            $section,
            ['relationship' => constants::RELATIONSHIP_SUBJECT]
        );
        $element = element::create($activity->get_context(), 'linked_review', 'title', '', json_encode([
            'content_type' => 'personal_goal',
            'content_type_settings' => [
                'enable_status_change' => true,
                'status_change_relationship' => $perform_generator->get_core_relationship(constants::RELATIONSHIP_MANAGER)->id
            ],
            'selection_relationships' => [$manager_section_relationship->core_relationship_id],
        ]));

        $section_element = $perform_generator->create_section_element($section, $element);

        $subject_instance1 = $perform_generator->create_subject_instance([
            'activity_id' => $activity->id,
            'subject_user_id' => $subject_user->id
        ]);

        $subject_instance2 = $perform_generator->create_subject_instance([
            'activity_id' => $activity->id,
            'subject_user_id' => $subject_user->id
        ]);

        $manager_participant_section1 = $perform_generator->create_participant_instance_and_section(
            $activity,
            $manager_user,
            $subject_instance1->id,
            $section,
            $manager_section_relationship->core_relationship->id
        );

        $subject_participant_section1 = $perform_generator->create_participant_instance_and_section(
            $activity,
            $subject_user,
            $subject_instance1->id,
            $section,
            $subject_section_relationship->core_relationship->id
        );
        $subject_participant_section2 = $perform_generator->create_participant_instance_and_section(
            $activity,
            $subject_user,
            $subject_instance2->id,
            $section,
            $subject_section_relationship->core_relationship->id
        );

        $linked_assignment1 = linked_review_content::create(
            $goal1->id, $section_element->id, $subject_participant_section1->participant_instance_id, false
        );
        $linked_assignment2 = linked_review_content::create(
            $goal1->id, $section_element->id, $subject_participant_section2->participant_instance_id, false
        );

        $data = new class {
            public $another_user;
            public $manager_user;
            public $subject_user;
            /** @var subject_instance_entity $subject_instance1 */
            public $subject_instance1;
            /** @var participant_section */
            public $manager_participant_section1;
            /** @var participant_instance */
            public $manager_participant_instance1;
            /** @var section_element */
            public $section_element;
            /** @var section */
            public $section;
            /** @var linked_review_content */
            public $linked_assignment1;
            /** @var linked_review_content */
            public $linked_assignment2;
            public $goal1;
            public $goal2;
        };

        $data->another_user = $another_user;
        $data->manager_user = $manager_user;
        $data->subject_user = $subject_user;
        $data->subject_instance1 = $subject_instance1;
        $data->manager_participant_instance1 = $manager_participant_section1->participant_instance;
        $data->manager_participant_section1 = $manager_participant_section1;
        $data->section_element = $section_element;
        $data->section = $section;
        $data->linked_assignment1 = $linked_assignment1;
        $data->linked_assignment2 = $linked_assignment2;
        $data->goal1 = $goal1;
        $data->goal2 = $goal2;

        return $data;
    }
}