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
 * @author Kunle Odusan <kunle.odusan@totaralearning.com>
 */

use core\collection;
use core\date_format;
use mod_perform\constants;
use mod_perform\entity\activity\element_response;
use mod_perform\entity\activity\participant_instance;
use mod_perform\entity\activity\participant_section;
use mod_perform\entity\activity\section as section_entity;
use mod_perform\entity\activity\subject_instance;
use mod_perform\entity\activity\track as track_entity;
use mod_perform\entity\activity\track_assignment;
use mod_perform\entity\activity\track_user_assignment;
use mod_perform\expand_task;
use mod_perform\models\activity\element;
use mod_perform\models\activity\section;
use mod_perform\models\activity\section_element;
use mod_perform\models\activity\track;
use mod_perform\models\response\responder_group;
use mod_perform\models\response\section_element_response;
use mod_perform\state\activity\draft;
use mod_perform\task\service\subject_instance_creation;
use mod_perform\user_groups\grouping;
use totara_job\job_assignment;
use totara_webapi\phpunit\webapi_phpunit_helper;

/**
 * @group perform
 * @group perform_element
 */
class performelement_redisplay_webapi_resolver_query_subject_instance_previous_responses_testcase extends advanced_testcase {

    use webapi_phpunit_helper;

    /**
     * @param mod_perform_generator $perform_generator
    */
    private $perform_generator;

    private const QUERY = 'performelement_redisplay_subject_instance_previous_responses';

    /**
     * @param array $users
     */
    private $users;

    /**
     * @param int $five_days_ago
     */
    private $five_days_ago;

    /**
     * @param array $redisplay
     */
    private $redisplay;

    /**
     * @param array $base_relationships
     */
    private $base_relationships = [
        constants::RELATIONSHIP_SUBJECT,
        constants::RELATIONSHIP_MANAGER,
        constants::RELATIONSHIP_APPRAISER
    ];

    /**
     * Tests title when getting previous response from redisplay element referencing same activity.
     */
    public function test_it_returns_no_participation_title_when_no_source_subject_instance_for_same_activity() {
        $this->create_test_users();
        $redisplay = $this->set_up_redisplay_activity($this->base_relationships);
        $redisplay['activity']->activate();
        $this->generate_instances();

        $redisplay_subject_participant_section = $this->get_participant_section_of_user_from_activity($redisplay['activity']->id, $this->users['subject']->id);

        $result = $this->resolve_graphql_query(self::QUERY, [
            'input' => [
                'section_element_id' => $redisplay['respondable_section_element']->id,
                'participant_section_id' => $redisplay_subject_participant_section->id,
            ]
        ]);

        $this->assertEquals(
            'Response redisplay to the following question cannot be shown, because there is no previous participation associated with the activity "Redisplay activity".',
            $result['title']
        );
    }

    /**
     * Tests title when getting previous response from redisplay element referencing another activity.
     */
    public function test_it_returns_no_participation_title_when_no_source_subject_instance_for_different_activity() {
        $this->create_test_users();
        $question_bank = $this->set_up_question_bank_activity($this->base_relationships);
        $redisplay = $this->set_up_redisplay_activity($this->base_relationships, $question_bank['respondable_section_element']->id);
        $redisplay['activity']->activate();
        $this->generate_instances();

        $redisplay_subject_participant_section = $this->get_participant_section_of_user_from_activity($redisplay['activity']->id, $this->users['subject']->id);

        $result = $this->resolve_graphql_query(self::QUERY, [
            'input' => [
                'section_element_id' => $question_bank['respondable_section_element']->id,
                'participant_section_id' => $redisplay_subject_participant_section->id,
            ]
        ]);

        $this->assertEquals(
            'Response redisplay cannot be shown, because there is no participation associated with the activity "Question Bank".',
            $result['title']
        );
    }

    /**
     * Tests title when getting previous response from redisplay element referencing a pending subject instance
     * or when no participant instance has responded.
     */
    public function test_it_returned_no_participation_title_for_pending_subject_instance_or_no_responses_yet() {
        $this->create_test_users();
        $relationships = $this->base_relationships;
        $relationships[] = constants::RELATIONSHIP_EXTERNAL;
        $question_bank = $this->set_up_question_bank_activity($relationships);
        $question_bank['activity']->activate();

        $redisplay = $this->set_up_redisplay_activity($this->base_relationships, $question_bank['respondable_section_element']->id);
        $redisplay['activity']->activate();
        $this->generate_instances();
        $this->back_date_subject_instances_for_activity($question_bank['activity']->id, $this->five_days_ago_timestamp());

        $redisplay_subject_participant_section = $this->get_participant_section_of_user_from_activity($redisplay['activity']->id, $this->users['subject']->id);

        $result = $this->resolve_graphql_query(self::QUERY, [
            'input' => [
                'section_element_id' => $question_bank['respondable_section_element']->id,
                'participant_section_id' => $redisplay_subject_participant_section->id,
            ]
        ]);
        $date = userdate($this->five_days_ago_timestamp(), get_string(date_format::get_lang_string(date_format::FORMAT_DATE), 'langconfig'));

        $this->assertEquals(
            "Response redisplay cannot be shown, because there is no participation associated with the activity \"Question Bank ($date)\".",
            $result['title']
        );
    }

    /**
     * Tests the title when the source subject instance doesn't have participant instances due to purging
     * or the automated relationships were not generated.
     */
    public function test_it_does_not_return_previous_responses_when_participant_instances_have_been_purged() {
        $this->create_test_users();
        $question_bank = $this->set_up_question_bank_activity($this->base_relationships);
        $question_bank['activity']->activate();

        $redisplay = $this->set_up_redisplay_activity($this->base_relationships, $question_bank['respondable_section_element']->id);
        $redisplay['activity']->activate();
        $this->generate_instances();
        $this->delete_participant_instances_for_activity($question_bank['activity']->id);

        $five_days_ago = $this->five_days_ago_timestamp();
        $this->back_date_subject_instances_for_activity($question_bank['activity']->id, $five_days_ago);

        $redisplay_subject_participant_section = $this->get_participant_section_of_user_from_activity($redisplay['activity']->id, $this->users['subject']->id);

        $result = $this->resolve_graphql_query(self::QUERY, [
            'input' => [
                'section_element_id' => $question_bank['respondable_section_element']->id,
                'participant_section_id' => $redisplay_subject_participant_section->id,
            ]
        ]);
        $date = userdate($five_days_ago, get_string(date_format::get_lang_string(date_format::FORMAT_DATE), 'langconfig'));

        $this->assertEquals(
            "Response redisplay cannot be shown, because there is no participation associated with the activity \"Question Bank ($date)\".",
            $result['title']
        );
    }

    /**
     * Tests exception is thrown when trying to access a redisplay element with a non-associated participant section.
     */
    public function test_trying_to_access_redisplay_element_with_non_associated_participant_section() {
        $this->create_test_users();
        $question_bank = $this->set_up_question_bank_activity($this->base_relationships);
        $question_bank['activity']->activate();
        $redisplay = $this->set_up_redisplay_activity($this->base_relationships);
        $redisplay['activity']->activate();
        $this->generate_instances();

        $redisplay_subject_participant_section = $this->get_participant_section_of_user_from_activity($question_bank['activity']->id, $this->users['subject']->id);

        $this->expectException(coding_exception::class);
        $this->expectErrorMessage('Invalid access to redisplay');
        $this->resolve_graphql_query(self::QUERY, [
            'input' => [
                'section_element_id' => $redisplay['respondable_section_element']->id,
                'participant_section_id' => $redisplay_subject_participant_section->id,
            ]
        ]);
    }

    /**
     * Test previous responses are grouped into the right relationship.
     */
    public function test_it_returns_previous_responses_with_named_relationships() {
        $this->create_test_users();
        $question_bank = $this->set_up_question_bank_activity($this->base_relationships);
        $question_bank['activity']->activate();

        $redisplay = $this->set_up_redisplay_activity($this->base_relationships, $question_bank['respondable_section_element']->id);
        $redisplay['activity']->activate();
        $this->generate_instances();
        $this->back_date_subject_instances_for_activity($question_bank['activity']->id, $this->five_days_ago_timestamp());
        $subject_instances = $this->get_subject_instances_belonging_to_activity($question_bank['activity']->id);
        $this->generate_responses_for_section_element_of_subject_instances($subject_instances, $question_bank['respondable_section_element']->id);

        $redisplay_subject_participant_section = $this->get_participant_section_of_user_from_activity($redisplay['activity']->id, $this->users['subject']->id);

        $result = $this->resolve_graphql_query(self::QUERY, [
            'input' => [
                'section_element_id' => $question_bank['respondable_section_element']->id,
                'participant_section_id' => $redisplay_subject_participant_section->id,
            ]
        ]);
        $date = userdate($this->five_days_ago_timestamp(), get_string(date_format::get_lang_string(date_format::FORMAT_DATE), 'langconfig'));
        $today_date = userdate(time(), get_string(date_format::get_lang_string(date_format::FORMAT_DATE), 'langconfig'));

        $this->assertEquals(
            "Response redisplay from \"Question Bank ($date)\" – responses last updated $today_date.",
            $result['title']
        );

        $this->assertInstanceOf(section_element_response::class, $result['your_response']);
        $this->assertEquals("my previous answer {$this->users['subject']->id}", $result['your_response']->response_data);
        $this->assertFalse($result['is_anonymous']);
        $this->assertNotEmpty($result['other_responder_groups']);

        /** @var responder_group $responder_group*/
        foreach ($result['other_responder_groups'] as $responder_group) {
            $relationship = strtolower($responder_group->get_relationship_name());
            $this->assertNotEquals(constants::RELATIONSHIP_SUBJECT, $responder_group->get_relationship_name());
            $this->assertEquals(1, $responder_group->get_responses()->count());
            $response = $responder_group->get_responses()->first();
            $this->assertEquals("my previous answer {$this->users[$relationship]->id}", $response->response_data);
        }
    }

    /**
     * Test previous responses are anonymized if source activity is anonymized.
     */
    public function test_it_returns_previous_responses_with_anonymized_relationships() {
        $this->create_test_users();
        $question_bank = $this->set_up_question_bank_activity($this->base_relationships);
        $question_bank['activity']->set_anonymous_setting(true);
        $question_bank['activity']->activate();

        $redisplay = $this->set_up_redisplay_activity($this->base_relationships, $question_bank['respondable_section_element']->id);
        $redisplay['activity']->activate();
        $this->generate_instances();
        $this->back_date_subject_instances_for_activity($question_bank['activity']->id, $this->five_days_ago_timestamp());
        $subject_instances = $this->get_subject_instances_belonging_to_activity($question_bank['activity']->id);
        $this->generate_responses_for_section_element_of_subject_instances($subject_instances, $question_bank['respondable_section_element']->id);

        $redisplay_subject_participant_section = $this->get_participant_section_of_user_from_activity($redisplay['activity']->id, $this->users['subject']->id);

        $result = $this->resolve_graphql_query(self::QUERY, [
            'input' => [
                'section_element_id' => $question_bank['respondable_section_element']->id,
                'participant_section_id' => $redisplay_subject_participant_section->id,
            ]
        ]);
        $date = userdate($this->five_days_ago_timestamp(), get_string(date_format::get_lang_string(date_format::FORMAT_DATE), 'langconfig'));
        $today_date = userdate(time(), get_string(date_format::get_lang_string(date_format::FORMAT_DATE), 'langconfig'));

        $this->assertEquals(
            "Response redisplay from \"Question Bank ($date)\" – responses last updated $today_date.",
            $result['title']
        );

        $this->assertInstanceOf(section_element_response::class, $result['your_response']);
        $this->assertEquals("my previous answer {$this->users['subject']->id}", $result['your_response']->response_data);
        $this->assertTrue($result['is_anonymous']);
        $this->assertCount(1, $result['other_responder_groups']);

        /** @var responder_group $anonymous_responder_group*/
        $anonymous_responder_group = end($result['other_responder_groups']);
        $this->assertNotContains(strtolower($anonymous_responder_group->get_relationship_name()), $this->base_relationships);
        $this->assertEquals($anonymous_responder_group->get_relationship_name(), 'Anonymous');
        $this->assertEquals(2, $anonymous_responder_group->get_responses()->count());
    }

    /**
     * Delete participant instances for all subject instances in activity.
     *
     * @param int $activity_id
     */
    private function delete_participant_instances_for_activity(int $activity_id): void {
        $participant_instance_ids = participant_instance::repository()->as('pi')
            ->join([subject_instance::TABLE, 'si'], 'pi.subject_instance_id', 'si.id')
            ->join([track_user_assignment::TABLE, 'tua'], 'si.track_user_assignment_id', 'tua.id')
            ->join([track_entity::TABLE, 't'], 'tua.track_id', 't.id')
            ->where('t.activity_id', $activity_id)
            ->get()->pluck('id');
        participant_instance::repository()->where_in('id', $participant_instance_ids)->delete();
    }

    /**
     * Generate responses for all participant instances in the subject instances for the section element id.
     *
     * @param collection $subject_instances
     * @param int $section_element_id
     * @return void
     */
    private function generate_responses_for_section_element_of_subject_instances(collection $subject_instances, int $section_element_id): void {
        foreach ($subject_instances as $subject_instance) {
            foreach ($subject_instance->participant_instances as $participant_instance) {
                $element_response = new element_response();
                $element_response->participant_instance_id = $participant_instance->id;
                $element_response->section_element_id = $section_element_id;
                $element_response->response_data = "my previous answer {$participant_instance->participant_id}";
                $element_response->save();
            }
        }
    }

    /**
     * Back-date subject instances in activity to timestamp.
     *
     * @param int $activity_id
     * @param int $timestamp
     * @return void
     */
    private function back_date_subject_instances_for_activity(int $activity_id, int $timestamp): void {
        /** @var collection|subject_instance[] $subject_instances*/
        $subject_instances = $this->get_subject_instances_belonging_to_activity($activity_id);

        foreach ($subject_instances as $subject_instance) {
            $subject_instance->created_at = $timestamp;
            $subject_instance->save();
        }
    }

    /**
     * Get participant section of user from activity.
     *
     * @param int $activity_id
     * @param int $user_id
     * @return participant_section|null
     */
    private function get_participant_section_of_user_from_activity(int $activity_id, int $user_id): ?participant_section {
        return participant_section::repository()->as('ps')
            ->join([participant_instance::TABLE, 'pi'], 'ps.participant_instance_id', 'pi.id')
            ->join([section_entity::TABLE, 's'], 'ps.section_id', 's.id')
            ->where('pi.participant_id', $user_id)
            ->where('s.activity_id', $activity_id)
            ->order_by('id')
            ->first();
    }

    /**
     * Get subject instances belonging to activity.
     *
     * @param int $activity_id
     * @return collection
     */
    private function get_subject_instances_belonging_to_activity(int $activity_id): collection {
        return subject_instance::repository()->as('s')
            ->join([track_user_assignment::TABLE, 'tua'], 's.track_user_assignment_id', 'tua.id')
            ->join([track_entity::TABLE, 't'], 'tua.track_id', 't.id')
            ->where('t.activity_id', $activity_id)
            ->get();
    }

    /**
     * @inheritDocs
     */
    protected function tearDown(): void {
        $this->perform_generator = null;
        $this->five_days_ago = null;
        $this->users = null;
    }

    /**
     * Create test users.
     */
    private function create_test_users() {
        $this->setAdminUser();
        /** @var mod_perform_generator $perform_generator */
        $this->perform_generator = $this->getDataGenerator()->get_plugin_generator('mod_perform');

        $this->setup_users_job_assignments();
    }

    /**
     * Create timestamp for five days ago.
     *
     * @return int
     */
    private function five_days_ago_timestamp(): int {
        if (!$this->five_days_ago) {
            $this->five_days_ago = time() - (60 * 60 * 24 * 5);
        }
        return $this->five_days_ago;
    }

    /**
     * Generate subject/participant instances.
     */
    private function generate_instances() {
        expand_task::create()->expand_all();
        $service = new subject_instance_creation();
        $service->generate_instances();
    }

    /**
     * Setup activity with questions.
     */
    private function set_up_question_bank_activity($relationships): array {
        $question_bank = [];

        $question_bank['activity'] = $this->perform_generator->create_activity_in_container(
            [
                'activity_name' => 'Question Bank',
                'activity_status' => draft::get_code(),
                'create_section' => false,
            ]
        );
        $question_bank['section'] = $this->perform_generator->create_section($question_bank['activity']);
        $question_bank['track'] = track::create($question_bank['activity']);
        $this->setup_track_assignments($question_bank['track']);

        foreach ($relationships as $relationship) {
            $this->perform_generator->create_section_relationship($question_bank['section'], ['relationship' => $relationship]);
        }
        $question_bank['respondable_section_element'] = $this->create_respondable_element($question_bank['section']);

        return $question_bank;
    }

    /**
     * Setup activity with redisplay element.
     */
    private function set_up_redisplay_activity($relationships, int $source_section_element_id = null): array {
        $redisplay = [];
        $redisplay['activity'] = $this->perform_generator->create_activity_in_container(
            [
                'activity_name' => 'Redisplay activity',
                'activity_status' => draft::get_code(),
                'create_section' => false,
            ]
        );
        $redisplay['section'] = $this->perform_generator->create_section($redisplay['activity']);
        $redisplay['track'] = track::create($redisplay['activity']);
        $this->setup_track_assignments($redisplay['track']);

        foreach ($relationships as $relationship) {
            $this->perform_generator->create_section_relationship($redisplay['section'], ['relationship' => $relationship]);
        }

        $redisplay['respondable_section_element'] = $this->create_respondable_element($redisplay['section']);

        if (is_null($source_section_element_id)) {
            $source_section_element_id = $redisplay['respondable_section_element']->id;
        }
        $redisplay_element = element::create(
            $redisplay['activity']->get_context(),
            'redisplay',
            'This was your previous response:',
            '',
            "{\"sectionElementId\":\"$source_section_element_id\"}"
        );
        element::post_create($redisplay_element);
        $redisplay['redisplay_section_element'] = $redisplay['section']->add_element($redisplay_element);

        return $redisplay;
    }

    /**
     * Create respondable section element for section.
     *
     * @param section $section
     * @return section_element
    */
    private function create_respondable_element(section $section): section_element {
        return $this->perform_generator->create_section_element($section, $this->perform_generator->create_element());
    }

    /**
     * Setup user job assignments.
     */
    private function setup_users_job_assignments() {
        $data_generator = $this->getDataGenerator();

        $users = [
            'subject' => $data_generator->create_user(),
            'manager' => $data_generator->create_user(),
            'appraiser' => $data_generator->create_user(),
        ];

        job_assignment::create(
            [
                'userid' => $users['subject']->id,
                'idnumber' => rand(0, 100) . '_subject',
                'managerjaid' => job_assignment::create_default($users['manager']->id)->id,
                'appraiserid' => $users['appraiser']->id,
            ]
        );

        $this->users = $users;
    }

    /**
     * Setup track assignments.
     */
    private function setup_track_assignments(track $track) {
        $this->perform_generator->create_track_assignments($track, 1, 0, 0, 0);
        $track_assignments = track_assignment::repository()
            ->where('user_group_type', grouping::COHORT)
            ->get();

        foreach ($track_assignments as $assignment) {
            cohort_add_member($assignment->user_group_id, $this->users['subject']->id);
        }
    }
}


