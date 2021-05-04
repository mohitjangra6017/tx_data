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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

use contentmarketplace_linkedin\action\lil_sync_action;
use contentmarketplace_linkedin\config;
use core_phpunit\testcase;
use contentmarketplace_linkedin\entity\learning_object as learning_object_entity;
use contentmarketplace_linkedin\testing\generator;
use totara_contentmarketplace\token\token;
use totara_core\http\clients\simple_mock_client;
use totara_core\http\response;
use totara_core\http\response_code;

class contentmarketplace_linkedin_lil_sync_action_testcase extends testcase {

    /**
     * @return void
     */
    public function test_lil_sync_action(): void {
        $entities = learning_object_entity::repository()->get();
        $this->assertEquals(0, $entities->count());


        $generator = generator::instance();
        $generator->set_up_configuration();
        $time_now = time();
        $expired_token = new token('tokenone', ($time_now + HOURSECS));
        $generator->set_token($expired_token);

        $client = new simple_mock_client();

        // Mock API response.
        $client->mock_queue(
            new response(
                $generator->get_json_content_from_fixtures('response_1'),
                response_code::OK,
                [],
                'application/json'
            )
        );

        $action = new lil_sync_action();
        $action->set_api_client($client);
        $this->assertFalse($action->initial_run());
        $action->invoke();

        $entities = learning_object_entity::repository()->get();
        $this->assertEquals(2, $entities->count());
        $this->assertTrue($action->initial_run());
    }
}