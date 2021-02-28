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
 * @package mod_perform
 */

use mod_perform\constants;
use performelement_linked_review\models\linked_review_content;
use performelement_linked_review\entity\linked_review_content as linked_review_content_entity;

require_once(__DIR__ . '/base_linked_review_testcase.php');

class linked_review_content_testcase extends performelement_linked_review_base_linked_review_testcase {

    public function test_create() {
        $content_id = 1;
        $validate = false;

        [$activity1, $section1, $element1, $section_element1] = $this->create_activity_with_section_and_review_element();
        [$user1, $subject_instance1, $participant_instance1] = $this->create_participant_in_section($activity1, $section1);

        $this->assertCount(0, linked_review_content_entity::repository()->get());
        linked_review_content::create($content_id, $section_element1->id, $participant_instance1->id, $validate);
        $this->assertCount(1, linked_review_content_entity::repository()->get());

        $review_content = linked_review_content_entity::repository()->get()->first();
        $this->assertEquals($section_element1->id, $review_content->section_element_id);
        $this->assertEquals($content_id, $review_content->content_id);
        $this->assertEquals($subject_instance1->id, $review_content->subject_instance_id);
    }

    public function test_delete() {
        $content_id_1 = 1;
        $content_id_2 = 2;
        $validate = false;

        [$activity1, $section1, $element1, $section_element1] = $this->create_activity_with_section_and_review_element();
        [$user1, $subject_instance1, $participant_instance1] = $this->create_participant_in_section($activity1, $section1);

        // Create two contents
        $model_1 = linked_review_content::create($content_id_1, $section_element1->id, $participant_instance1->id, $validate);
        $model_2 = linked_review_content::create($content_id_2, $section_element1->id, $participant_instance1->id, $validate);
        $this->assertCount(2, linked_review_content_entity::repository()->get());

        // Delete the first content
        $model_1->delete();

        // Only the second content left
        $this->assertCount(1, linked_review_content_entity::repository()->get());
        $this->assertEquals($model_2->id, linked_review_content_entity::repository()->get()->first()->id);
    }

    public function test_update_content_successful() {
        [$activity1, $section1, $element1, $section_element1] = $this->create_activity_with_section_and_review_element();
        [$user1, $subject_instance1, $participant_instance1] = $this->create_participant_in_section($activity1, $section1);
        $content_ids = $this->create_competency_assignments($user1->id);
        $first_two_content_ids = array_slice($content_ids, 0, 2);

        $this->generator()->create_section_relationship($section1, ['relationship' => constants::RELATIONSHIP_SUBJECT]);
        $this->assertCount(0, linked_review_content_entity::repository()->get());

        $this->setUser($user1);

        // add three contents
        linked_review_content::update_content($content_ids, $section_element1->id, $participant_instance1->id);
        $this->assertCount(3, linked_review_content_entity::repository()->get());

        $last_content_id = strval(end($content_ids));
        $this->assertContains($last_content_id, linked_review_content_entity::repository()->get()->pluck('content_id'));

        // remove the last content
        linked_review_content::update_content($first_two_content_ids, $section_element1->id, $participant_instance1->id);
        $this->assertCount(2, linked_review_content_entity::repository()->get());

        $last_content_id = strval(end($content_ids));
        $this->assertNotContains($last_content_id, linked_review_content_entity::repository()->get()->pluck('content_id'));
    }

    public function test_invalid_element() {
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessageMatches('/specified participant instance with ID/');
        linked_review_content::update_content([1, 2], 99999, 99988);
    }

    public function test_participant_can_answer() {
        [$activity1, $section1, $element1, $section_element1] = $this->create_activity_with_section_and_review_element();
        [$user1, $subject_instance1, $participant_instance1] = $this->create_participant_in_section($activity1, $section1);
        $content_ids = $this->create_competency_assignments($user1->id);

        $this->generator()->create_section_relationship(
            $section1, ['relationship' => constants::RELATIONSHIP_SUBJECT], true, false
        );
        $this->setUser($user1);

        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessageMatches('/do not currently have permissions/');

        linked_review_content::update_content($content_ids, $section_element1->id, $participant_instance1->id);
    }

    public function test_logged_in_user_does_not_belong_to_participant_instance() {
        [$activity1, $section1, $element1, $section_element1] = $this->create_activity_with_section_and_review_element();
        [$user1, $subject_instance1, $participant_instance1] = $this->create_participant_in_section($activity1, $section1);
        $user2 = $this->getDataGenerator()->create_user();
        $content_ids = $this->create_competency_assignments($user1->id);

        $this->generator()->create_section_relationship(
            $section1, ['relationship' => constants::RELATIONSHIP_SUBJECT], true, false
        );
        $this->setUser($user2);

        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage('do not currently have permissions');

        linked_review_content::update_content($content_ids, $section_element1->id, $participant_instance1->id);
    }

    public function test_content_ids_point_to_actual_content() {
        [$activity1, $section1, $element1, $section_element1] = $this->create_activity_with_section_and_review_element();
        [$user1, $subject_instance1, $participant_instance1] = $this->create_participant_in_section($activity1, $section1);
        $content_ids = [1, 2];

        $this->generator()->create_section_relationship($section1, ['relationship' => constants::RELATIONSHIP_SUBJECT]);
        $this->setUser($user1);

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessageMatches('/Not all the specified content IDs actually exist/');
        linked_review_content::update_content($content_ids, $section_element1->id, $participant_instance1->id);
    }

    private function generator(): \mod_perform\testing\generator {
        $data_generator = $this->getDataGenerator();
        /** @var \mod_perform\testing\generator $perform_generator */
        return $data_generator->get_plugin_generator('mod_perform');
    }
}