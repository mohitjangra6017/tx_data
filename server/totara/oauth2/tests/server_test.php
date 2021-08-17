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
 * @package totara_oauth2
 */

use core\orm\query\builder;
use core_phpunit\testcase;
use totara_oauth2\entity\access_token;
use totara_oauth2\grant_type;
use totara_oauth2\server;
use totara_oauth2\testing\generator;
use totara_oauth2\testing\mock_request;

class totara_oauth2_server_testcase extends testcase {
    /**
     * @return void
     */
    public function test_request_token(): void {
        $generator = generator::instance();
        $client = $generator->create_client_provider();

        $server = server::boot();
        $request = new mock_request(
            [],
            [
                "grant_type" => grant_type::get_client_credentials(),
                "client_id" => $client->client_id,
                "client_secret" => $client->client_secret
            ],
            ["REQUEST_METHOD" => "POST"],
        );

        $db = builder::get_db();
        self::assertEquals(0, $db->count_records(access_token::TABLE, ["client_id" => $client->client_id]));

        // Once response is being processed, then we will get a new record of access token.
        $response = $server->handle_token_request($request);
        self::assertEquals(1, $db->count_records(access_token::TABLE, ["client_id" => $client->client_id]));

        $access_token = $response->getParameter("access_token");
        self::assertNotNull($access_token);

        $token_entity = access_token::repository()->find_by_token($access_token);
        self::assertNotNull($token_entity);

        $token_type = $response->getParameter("token_type");
        self::assertNotNull($token_type);
        self::assertEquals("Bearer", $token_type);
    }

    /**
     * @return void
     */
    public function test_request_token_with_invalid_method(): void {
        $generator = generator::instance();
        $client = $generator->create_client_provider();

        $request = new mock_request(
            [],
            [
                "grant_type" => grant_type::get_client_credentials(),
                "client_id" => $client->client_id,
                "client_secret" => $client->client_secret,
            ],
            ["REQUEST_METHOD" => "GET"]
        );

        $server = server::boot();
        $response = $server->handle_token_request($request);

        $access_token = $response->getParameter("access_token");
        $token_type = $response->getParameter("token_type");

        self::assertNull($access_token);
        self::assertNull($token_type);

        $error = $response->getParameter("error");
        self::assertNotNull($error);
        self::assertEquals("invalid_request", $error);

        $error_description = $response->getParameter("error_description");
        self::assertNotNull($error_description);
        self::assertEquals(
            "The request method must be POST when requesting an access token",
            $error_description
        );
    }

    /**
     * @return void
     */
    public function test_verify_token(): void {
        $generator = generator::instance();
        $time_now = time();

        $client = $generator->create_client_provider();
        $token = $generator->create_access_token_from_client_provider($client, $time_now + HOURSECS);

        $request = new mock_request(
            [],
            [],
            ["REQUEST_METHOD" =>  "GET"],
            ["AUTHORIZATION" => "Bearer {$token->access_token}"]
        );

        $server = server::boot($time_now);
        $result = $server->is_request_verified($request);

        self::assertTrue($result);
    }

    /**
     * @return void
     */
    public function test_cannot_verify_token_due_to_expired(): void {
        $time_now = time();
        $generator = generator::instance();

        $client = $generator->create_client_provider();
        $token = $generator->create_access_token_from_client_provider($client, $time_now + HOURSECS);

        $request = new mock_request(
            [],
            [],
            ["HTTP_METHOD" => "GET"],
            ["AUTHORIZATION" => "Bearer {$token->access_token}"]
        );

        $server = server::boot($time_now + (HOURSECS * 2));
        $result = $server->is_request_verified($request);

        self::assertFalse($result);
    }

    /**
     * @return void
     */
    public function test_verify_token_with_success_response(): void {
        $time_now = time();
        $generator = generator::instance();

        $client = $generator->create_client_provider();
        $token = $generator->create_access_token_from_client_provider($client, $time_now + HOURSECS);

        $request = new mock_request(
            [],
            [],
            ["REQUEST_METHOD" =>  "GET"],
            ["AUTHORIZATION" => "Bearer {$token->access_token}"]
        );

        $server = server::boot($time_now);
        $response = $server->verify_resource_request($request);

        self::assertNull($response->getParameter("error"));
        self::assertNull($response->getParameter("error_description"));
    }

    /**
     * @return void
     */
    public function test_verify_token_with_failure_response(): void {
        $request = new mock_request(
            [],
            [],
            ["REQUEST_METHOD" => "GET"],
            ["AUTHORIZATION" => "Bearer token"],
        );

        $server = server::boot();
        $response = $server->verify_resource_request($request);

        $error = $response->getParameter("error");
        $error_description = $response->getParameter("error_description");

        self::assertNotNull($error);
        self::assertEquals("invalid_token", $error);

        self::assertNotNull($error_description);
        self::assertEquals(
            "The access token provided is invalid",
            $error_description
        );
    }
}