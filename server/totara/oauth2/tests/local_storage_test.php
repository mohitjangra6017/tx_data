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
use totara_oauth2\local\storage;
use totara_oauth2\testing\generator;

class totara_oauth2_local_storage_testcase extends testcase {
    /**
     * @return void
     */
    public function test_get_access_token(): void {
        $generator = generator::instance();
        $entity = $generator->create_access_token(
            null,
            [
                "expires" => time() + HOURSECS,
                "access_token" => "access_token"
            ]
        );

        $storage = new storage();
        $token = $storage->getAccessToken("access_token");

        self::assertNotNull($token);
        self::assertIsArray($token);

        self::assertArrayHasKey("expires", $token);
        self::assertEquals($entity->expires, $token["expires"]);

        self::assertArrayHasKey("client_id", $token);
        self::assertEquals($entity->client_id, $token["client_id"]);

        self::assertArrayHasKey("scope", $token);
        self::assertEquals($entity->scope, $token["scope"]);
    }

    /**
     * @return void
     */
    public function test_get_expired_access_token(): void {
        $time_now = time();
        $generator = generator::instance();

        $generator->create_access_token(
            null,
            [
                "expires" => $time_now + HOURSECS,
                "access_token" => "access_token"
            ]
        );

        // The time now exceeding the one hour after.
        $storage = new storage($time_now + DAYSECS);
        $token = $storage->getAccessToken("access_token");

        self::assertNull($token);
    }

    /**
     * @return void
     */
    public function test_get_client_details(): void {
        $generator = generator::instance();
        $entity = $generator->create_client_provider("client_id_x");

        $storage = new storage();
        $client_details = $storage->getClientDetails("client_id_x");

        self::assertNotNull($client_details);
        self::assertIsArray($client_details);

        // Not used for now
        self::assertArrayHasKey("redirect_uri", $client_details);
        self::assertNull($client_details["redirect_uri"]);

        self::assertArrayHasKey("grant_types", $client_details);
        self::assertIsArray($client_details["grant_types"]);
        self::assertEquals(
            $entity->get_grant_types_as_array(),
            $client_details["grant_types"]
        );

        self::assertArrayHasKey("scope", $client_details);
        self::assertEquals($entity->scope, $client_details["scope"]);

        self::assertArrayHasKey("client_id", $client_details);
        self::assertEquals($entity->client_id, $client_details["client_id"]);
    }

    /**
     * @return void
     */
    public function test_get_client_scope(): void {
        $generator = generator::instance();
        $entity = $generator->create_client_provider("client_id_x", ["scope" => "scope"]);

        $storage = new storage();
        $scope = $storage->getClientScope("client_id_x");

        self::assertEquals($entity->scope, $scope);
    }
}