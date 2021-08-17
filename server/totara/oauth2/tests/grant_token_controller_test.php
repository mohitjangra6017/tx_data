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
use core_phpunit\testcase;
use totara_oauth2\controller\grant_token_controller;
use totara_oauth2\entity\access_token;
use totara_oauth2\facade\response_interface;
use totara_oauth2\grant_type;
use totara_oauth2\testing\mock_request;
use totara_oauth2\testing\generator;

class totara_oauth2_grant_token_controller_testcase extends testcase {
    /**
     * @return void
     */
    public function test_grant_token(): void {
        $generator = generator::instance();
        $client = $generator->create_client_provider();

        $request = mock_request::mock_post(
            [],
            [
                "grant_type" => grant_type::get_client_credentials(),
                "client_id" => $client->client_id,
                "client_secret" => $client->client_secret
            ],
        );

        $controller = new grant_token_controller($request, time());
        $response = $controller->action();

        self::assertInstanceOf(response_interface::class, $response);

        $access_token = $response->getParameter("access_token");
        self::assertNotNull($access_token);

        $token_entity = access_token::repository()->find_by_token($access_token);
        self::assertNotNull($token_entity);
        self::assertEquals($client->client_id, $token_entity-> client_id);
        self::assertNull($client->scope);

        $token_type = $response->getParameter("token_type");
        self::assertNotNull($token_type);
        self::assertEquals("Bearer", $token_type);
    }

    /**
     * @return void
     */
    public function test_cannot_grant_token(): void {
        $request = mock_request::mock_post(
            [],
            [
                "grant_type" => grant_type::get_client_credentials(),
                "client_id" => "client_id",
                "client_secret" => "client_secret"
            ]
        );

        $controller = new  grant_token_controller($request, time());
        $response = $controller->action();

        self::assertInstanceOf(response_interface::class, $response);
        $error = $response->getParameter("error");

        self::assertNotNull($error);
        self::assertEquals("invalid_client", $error);

        $error_description = $response->getParameter("error_description");
        self::assertNotNull($error_description);
        self::assertEquals("The client credentials are invalid", $error_description);
    }
}