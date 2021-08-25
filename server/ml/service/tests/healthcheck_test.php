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
 * @author Cody Finegan <cody.finegan@totaralearning.com>
 * @package ml_service
 */
defined('MOODLE_INTERNAL') || die();

use core_phpunit\testcase;
use ml_service\api;
use ml_service\healthcheck;
use totara_core\http\response;

class ml_service_healthcheck_testcase extends testcase {

    /**
     * Data provider for the test_invalid_state
     *
     * @return array
     */
    public function invalid_state_data(): array {
        return [
            [null, 'abcd12345'],
            ['http://example.com', null],
            [null, null],
        ];
    }

    /**
     * @dataProvider invalid_state_data
     * @param string|null $url
     * @param string|null $key
     * @return void
     */
    public function test_invalid_state(?string $url, ?string $key): void {
        global $CFG;

        // Mocking the API object
        $mock_api = $this->createMock(api::class);

        $healthcheck = healthcheck::make($mock_api);

        self::assertNull($healthcheck->get_totara_to_service());
        self::assertNull($healthcheck->get_service_to_totara());
        self::assertEmpty($healthcheck->get_error_messages());
        self::assertEmpty($healthcheck->get_other_info());

        // Assert no configured service
        $CFG->ml_service_url = $url;
        $CFG->ml_service_key = $key;
        $healthcheck->check_health();

        self::assertFalse($healthcheck->get_totara_to_service());
        self::assertNull($healthcheck->get_service_to_totara());
        self::assertEmpty($healthcheck->get_other_info());
        self::assertEqualsCanonicalizing([
            get_string('error_no_config_defined', 'ml_service')
        ], $healthcheck->get_error_messages());
    }

    /**
     * Test the behaviour when Totara cannot connect to the service
     */
    public function test_failed_communication(): void {
        global $CFG;

        // Mocking the API object
        $mock_api = $this->createMock(api::class);
        $CFG->ml_service_url = 'http://example.com';
        $CFG->ml_service_key = 'abcd1234';

        $healthcheck = healthcheck::make($mock_api);

        $failed_response = new response(
            json_encode(['error' => 'Error message']),
            500,
            []
        );
        $mock_api->method('get')
            ->with('/health-check')
            ->willReturn($failed_response);

        $healthcheck->check_health();
        self::assertFalse($healthcheck->get_totara_to_service());
        self::assertNull($healthcheck->get_service_to_totara());
        self::assertEmpty($healthcheck->get_other_info());
        self::assertEqualsCanonicalizing([
            'Error message',
            'Service Status Code: 500',
        ], $healthcheck->get_error_messages());
    }

    /**
     * Test the behaviour when the service cannot connect back to Totara
     */
    public function test_service_failure(): void {
        global $CFG;

        // Mocking the API object
        $mock_api = $this->createMock(api::class);
        $CFG->ml_service_url = 'http://example.com';
        $CFG->ml_service_key = 'abcd1234';

        $healthcheck = healthcheck::make($mock_api);

        $response = new response(
            json_encode([
                'success' => false,
                'errors' => ['Error 1', 'Error 2'],
                'totara' => [
                    'elapsed_seconds' => 9,
                    'totara_ip' => '127.0.0.1',
                    'url' => 'http://example.com/totara/site',
                ]
            ]),
            200,
            []
        );
        $mock_api->method('get')
            ->with('/health-check')
            ->willReturn($response);

        $healthcheck->check_health();
        self::assertTrue($healthcheck->get_totara_to_service());
        self::assertFalse($healthcheck->get_service_to_totara());
        self::assertEqualsCanonicalizing([
            'elapsed_seconds' => 9,
            'totara_ip' => '127.0.0.1',
            'url' => 'http://example.com/totara/site'
        ], $healthcheck->get_other_info());
        self::assertEqualsCanonicalizing([
            'Error 1',
            'Error 2'
        ], $healthcheck->get_error_messages());
    }

    /**
     * Test behaviour when service can connect back to totara
     */
    public function test_service_success(): void {
        global $CFG;

        // Mocking the API object
        $mock_api = $this->createMock(api::class);
        $CFG->ml_service_url = 'http://example.com';
        $CFG->ml_service_key = 'abcd1234';

        $healthcheck = healthcheck::make($mock_api);

        $response = new response(
            json_encode([
                'success' => true,
                'totara' => [
                    'elapsed_seconds' => 9,
                    'totara_ip' => '127.0.0.1',
                    'url' => 'http://example.com/totara/site',
                ]
            ]),
            200,
            []
        );
        $mock_api->method('get')
            ->with('/health-check')
            ->willReturn($response);

        $healthcheck->check_health();
        self::assertTrue($healthcheck->get_totara_to_service());
        self::assertTrue($healthcheck->get_service_to_totara());
        self::assertEqualsCanonicalizing([
            'elapsed_seconds' => 9,
            'totara_ip' => '127.0.0.1',
            'url' => 'http://example.com/totara/site'
        ], $healthcheck->get_other_info());
        self::assertEmpty($healthcheck->get_error_messages());
    }
}