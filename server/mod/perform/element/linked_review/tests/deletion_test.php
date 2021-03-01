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
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package performelement_linked_review
 */

use mod_perform\constants;
use performelement_linked_review\models\linked_review_content;
use totara_core\relationship\relationship;

require_once(__DIR__ . '/linked_review_testcase.php');

/**
 * @group perform
 * @group perform_element
 */
class performelement_linked_review_deletion_testcase extends linked_review_testcase {

    public function test_activity_deletion(): void {
        global $DB;
        [$activity1, $section1, $element1, $section_element1] = $this->create_activity_with_section_and_review_element();
        [$user1, $subject_instance1, $participant_instance1] = $this->create_participant_in_section($activity1, $section1);
        [[$child_element1]] = $this->create_child_elements($section1, $element1);
        [$competency_assignment1_id] = $this->create_competency_assignments($user1->id);
        $linked_content1 = linked_review_content::create(
            $competency_assignment1_id, $section_element1->id, $participant_instance1->id, false
        );
        $content_response1 = $this->create_content_response($participant_instance1, $linked_content1, $child_element1);

        [$activity2, $section2, $element2, $section_element2] = $this->create_activity_with_section_and_review_element();
        [$user2, $subject_instance2, $participant_instance2] = $this->create_participant_in_section($activity2, $section2);
        [[$child_element2]] = $this->create_child_elements($section2, $element2);
        [$competency_assignment2_id] = $this->create_competency_assignments($user2->id);
        $linked_content2 = linked_review_content::create(
            $competency_assignment2_id, $section_element2->id, $participant_instance2->id, false
        );
        $content_response2 = $this->create_content_response($participant_instance2, $linked_content2, $child_element2);

        $this->assertEquals(2, $DB->count_records('perform_element_linked_review_content'));
        $this->assertEquals(2, $DB->count_records('perform_element_linked_review_content_response'));

        $activity1->delete();

        $this->assertFalse($DB->record_exists('perform_element_linked_review_content', ['id' => $linked_content1->id]));
        $this->assertFalse($DB->record_exists('perform_element_linked_review_content_response', ['id' => $content_response1->id]));
        $this->assertTrue($DB->record_exists('perform_element_linked_review_content', ['id' => $linked_content2->id]));
        $this->assertTrue($DB->record_exists('perform_element_linked_review_content_response', ['id' => $content_response2->id]));

        $activity2->delete();

        $this->assertEquals(0, $DB->count_records('perform_element_linked_review_content'));
        $this->assertEquals(0, $DB->count_records('perform_element_linked_review_content_response'));
    }

    public function test_participant_instance_deletion(): void {
        global $DB;
        [$activity, $section, $element, $section_element] = $this->create_activity_with_section_and_review_element();
        [$user1, $subject_instance, $participant_instance1] = $this->create_participant_in_section($activity, $section);
        [$user2, $subject_instance, $participant_instance2] = $this->create_participant_in_section(
            $activity, $section, $subject_instance, relationship::load_by_idnumber(constants::RELATIONSHIP_MANAGER)->id
        );
        [[$child_element1]] = $this->create_child_elements($section, $element);
        [$competency_assignment_id] = $this->create_competency_assignments($user1->id);
        $linked_content = linked_review_content::create(
            $competency_assignment_id, $section_element->id, $participant_instance1->id, false
        );

        $content_response1 = $this->create_content_response($participant_instance1, $linked_content, $child_element1);
        $content_response2 = $this->create_content_response($participant_instance2, $linked_content, $child_element1);

        $this->assertEquals(1, $DB->count_records('perform_element_linked_review_content'));
        $this->assertEquals(2, $DB->count_records('perform_element_linked_review_content_response'));

        $participant_instance1->delete();

        $this->assertTrue($DB->record_exists('perform_element_linked_review_content', ['id' => $linked_content->id]));
        $this->assertFalse($DB->record_exists('perform_element_linked_review_content_response', ['id' => $content_response1->id]));
        $this->assertTrue($DB->record_exists('perform_element_linked_review_content_response', ['id' => $content_response2->id]));

        $participant_instance2->delete();

        $this->assertEquals(1, $DB->count_records('perform_element_linked_review_content'));
        $this->assertEquals(0, $DB->count_records('perform_element_linked_review_content_response'));
    }

    public function test_linked_content_deletion(): void {
        global $DB;
        [$activity, $section, $element, $section_element] = $this->create_activity_with_section_and_review_element();
        [$user, $subject_instance, $participant_instance] = $this->create_participant_in_section($activity, $section);
        [[$child_element1]] = $this->create_child_elements($section, $element);
        [$competency_assignment1_id, $competency_assignment2_id] = $this->create_competency_assignments($user->id);

        $linked_content1 = linked_review_content::create(
            $competency_assignment1_id, $section_element->id, $participant_instance->id, false
        );
        $content_response1 = $this->create_content_response($participant_instance, $linked_content1, $child_element1);

        $linked_content2 = linked_review_content::create(
            $competency_assignment2_id, $section_element->id, $participant_instance->id, false
        );
        $content_response2 = $this->create_content_response($participant_instance, $linked_content2, $child_element1);

        $this->assertEquals(2, $DB->count_records('perform_element_linked_review_content'));
        $this->assertEquals(2, $DB->count_records('perform_element_linked_review_content_response'));

        $linked_content1->delete();

        $this->assertFalse($DB->record_exists('perform_element_linked_review_content', ['id' => $linked_content1->id]));
        $this->assertFalse($DB->record_exists('perform_element_linked_review_content_response', ['id' => $content_response1->id]));
        $this->assertTrue($DB->record_exists('perform_element_linked_review_content', ['id' => $linked_content2->id]));
        $this->assertTrue($DB->record_exists('perform_element_linked_review_content_response', ['id' => $content_response2->id]));

        $linked_content2->delete();

        $this->assertEquals(0, $DB->count_records('perform_element_linked_review_content'));
        $this->assertEquals(0, $DB->count_records('perform_element_linked_review_content_response'));
    }

}
