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
use totara_mvc\tui_view;
use totara_oauth2\testing\generator;
use totara_oauth2\controller\oauth2_provider_controller;

class totara_oauth2_oauth2_provider_controller_testcase extends testcase {
    /**
     * @return void
     */
    public function test_oauth2_provider_controller_visited_by_admin(): void {
        self::setAdminUser();

        $generator = generator::instance();
        $provider = $generator->create_client_provider("client_id_one");

        $controller = new oauth2_provider_controller();

        $result = $controller->action();
        $reflection = new ReflectionClass($result);
        $property = $reflection->getProperty('data');
        $property->setAccessible(true);
        $data = $property->getValue($result);

        self::assertEquals('OAuth 2 provider details', $data['title']);
        self::assertEquals($provider->id, $data['id']);
    }

    /**
     * @return void
     */
    public function test_oauth2_provider_controller_without_client_provider_record(): void {
        self::setAdminUser();

        $controller = new oauth2_provider_controller();
        $result = $controller->action();
        $reflection = new ReflectionClass($result);
        $property = $reflection->getProperty('data');
        $property->setAccessible(true);
        $data = $property->getValue($result);

        self::assertEquals('OAuth 2 provider details', $data['title']);
        self::assertArrayNotHasKey('id', $data);

    }

    /**
     * @return void
     */
    public function test_oauth2_provider_controller_visited_by_system_user(): void {
        $gen = self::getDataGenerator();
        $user = $gen->create_user();

        self::setUser($user);

        $generator = generator::instance();
        $generator->create_client_provider("client_id_one");

        self::expectException(moodle_exception::class);
        self::expectExceptionMessage('Access denied');
        $controller = new oauth2_provider_controller();
        $controller->process();
    }
}