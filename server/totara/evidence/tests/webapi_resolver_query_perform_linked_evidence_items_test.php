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
 * @author Marco Song <marco.song@totaralearning.com>
 * @package totara_evidence
 */

use mod_perform\constants;
use mod_perform\entity\activity\participant_instance;
use mod_perform\models\activity\activity;
use mod_perform\models\activity\element;
use mod_perform\models\activity\section_element;
use performelement_linked_review\models\linked_review_content;
use totara_core\advanced_feature;
use totara_core\feature_not_available_exception;
use totara_evidence\models\evidence_item;
use totara_webapi\phpunit\webapi_phpunit_helper;

class webapi_resolver_query_perform_linked_evidence_items_testcase extends advanced_testcase {

    private const QUERY = "totara_evidence_perform_linked_evidence_items";

    use webapi_phpunit_helper;

    public function test_query_linked_evidences_successful() {
        $data = $this->create_test_data();

        $input = [
            'section_element_id' => $data->section_element->id,
            'participant_instance_id' => $data->participant_instance->id,
        ];

        $result = $this->resolve_graphql_query(self::QUERY, $input);
        $this->assertCount(1, $result);
    }

    public function test_feature_disabled() {
        $data = $this->create_test_data();

        $input = [
            'section_element_id' => $data->section_element->id,
            'participant_instance_id' => $data->participant_instance->id,
        ];

        advanced_feature::disable('evidence');

        $this->expectException(feature_not_available_exception::class);
        $this->expectExceptionMessage('Feature evidence is not available.');

        $this->resolve_graphql_query(self::QUERY, $input);
    }

    /**
     * Create test data
     *
     * @return stdClass
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    private function create_test_data() {
        $this->setAdminUser();

        $perform_generator = $this->getDataGenerator()->get_plugin_generator('mod_perform');

        $section_element = $this->create_section_element($perform_generator);

        $participant_instance = $this->create_participant_instance($perform_generator);

        $linked_review_contents = $this->create_linked_review_content($section_element, $participant_instance);

        $data = new stdClass();
        $data->section_element = $section_element;
        $data->participant_instance = $participant_instance;
        $data->linked_review_contents = $linked_review_contents;

        return $data;
    }

    /**
     * Create section element
     *
     * @param $perform_generator
     * @return section_element
     * @throws dml_exception
     * @throws moodle_exception
     */
    private function create_section_element($perform_generator): section_element {
        $activity = $perform_generator->create_activity_in_container();
        $section = $perform_generator->create_section($activity);

        $element_input_data = [
            'content_type' => 'totara_evidence',
            'content_type_settings' => [],
            'selection_relationships' => [1],
        ];

        $element = $this->create_element($element_input_data, $activity);
        $sort_order = 123;
        $section_element = section_element::create($section, $element, $sort_order);
        return $section_element;
    }

    /**
     * Create participant instance
     *
     * @param $perform_generator
     * @return participant_instance
     */
    private function create_participant_instance($perform_generator): participant_instance {
        $subject_user = $this->getDataGenerator()->create_user();
        $participant_user = $this->getDataGenerator()->create_user();

        $subject_instance = $perform_generator->create_subject_instance(
            [
                'subject_is_participating' => true,
                'subject_user_id' => $subject_user->id,
                'other_participant_id' => $participant_user->id,
            ]
        );

        $participant_instance = $perform_generator->create_participant_instance(
            $participant_user, $subject_instance->id, constants::RELATIONSHIP_SUBJECT
        );
        return $participant_instance;
    }

    /**
     * @param section_element $section_element
     * @param participant_instance $participant_instance
     * @return array
     * @throws coding_exception
     */
    private function create_linked_review_content(section_element $section_element, participant_instance $participant_instance) {
        $evidence_generator = $this->getDataGenerator()->get_plugin_generator('totara_evidence');

        $evidence_user = $evidence_generator->create_evidence_user();
        $evidence_type = $evidence_generator->create_evidence_type(['name' => 'Type']);

        $field_data = (object)[
            'key' => 'value',
        ];

        $evidence_item = evidence_item::create($evidence_type, $evidence_user, $field_data, 'Evidence1');

        $linked_review_contents[] = linked_review_content::create(
            $evidence_item->id, $section_element->id, $participant_instance->id, false
        );

        return $linked_review_contents;
    }

    /**
     * @param array $data
     * @param activity|null $activity
     * @return element
     * @throws dml_exception
     * @throws moodle_exception
     */
    private function create_element(array $data, activity $activity = null) {
        $context = $activity ? $activity->get_context() : context_system::instance();
        return element::create($context, 'linked_review', 'title', '', json_encode($data));
    }
}