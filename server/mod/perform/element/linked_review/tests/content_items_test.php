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
 * @author Fabian Derschatta <fabian.derschatta@totaralearning.com>
 * @package performelement_linked_review
 */

require_once(__DIR__ . '/linked_review_testcase.php');

use core\collection;
use core\orm\query\exceptions\record_not_found_exception;
use core\webapi\execution_context;
use mod_perform\constants;
use mod_perform\models\activity\participant_instance;
use mod_perform\models\activity\section_relationship;
use performelement_linked_review\models\linked_review_content;
use performelement_linked_review\webapi\resolver\query\content_items;
use totara_core\relationship\relationship;
use totara_webapi\graphql;

/**
 * @group perform
 * @group perform_element
 */
class performelement_linked_review_content_items_testcase extends linked_review_testcase {

    public function test_get_content_items(): void {
        $query = new class() extends content_items {
            protected static function query_content(int $user_id, \core\orm\collection $content): array {
                return [$user_id, $content];
            }
        };

        [$activity1, $section1, $element1, $section_element1] = $this->create_activity_with_section_and_review_element();
        [$activity2, $section2, $element2, $section_element2] = $this->create_activity_with_section_and_review_element();
        [$user1, $subject_instance1, $participant_instance1] = $this->create_participant_in_section($activity1, $section1);
        [$user2, $subject_instance2, $participant_instance2] = $this->create_participant_in_section($activity2, $section2);

        $reporting_user = $this->getDataGenerator()->create_user();

        $content_ids1 = $this->create_competency_assignments($user1->id);
        $content_ids2 = $this->create_competency_assignments($user2->id);

        $content_items1 = linked_review_content::create_multiple(
            $content_ids1,
            $section_element1->id,
            $participant_instance1->id, false
        );

        $content_items2 = linked_review_content::create_multiple(
            $content_ids2,
            $section_element1->id,
            $participant_instance2->id, false
        );

        self::setUser($user1);

        $args = [
            'section_element_id' => $section_element1->id,
            'subject_instance_id' => $participant_instance1->subject_instance_id,
        ];

        [$actual_user_id, $actual_content] = $query::resolve($args, execution_context::create(graphql::TYPE_AJAX));

        $this->assertEquals($user1->id, $actual_user_id);
        $this->assertInstanceOf(collection::class, $actual_content);
        $items = $actual_content->all();
        $this->assertContainsOnlyInstancesOf(linked_review_content::class, $items);
        $this->assertEqualsCanonicalizing($content_items1->pluck('id'), $actual_content->pluck('id'));
        $this->assertNotEqualsCanonicalizing($content_items2->pluck('id'), $actual_content->pluck('id'));
    }

    public function test_error_cases() {
        $query = new class() extends content_items {
            protected static function query_content(int $user_id, \core\orm\collection $content): array {
                return [$user_id, $content];
            }
        };

        [$activity1, $section1, $element1, $section_element1] = $this->create_activity_with_section_and_review_element();
        [$activity2, $section2, $element2, $section_element2] = $this->create_activity_with_section_and_review_element();
        [$user1, $subject_instance1, $participant_instance1] = $this->create_participant_in_section($activity1, $section1);
        [$user2, $subject_instance2, $participant_instance2] = $this->create_participant_in_section($activity2, $section2);

        $element3 = $this->perform_generator()->create_element(['title' => 'Another one']);
        $section_element3 = $this->perform_generator()->create_section_element($section1, $element3);

        $content_ids1 = $this->create_competency_assignments($user1->id);
        $content_ids2 = $this->create_competency_assignments($user2->id);

        $content_items1 = linked_review_content::create_multiple(
            $content_ids1,
            $section_element1->id,
            $participant_instance1->id, false
        );

        $content_items2 = linked_review_content::create_multiple(
            $content_ids2,
            $section_element1->id,
            $participant_instance2->id, false
        );

        self::setUser($user1);

        // Try section_element not part of given participant instance
        $args = [
            'section_element_id' => $section_element2->id,
            'subject_instance_id' => $participant_instance1->subject_instance_id,
        ];

        try {
            $query::resolve($args, execution_context::create(graphql::TYPE_AJAX));
            $this->fail('expected query to fail');
        } catch (coding_exception $exception) {
            $this->assertStringContainsString('User does not participant on given section', $exception->getMessage());
        }

        // Test non existing section_element
        try {
            $args['section_element_id'] = 666;
            $query::resolve($args, execution_context::create(graphql::TYPE_AJAX));
            $this->fail('expected query to fail');
        } catch (record_not_found_exception $exception) {
            $this->assertStringContainsString('Can not find data record in database', $exception->getMessage());
        }

        // Test non-linked_review section_element
        try {
            $args['section_element_id'] = $section_element3->id;
            $query::resolve($args, execution_context::create(graphql::TYPE_AJAX));
            $this->fail('expected query to fail');
        } catch (coding_exception $exception) {
            $this->assertStringContainsString('Invalid section element ID: '.$section_element3->id, $exception->getMessage());
        }

        // Test user is not a participant

        self::setUser($user2);

        $args = [
            'section_element_id' => $section_element1->id,
            'subject_instance_id' => $participant_instance1->subject_instance_id,
        ];

        try {
            $query::resolve($args, execution_context::create(graphql::TYPE_AJAX));
            $this->fail('expected query to fail');
        } catch (coding_exception $exception) {
            $this->assertStringContainsString('User does not participant on given section', $exception->getMessage());
        }
    }

    public function test_external_participant() {
        $query = new class() extends content_items {
            protected static function query_content(int $user_id, \core\orm\collection $content): array {
                return [$user_id, $content];
            }
        };

        [$activity1, $section1, $element1, $section_element1] = $this->create_activity_with_section_and_review_element();
        [$activity2, $section2, $element2, $section_element2] = $this->create_activity_with_section_and_review_element();
        [$user1, $subject_instance1, $participant_instance1] = $this->create_participant_in_section($activity1, $section1);
        [$user2, $subject_instance2, $participant_instance2] = $this->create_participant_in_section($activity2, $section2);

        $content_ids = $this->create_competency_assignments($user1->id);

        $content_items = linked_review_content::create_multiple(
            $content_ids,
            $section_element1->id,
            $participant_instance1->id, false
        );

        $this->perform_generator->create_section_relationship(
            $section1,
            ['relationship' => constants::RELATIONSHIP_EXTERNAL]
        );

        [$external_participant_instance1] = $this->perform_generator->create_external_participant_instances(
            $subject_instance1->id,
            [
                'fullname' => 'External user',
                'email' => 'mytest@example.com',
            ]
        );

        $this->perform_generator->create_section_relationship(
            $section2,
            ['relationship' => constants::RELATIONSHIP_EXTERNAL]
        );

        [$external_participant_instance2] = $this->perform_generator->create_external_participant_instances(
            $subject_instance2->id,
            [
                'fullname' => 'Other External user',
                'email' => 'anothertest@example.com',
            ]
        );

        /** @var participant_instance $external_participant_instance1 */
        $external_participant_instance1 = participant_instance::load_by_entity($external_participant_instance1);
        /** @var participant_instance $external_participant_instance2 */
        $external_participant_instance2 = participant_instance::load_by_entity($external_participant_instance2);

        $token1 = $external_participant_instance1->get_participant()->get_user()->token;
        $token2 = $external_participant_instance2->get_participant()->get_user()->token;
        $this->assertTrue(strlen($token1) > 0);

        $this->setUser(null);

        // Missing token should throw an exception
        $args = [
            'section_element_id' => $section_element1->id,
            'subject_instance_id' => $participant_instance1->subject_instance_id,
        ];

        try {
            $query::resolve($args, execution_context::create(graphql::TYPE_AJAX));
            $this->fail('expected query to fail');
        } catch (require_login_exception $exception) {
            $this->assertStringContainsString('You are not logged in', $exception->getMessage());
        }

        // Now try with invalid token
        try {
            $args['token'] = 'abcdefg';
            $query::resolve($args, execution_context::create(graphql::TYPE_AJAX));
            $this->fail('expected query to fail');
        } catch (coding_exception $exception) {
            $this->assertStringContainsString('Token validation for external participant failed', $exception->getMessage());
        }

        // Now try with token from a different instance
        try {
            $args['token'] = $token2;
            $query::resolve($args, execution_context::create(graphql::TYPE_AJAX));
            $this->fail('expected query to fail');
        } catch (coding_exception $exception) {
            $this->assertStringContainsString('Invalid subject instance for given token', $exception->getMessage());
        }

        // Now try the successful one
        $args['token'] = $token1;

        [$actual_user_id, $actual_content] = $query::resolve($args, execution_context::create(graphql::TYPE_AJAX));

        $this->assertEquals($user1->id, $actual_user_id);
        $this->assertInstanceOf(collection::class, $actual_content);
        $items = $actual_content->all();
        $this->assertContainsOnlyInstancesOf(linked_review_content::class, $items);
        $this->assertEqualsCanonicalizing($content_items->pluck('id'), $actual_content->pluck('id'));
    }

    public function test_user_who_can_report_on_responses_can_load_the_items() {
        $query = new class() extends content_items {
            protected static function query_content(int $user_id, \core\orm\collection $content): array {
                return [$user_id, $content];
            }
        };

        [$activity, $section, $element, $section_element] = $this->create_activity_with_section_and_review_element();
        [$user, $subject_instance, $participant_instance] = $this->create_participant_in_section($activity, $section);

        $reporting_user = $this->getDataGenerator()->create_user();

        $this->assign_reporter_cap_over_subject('mod/perform:report_on_subject_responses', $reporting_user, $user);

        $content_ids = $this->create_competency_assignments($user->id);

        $content_items = linked_review_content::create_multiple(
            $content_ids,
            $section_element->id,
            $participant_instance->id, false
        );

        self::setUser($reporting_user);

        $args = [
            'section_element_id' => $section_element->id,
            'subject_instance_id' => $participant_instance->subject_instance_id,
        ];

        [$actual_user_id, $actual_content] = $query::resolve($args, execution_context::create(graphql::TYPE_AJAX));

        $this->assertEquals($user->id, $actual_user_id);
        $this->assertInstanceOf(collection::class, $actual_content);
        $items = $actual_content->all();
        $this->assertContainsOnlyInstancesOf(linked_review_content::class, $items);
        $this->assertEqualsCanonicalizing($content_items->pluck('id'), $actual_content->pluck('id'));
    }

    /**
     * @param string $cap
     * @param stdClass $reporter
     * @param stdClass $subject
     */
    private function assign_reporter_cap_over_subject(string $cap, stdClass $reporter, stdClass $subject): void {
        $reporter_role_id = create_role(
            'Perform Reporter Role',
            'perform_reporter_role',
            'Can report on perform data'
        );

        $system_context = context_system::instance();
        assign_capability(
            $cap,
            CAP_ALLOW,
            $reporter_role_id,
            $system_context
        );

        $this->getDataGenerator()->role_assign(
            $reporter_role_id,
            $reporter->id,
            context_user::instance($subject->id)
        );
    }

}
