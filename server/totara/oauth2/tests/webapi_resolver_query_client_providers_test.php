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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_oauth2
 */

use core_phpunit\testcase;
use totara_oauth2\testing\generator;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_oauth2_webapi_resolver_query_client_providers_testcase extends testcase {
    use webapi_phpunit_helper;

    private const QUERY = 'totara_oauth2_client_providers';

    /**
     * @return void
     */
    public function test_client_providers_by_admin(): void {
        self::setAdminUser();

        $generator = generator::instance();
        $provider = $generator->create_client_provider("client_id_one");

        $result = $this->resolve_graphql_query(
            self::QUERY,
            [
                'input' => [
                    'filters' => [
                        'id' => $provider->id
                    ]
                ],
            ]
        );

        self::assertNotEmpty($result);
        $model = $result->first();
        self::assertEquals($provider->id, $model->id);
    }

    /**
     * @return void
     */
    public function test_client_providers_with_empty_id(): void {
        self::setAdminUser();

        $generator = generator::instance();
        $generator->create_client_provider("client_id_one");

        $result = $this->resolve_graphql_query(
            self::QUERY,
            [
                'input' => [
                    'filters' => []
                ],
            ]
        );

        self::assertNotEmpty($result);
        self::assertEquals(1, count($result));
    }

    /**
     * @return void
     */
    public function test_client_providers_by_system_user(): void {
        $gen = self::getDataGenerator();
        $user = $gen->create_user();

        self::setUser($user);

        $generator = generator::instance();
        $provider = $generator->create_client_provider("client_id_one");

        self::expectException(required_capability_exception::class);
        self::expectExceptionMessage('Sorry, but you do not currently have permissions to do that (Change site configuration)');
        $this->resolve_graphql_query(
            self::QUERY,
            [
                'input' => [
                    'filters' => [
                        'id' => $provider->id
                    ]
                ],
            ]
        );
    }

}