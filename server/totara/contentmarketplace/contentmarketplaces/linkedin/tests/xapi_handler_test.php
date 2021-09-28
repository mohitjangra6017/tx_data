<?php
/**
 * This file is part of Totara Core
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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

use contentmarketplace_linkedin\dto\xapi\progress;
use contentmarketplace_linkedin\entity\user_completion;
use core\orm\query\builder;
use core_phpunit\testcase;
use totara_oauth2\entity\access_token;
use totara_oauth2\testing\generator as oauth2_generator;
use contentmarketplace_linkedin\testing\generator as linkedin_generator;
use totara_xapi\controller\receiver_controller;
use totara_xapi\entity\xapi_statement;
use totara_xapi\handler\factory;
use totara_xapi\model\xapi_statement as xapi_statement_model;
use totara_xapi\request\request;
use totara_xapi\response\json_result;
use contentmarketplace_linkedin\totara_xapi\statement;

/**
 * @group totara_contentmarketplace
 */
class contentmarketplace_linkedin_xapi_handler_testcase extends testcase {
    /**
     * @var int|null
     */
    private $time_now;

    /**
     * @return void
     */
    protected function setUp(): void {
        oauth2_generator::setup_required_configuration();
        $this->time_now = time();
    }

    /**
     * @return void
     */
    protected function tearDown(): void {
        $this->time_now = null;
    }

    /**
     * @return void
     */
    public function test_handling_xapi_request_with_non_existing_email(): void {
        $generator = oauth2_generator::instance();
        $access_token = $generator->create_access_token(
            "some_client",
            ["expires" => $this->time_now + HOURSECS]
        );

        $request = request::create_from_global(
            ["component" => "contentmarketplace_linkedin"],
            [],
            ["Authorization" => "Bearer {$access_token}"],
            ["REQUEST_METHOD" => "POST"]
        );

        $request->set_content(
            json_encode([
                "actor" => [
                    "mbox" => "mailto:bob@example.com",
                    "objectType" => "Agent"
                ],
                "result" => [
                    "completion" => true,
                    "duration" => "PT4M30S",
                    "extensions" => [
                        "https://w3id.org/xapi/cmi5/result/extensions/progress" => "100"
                    ]
                ],
                "verb" => [
                    "display" => [
                        "en-US" => "COMPLETED",
                    ],
                    "id" => "http://adlnet.gov/expapi/verbs/completed"
                ],
                "id" => "212tvkodls-csacx-487f-9jiv34-1i93ikkvnid",
                "object" => [
                    "definition" => [
                        "type" => "http://adlnet.gov/expapi/activities/course"
                    ],
                    "id" => "urn:lyndaCourse:252",
                    "objectType" => "Activity"
                ],
                "timestamp" => date(DATE_ISO8601, $this->time_now)
            ])
        );

        // Missing user with email bob@exammple.com

        try {
            $controller = new receiver_controller($request, $this->time_now);
            $controller->action();
            self::fail("Expecting the operation would yield an error");
        } catch (dml_missing_record_exception $e) {
            self::assertStringContainsString(
                "Can not find data record in database",
                $e->getMessage()
            );
        }
    }

    /**
     * @return void
     */
    public function test_handling_xapi_request_with_expired_token(): void {
        $generator = oauth2_generator::instance();
        $access_token = $generator->create_access_token(
            "some_client",
            ["expires" => $this->time_now - HOURSECS]
        );

        $db = builder::get_db();
        self::assertTrue(
            $db->record_exists(access_token::TABLE, ["identifier" => $access_token->getIdentifier()])
        );

        // Executing the request, with the time that surpass the expiry time of access token.
        $request = request::create_from_global(
            ["component" => "contentmarketplace_linkedin"],
            [],
            ["Authorization" => "Bearer {$access_token}"],
            ["REQUEST_METHOD" => "POST"]
        );

        $request->set_content("");

        $controller = new receiver_controller($request, $this->time_now + (DAYSECS * 2));
        $result = $controller->action();

        self::assertInstanceOf(json_result::class, $result);
        $data = $result->get_data();

        self::assertIsArray($data);
        self::assertNotEmpty($data);
        self::assertArrayHasKey("error", $data);
        self::assertArrayHasKey("error_description", $data);

        self::assertEquals("access_denied", $data["error"]);
        self::assertEquals(
            get_string("access_denied", "contentmarketplace_linkedin"),
            $data["error_description"]
        );
    }

    /**
     * @return void
     */
    public function test_handling_xapi_request_with_update_user_completion(): void {
        $oauth2_generator = oauth2_generator::instance();
        $token = $oauth2_generator->create_access_token();

        $generator = self::getDataGenerator();
        $user = $generator->create_user();

        $linkedin_generator = linkedin_generator::instance();
        $learning_object = $linkedin_generator->create_learning_object("urn:lyndaCourse:252");

        $create_xapi_statement = function (int $progress) use ($user, $learning_object): array {
            $text = "PROGRESSED";
            if (progress::PROGRESS_MAXIMUM === $progress) {
                $text = "COMPLETED";
            }
            return [
                "actor" => [
                    "mbox" => "mailto:{$user->email}",
                    "objectType" => "Agent"
                ],
                "result" => [
                    "completion" => (progress::PROGRESS_MAXIMUM === $progress),
                    "duration" => "PT4M30S",
                    "extensions" => [
                        "https://w3id.org/xapi/cmi5/result/extensions/progress" => $progress
                    ]
                ],
                "verb" => [
                    "display" => [
                        "en-US" => $text,
                    ],
                    "id" => sprintf("http://adlnet.gov/expapi/verbs/%s", strtolower($text)),
                ],
                "id" => "212tvkodls-csacx-487f-9jiv34-1i93ikkvnid",
                "object" => [
                    "definition" => [
                        "type" => "http://adlnet.gov/expapi/activities/course"
                    ],
                    "id" => $learning_object->urn,
                    "objectType" => "Activity"
                ],
                "timestamp" => date(DATE_ISO8601, $this->time_now)
            ];
        };


        $request = request::create_from_global(
            ["component" => "contentmarketplace_linkedin"],
            [],
            ["Authorization" => "Bearer {$token}"]
        );

        $request->set_content(json_encode($create_xapi_statement(39)));

        $db = builder::get_db();
        $controller = new receiver_controller($request, $this->time_now);

        self::assertEquals(0, $db->count_records(xapi_statement::TABLE));
        self::assertEquals(0, $db->count_records(user_completion::TABLE));

        $controller->action();
        self::assertEquals(1, $db->count_records(xapi_statement::TABLE));
        self::assertEquals(1, $db->count_records(user_completion::TABLE));

        $user_completion = user_completion::repository()->find_for_user_with_urn($user->id, $learning_object->urn);
        self::assertNotNull($user_completion);
        self::assertEquals(39, $user_completion->progress);
        self::assertFalse($user_completion->completion);

        // Update again with the controller.
        $request->set_content(json_encode($create_xapi_statement(progress::PROGRESS_MAXIMUM)));
        $controller = new receiver_controller($request, $this->time_now);
        $controller->action();

        $user_completion->refresh();

        // 2 xapi statements are being recorded
        self::assertEquals(2, $db->count_records(xapi_statement::TABLE));
        self::assertEquals(1, $db->count_records(user_completion::TABLE));

        self::assertEquals(progress::PROGRESS_MAXIMUM, $user_completion->progress);
        self::assertTrue($user_completion->completion);
    }

    /**
     * @return void
     */
    public function test_xpi_statement_get_user_id(): void {
        $token = oauth2_generator::instance()->create_access_token();
        $email = 'my.EMAIL@example.COM';
        $user = self::getDataGenerator()->create_user(['email' => $email, 'deleted' => 0]);
        $request = request::create_from_global(
            ["component" => "contentmarketplace_linkedin"],
            [],
            ["Authorization" => "Bearer {$token}"]
        );

        // Test with regular email.
        $request->set_content(json_encode($this->get_mock_response_data_by_email($user->email)));
        $handler = factory::create_handler('contentmarketplace_linkedin', $request, time());
        $statement = xapi_statement_model::create_from_request($request, $handler);
        self::assertEquals($user->id, $statement->user_id);

        // Test with uppercase email.
        $request->set_content(json_encode($this->get_mock_response_data_by_email(strtoupper($user->email))));
        $handler = factory::create_handler('contentmarketplace_linkedin', $request, time());
        $statement = xapi_statement_model::create_from_request($request, $handler);
        self::assertEquals($user->id, $statement->user_id);
    }

    /**
     * @param string $email
     * @return array
     */
    private function get_mock_response_data_by_email(string $email): array {
        return [
            "actor" => [
                "mbox" => "mailto:{$email}",
                "objectType" => "Agent"
            ],
            "result" => [
                "completion" => true,
                "duration" => "PT4M30S",
                "extensions" => [
                    "https://w3id.org/xapi/cmi5/result/extensions/progress" => "100"
                ]
            ],
            "verb" => [
                "display" => [
                    "en-US" => "COMPLETED",
                ],
                "id" => "http://adlnet.gov/expapi/verbs/completed"
            ],
            "id" => "212tvkodls-csacx-487f-9jiv34-1i93ikkvnid",
            "object" => [
                "definition" => [
                    "type" => "http://adlnet.gov/expapi/activities/course"
                ],
                "id" => "urn:lyndaCourse:252",
                "objectType" => "Activity"
            ],
            "timestamp" => date(DATE_ISO8601, $this->time_now)
        ];
    }
}