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
use core\orm\query\builder;
use core_phpunit\testcase;
use totara_oauth2\entity\access_token;
use totara_oauth2\testing\generator as oauth2_generator;
use totara_xapi\controller\receiver_controller;
use totara_xapi\request\request;
use totara_xapi\response\json_result;

class contentmarketplace_linkedin_xapi_handler_testcase extends testcase {
    /**
     * @var access_token|null
     */
    private $access_token;

    /**
     * @var int|null
     */
    private $time_now;

    /**
     * @return void
     */
    protected function setUp(): void {
        $generator = oauth2_generator::instance();
        $this->time_now = time();

        $this->access_token = $generator->create_access_token(
            "some_client",
            ["expires" => $this->time_now + HOURSECS]
        );
    }

    /**
     * @return void
     */
    protected function tearDown(): void {
        $this->access_token = null;
        $this->time_now = null;
    }

    /**
     * @return void
     */
    public function test_handling_xapi_request_with_non_existing_email(): void {
        $request = request::create_from_global(
            ["component" => "contentmarketplace_linkedin"],
            [],
            ["Authorization" => "Bearer {$this->access_token->access_token}"],
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
                        "en-US" => progress::COMPLETED,
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
        $db = builder::get_db();
        self::assertTrue(
            $db->record_exists(access_token::TABLE, ["access_token" => $this->access_token->access_token])
        );

        // Executing the request, with the time that surpass the expiry time of access token.
        $request = request::create_from_global(
            ["component" => "contentmarketplace_linkedin"],
            [],
            ["Authorization" => "Bearer {$this->access_token->access_token}"],
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

        self::assertEquals("invalid_token", $data["error"]);
        self::assertEquals(
            "The access token provided is invalid",
            $data["error_description"]
        );
    }
}