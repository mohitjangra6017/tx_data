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
 * @author Jaron Steenson <jaron.steenson@totaralearning.com>
 * @package mod_perform
 */

use totara_core\advanced_feature;
use totara_webapi\phpunit\webapi_phpunit_helper;

require_once(__DIR__ . '/subject_instance_testcase.php');

/**
 * @coversDefaultClass \mod_perform\webapi\resolver\query\subject_instance_for_participant
 *
 * @group perform
 */
class mod_perform_webapi_resolver_query_subject_instance_for_participant_testcase extends mod_perform_subject_instance_testcase {
    private const QUERY = 'mod_perform_subject_instance_for_participant';

    use webapi_phpunit_helper;

    public function test_query_successful(): void {
        $args = [
            'subject_instance_id' => self::$about_user_and_participating->get_id()
        ];

        $result = $this->parsed_graphql_operation(self::QUERY, $args);
        $this->assert_webapi_operation_successful($result);
        $actual = $this->get_webapi_operation_data($result);

        $profile_image_small_url = (new user_picture(
            self::$about_user_and_participating->subject_user->get_user()->get_record(),
            0
        ))->get_url($GLOBALS['PAGE'])->out(false);

        $profile_image_url = (new user_picture(
            self::$about_user_and_participating->subject_user->get_user()->get_record(),
            1
        ))->get_url($GLOBALS['PAGE'])->out(false);

        $expected = [
            'id' => (string) self::$about_user_and_participating->id,
            'progress_status' => self::$about_user_and_participating->get_progress_status(),
            'instance_count' => 1,
            'job_assignment' => null,
            'due_date' => null,
            'activity' => [
                'id' => self::$about_user_and_participating->get_activity()->id,
                'name' => self::$about_user_and_participating->get_activity()->name,
                'type' => [
                    'display_name' => self::$about_user_and_participating->get_activity()->type->display_name,
                ],
                'settings' => [
                    'close_on_completion' => false,
                    'multisection' => false,
                    'visibility_condition' => [
                        'participant_description' => null,
                        'view_only_participant_description' => 'Responses are displayed as soon as a participant has submitted.'
                    ],
                ],
                'anonymous_responses' => false,
            ],
            'subject_user' => [
                'id' => self::$about_user_and_participating->subject_user->id,
                'fullname' => self::$about_user_and_participating->subject_user->fullname,
                'profileimageurlsmall' => $profile_image_small_url,
                'card_display' => [
                    'profile_picture_alt' => null,
                    'profile_url' => null,
                    'profile_picture_url' => $profile_image_url,
                    'display_fields' => [
                        [
                            'associate_url' => null,
                            'value' => self::$about_user_and_participating->subject_user->fullname,
                            'label' => 'Full name',
                            'is_custom' => false,
                        ],
                        [
                            'associate_url' => null,
                            'value' => '',
                            'label' => 'Department',
                            'is_custom' => false,
                        ],
                        [
                            'associate_url' => null,
                            'value' => null,
                            'label' => null,
                            'is_custom' => false,
                        ],
                        [
                            'associate_url' => null,
                            'value' => null,
                            'label' => null,
                            'is_custom' => false,
                        ],
                    ],
                ],
            ],
            'static_instances' => [],
        ];

        self::assertEquals($expected, $this->strip_expected_dates($actual));
    }

    public function test_get_as_participation_manager(): void {
        $subject_instance = self::$about_user_but_not_participating;
        $args = ['subject_instance_id' => $subject_instance->id];

        $manager = self::getDataGenerator()->create_user();
        $employee = self::$about_user_but_not_participating->subject_user;

        self::setUser($manager);

        $context = $this->create_webapi_context(self::QUERY);
        $context->set_relevant_context($subject_instance->get_context());

        $returned_subject_instance = $this->resolve_graphql_query(self::QUERY, $args);
        self::assertNull($returned_subject_instance);

        $this->setup_manager_employee_job_assignment($manager, $employee);

        $returned_subject_instance = $this->resolve_graphql_query(self::QUERY, $args);
        $this->assertEquals(self::$about_user_but_not_participating->id, $returned_subject_instance->id);
    }

    public function test_failed_ajax_query(): void {
        $args = [
            'subject_instance_id' => self::$about_user_and_participating->get_id()
        ];

        $feature = 'performance_activities';
        advanced_feature::disable($feature);
        $result = $this->parsed_graphql_operation(self::QUERY, $args);
        $this->assert_webapi_operation_failed($result, 'Feature performance_activities is not available.');
        advanced_feature::enable($feature);

        $result = $this->parsed_graphql_operation(self::QUERY, []);
        $this->assert_webapi_operation_failed($result, 'subject_instance_id');

        $result = $this->parsed_graphql_operation(self::QUERY, ['subject_instance_id' => 0]);
        $this->assert_webapi_operation_failed($result, 'subject instance id');

        $this->setUser();
        $result = $this->parsed_graphql_operation(self::QUERY, $args);
        $this->assert_webapi_operation_failed($result, 'not logged in');
    }

}