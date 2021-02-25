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
 * @author Riana Rossouw <riana.rossouw@totaralearning.com>
 * @package mod_perform
 * @category test
 */

use mod_perform\constants;
use mod_perform\entity\activity\element_response as element_response_entity;
use mod_perform\entity\activity\participant_instance as participant_instance_entity;
use mod_perform\models\activity\activity as activity_model;
use mod_perform\models\response\participant_section as participant_section_model;
use mod_perform\state\activity\active;
use performelement_aggregation\aggregation;
use performelement_aggregation\calculations\average;
use performelement_custom_rating_scale\custom_rating_scale;
use performelement_numeric_rating_scale\numeric_rating_scale;

/**
 * @group perform
 */
class mod_perform_derived_responses_element_plugin_testcase extends advanced_testcase {

    public function test_participant_section_derived_responses_observer_separate_sections(): void {
        self::setAdminUser();

        $generator = \mod_perform\testing\generator::instance();

        $subject_user = self::getDataGenerator()->create_user();
        $subject_user_id = $subject_user->id;
        $manager_user = self::getDataGenerator()->create_user();
        $manager_user_id = $manager_user->id;

        $subject_instance = $generator->create_subject_instance([
            'activity_name' => 'Test aggregation',
            'activity_status' => active::get_code(),
            'subject_user_id' => $subject_user_id,
            'other_participant_id' => $manager_user_id,
            'relationships_can_view' => 'subject, manager',
            'relationships_can_answer' => 'subject, manager',
            'subject_is_participating' => true,
            'include_questions' => false,
        ]);
        $participant_instances = participant_instance_entity::repository()->get();
        $subject_participant_instance = $participant_instances->find(function (participant_instance_entity $instance) use ($subject_user_id){
            return $instance->participant_id == $subject_user_id;
        });
        $manager_participant_instance = $participant_instances->find(function (participant_instance_entity $instance) use ($manager_user_id){
            return $instance->participant_id == $manager_user_id;
        });

        $activity = activity_model::load_by_entity($subject_instance->activity());
        $subject_section = $generator->create_section($activity, ['title' => 'Subject section']);
        $q1_element = $generator->create_element([
            'plugin_name' => numeric_rating_scale::get_plugin_name(),
            'data' => json_encode([
                'defaultValue' => 1,
                'highValue' => 10,
                'lowValue' => 1,
            ], JSON_THROW_ON_ERROR),
        ]);
        $q2_element = $generator->create_element([
            'plugin_name' => custom_rating_scale::get_plugin_name(),
            'data' => json_encode([
                'options' => [
                    [
                        'name' => 'option_1',
                        'value' => ['text' => 'Option 1', 'score' => '1']
                    ],
                    [
                        'name' => 'option_3',
                        'value' => ['text' => 'Option 2', 'score' => '3']
                    ],
                    [
                        'name' => 'option_5',
                        'value' => ['text' => 'Option 3', 'score' => '5']
                    ],
                    [
                        'name' => 'option_7',
                        'value' => ['text' => 'Option 4', 'score' => '7']
                    ],
                    [
                        'name' => 'option_9',
                        'value' => ['text' => 'Option 5', 'score' => '9']
                    ],
                ]
            ], JSON_THROW_ON_ERROR),
        ]);
        $q1_section_element = $generator->create_section_element($subject_section, $q1_element);
        $q2_section_element = $generator->create_section_element($subject_section, $q2_element);
        $generator->create_section_relationship(
            $subject_section,
            ['relationship' => constants::RELATIONSHIP_SUBJECT],
            true,
            true
        );
        $subject_participant_section = $generator->create_participant_section($activity, $subject_participant_instance, false, $subject_section);
        $subject_participant_section_model = participant_section_model::load_by_entity($subject_participant_section);

        $manager_section = $generator->create_section($activity, ['title' => 'Manager section']);
        $aggregation_element = $generator->create_element([
            'plugin_name' => 'aggregation',
            'data' => json_encode([
                aggregation::SOURCE_SECTION_ELEMENT_IDS => [$q1_section_element->id, $q2_section_element->id],
                aggregation::CALCULATIONS => [average::get_name()],
                aggregation::EXCLUDED_VALUES => [],
            ], JSON_THROW_ON_ERROR),
        ]);
        $aggregation_section_element = $generator->create_section_element($manager_section, $aggregation_element);
        $generator->create_section_relationship(
            $manager_section,
            ['relationship' => constants::RELATIONSHIP_MANAGER],
            true,
            true
        );
        $manager_participant_section = $generator->create_participant_section($activity, $manager_participant_instance, false, $manager_section);

        // No responses yet - so there should not yet be an aggregated response
        $aggregated_responses = element_response_entity::repository()
            ->where_in('participant_instance_id', [$subject_participant_instance->id, $manager_participant_instance->id])
            ->where('section_element_id', $aggregation_section_element->id)
            ->get();
        self::assertEmpty($aggregated_responses);

        // Add responses
        // $subject_participant_section_model->progress_status
        $q1_response = new element_response_entity();
        $q1_response->participant_instance_id = $subject_participant_instance->id;
        $q1_response->section_element_id = $q1_section_element->id;
        $q1_response->response_data = json_encode(2, JSON_THROW_ON_ERROR);
        $q1_response->save();

        $q2_response = new element_response_entity();
        $q2_response->participant_instance_id = $subject_participant_instance->id;
        $q2_response->section_element_id = $q2_section_element->id;
        $q2_response->response_data = json_encode('option_7', JSON_THROW_ON_ERROR);
        $q2_response->save();

        $subject_participant_section_model->complete();
        
        // This should result in an average response for the subject
        $manager_aggregated_responses = element_response_entity::repository()
            ->where('participant_instance_id', $manager_participant_instance->id)
            ->where('section_element_id', $aggregation_section_element->id)
            ->get();
        self::assertEmpty($manager_aggregated_responses);

        $subject_aggregated_responses = element_response_entity::repository()
            ->where('participant_instance_id', $subject_participant_instance->id)
            ->where('section_element_id', $aggregation_section_element->id)
            ->get();
        self::assertCount(1, $subject_aggregated_responses);
        $subject_aggregated_response = $subject_aggregated_responses->first();
        $expected = json_encode([average::get_name() => 4], JSON_THROW_ON_ERROR);
        self::assertSame($expected, $subject_aggregated_response->response_data);
    }

}