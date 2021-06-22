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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package core
 */

use contentmarketplace_linkedin\constants;
use contentmarketplace_linkedin\sync_action\sync_learning_asset;
use contentmarketplace_linkedin\config;
use contentmarketplace_linkedin\testing\generator;
use core_phpunit\testcase;
use totara_contentmarketplace\token\token;
use totara_core\http\clients\simple_mock_client;
use totara_core\http\response;
use totara_core\http\response_code;
use core\orm\query\builder;
use contentmarketplace_linkedin\entity\learning_object;

class contentmarketplace_linkedin_initial_sync_learning_asset_testcase extends testcase {
    /**
     * @return void
     */
    protected function setUp(): void {
        $generator = generator::instance();
        $generator->set_up_configuration();

        $token = new token('tokenone', time() + DAYSECS);
        $generator->set_token($token);
    }

    /**
     * @return void
     */
    public function test_initial_sync(): void {
        $db = builder::get_db();
        $generator = generator::instance();

        // Initial run should not set the function.
        self::assertFalse(config::completed_initial_sync_learning_asset());
        self::assertNull(config::last_time_sync_learning_asset());

        $client = new simple_mock_client();
        $client->mock_queue(
            new response(
                $generator->get_json_content_from_fixtures('response_1'),
                response_code::OK,
                [],
                'application/json'
            )
        );

        // Mock second page which is empty to end the process
        $client->mock_queue(
            new response(
                $generator->get_json_content_from_fixtures('empty_response'),
                response_code::OK,
                [],
                'application/json'
            )
        );

        $time_now = time();

        $sync = new sync_learning_asset(true, $time_now);
        $sync->set_api_client($client);
        $sync->set_asset_types(constants::ASSET_TYPE_COURSE);

        // Before run there should be no record.
        self::assertEquals(0, $db->count_records(learning_object::TABLE));

        // After sync there should be two records add to the system.
        $sync->invoke();
        self::assertEquals(2, $db->count_records(learning_object::TABLE));

        $records = $db->get_records(learning_object::TABLE, null, 'id');

        // Reset array keys, in order to get two records from array assignment.
        $records = array_values($records);
        [$record_one, $record_two] = $records;

        // From response_1.json fixture file.
        self::assertEquals('urn:li:lyndaCourse:252', $record_one->urn);
        self::assertEquals('urn:li:lyndaCourse:260', $record_two->urn);

        self::assertEquals('en', $record_one->locale_language);
        self::assertEquals('en', $record_two->locale_language);

        self::assertEquals('US', $record_one->locale_country);
        self::assertEquals('US', $record_two->locale_country);

        self::assertTrue(config::completed_initial_sync_learning_asset());
        self::assertEquals($time_now, config::last_time_sync_learning_asset());
    }

    /**
     * @return void
     */
    public function test_initial_sync_with_flag_completed(): void {
        $db = builder::get_db();
        $generator = generator::instance();

        // Originally, there are no records.
        self::assertEquals(0, $db->count_records(learning_object::TABLE));

        // Mock this queue, just to prove that this reponse will never be hit.
        $client = new simple_mock_client();
        $client->mock_queue(
            new response(
                $generator->get_json_content_from_fixtures('response_1'),
                response_code::OK,
                [],
                'application/json'
            )
        );

        // Mock second page which is empty to end the process
        $client->mock_queue(
            new response(
                $generator->get_json_content_from_fixtures('empty_response'),
                response_code::OK,
                [],
                'application/json'
            )
        );

        $time_now = time();

        config::save_last_time_sync_learning_asset($time_now - DAYSECS);
        config::save_completed_initial_sync_learning_asset(true);

        self::assertNotEquals($time_now, config::last_time_sync_learning_asset());
        self::assertEquals($time_now - DAYSECS, config::last_time_sync_learning_asset());

        $sync = new sync_learning_asset(true, $time_now);
        $sync->set_api_client($client);
        $sync->set_asset_types(constants::ASSET_TYPE_COURSE);

        $sync->invoke();

        // Nothing is sync, because the flag initial sync had been used.
        self::assertEquals(0, $db->count_records(learning_object::TABLE));
        self::assertNotEquals($time_now, config::last_time_sync_learning_asset());
        self::assertEquals($time_now - DAYSECS, config::last_time_sync_learning_asset());
    }

    /**
     * @return void
     */
    public function test_initial_sync_with_pagination(): void {
        $db = builder::get_db();
        $generator = generator::instance();

        self::assertEquals(0, $db->count_records(learning_object::TABLE));

        $client = new simple_mock_client();
        $client->mock_queue($generator->create_json_response_from_fixture('response_1'));
        $client->mock_queue($generator->create_json_response_from_fixture('response_3'));

        $sync = new sync_learning_asset(true);
        $sync->set_api_client($client);
        $sync->set_asset_types(constants::ASSET_TYPE_COURSE);

        $sync->invoke();

        // json response 1 has 2 records. and json response 3 has another two records. Which we end up to have
        // 4 records within our database.
        self::assertEquals(4, $db->count_records(learning_object::TABLE));

        $urns = [
            'urn:li:lyndaCourse:252',
            'urn:li:lyndaCourse:253',
            'urn:li:lyndaCourse:260',
            'urn:li:lyndaCourse:261'
        ];

        foreach ($urns as $urn) {
            self::assertTrue(
                $db->record_exists(learning_object::TABLE, ['urn' => $urn])
            );
        }
    }

    /**
     * @return void
     */
    public function test_initial_sync_with_empty_response(): void {
        $db = builder::get_db();
        $generator = generator::instance();

        self::assertEquals(0, $db->count_records(learning_object::TABLE));

        $client = new simple_mock_client();
        $client->mock_queue($generator->create_json_response_from_fixture('empty_response'));

        $sync = new sync_learning_asset(true);
        $sync->set_api_client($client);
        $sync->set_asset_types(constants::ASSET_TYPE_COURSE);

        $sync->invoke();

        // The response is empty, hence nothing added
        self::assertEquals(0, $db->count_records(learning_object::TABLE));
    }

    /**
     * @return void
     */
    public function test_initial_sync_with_duplicated_record(): void {
        $db = builder::get_db();
        $generator = generator::instance();

        $generator->create_learning_object('urn:li:lyndaCourse:252');
        self::assertEquals(1, $db->count_records(learning_object::TABLE));

        $client = new simple_mock_client();
        $client->mock_queue($generator->create_json_response_from_fixture('response_1'));

        $sync = new sync_learning_asset(true);
        $sync->set_api_client($client);
        $sync->set_asset_types(constants::ASSET_TYPE_COURSE);

        try {
            $sync->invoke();
            self::fail("Expects the sync learning asset to yield errors");
        } catch (dml_write_exception $exception) {
            $message = $exception->getMessage();

            // Different db vendor will yield different errors, this is the best we can assert for now.
            self::assertStringContainsString("error writing to database", strtolower($message));
            self::assertStringContainsString("duplicate key value", strtolower($message));
        }
    }
}