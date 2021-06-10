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
 * @package totara_msteams
 */

use core_phpunit\testcase;
use totara_msteams\msteams_gateway_helper;

class totara_msteams_msteams_gateway_local_helper_testcase extends testcase {

    public function test_remote_procedure_call_success(): void {
        $domain = 'www.example.com';

        \mock_http_request::add_mock_domain($domain);

        $result = msteams_gateway_helper::remote_procedure_call_success($domain);
        self::assertTrue($result);
    }

    public function test_get_tenant_id(): void {
        $expected_id = \mock_http_request::get_tenant_id();
        $response = \mock_http_request::get_mock_response();

        $id = msteams_gateway_helper::get_tenant_id($response);

        self::assertEquals($expected_id, $id);
    }

    protected function setUp(): void {
        global $CFG;
        require_once("{$CFG->dirroot}/totara/msteams/tests/fixtures/mock_http_request.php");
    }

    protected function tearDown(): void {
        \mock_http_request::clear();
    }
}