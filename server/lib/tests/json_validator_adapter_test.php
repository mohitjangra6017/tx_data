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
 * @package core
 */
use core_phpunit\testcase;
use core\json\validation_adapter;

class core_json_validator_adapter_testcase extends testcase {
    /**
     * @return void
     */
    public function test_validate_invalid_structure(): void {
        $dummy_data = new stdClass();
        $validator = validation_adapter::create_default();

        $invalid_structure = [
            "x",
            false,
            true,
            1,
            1.1
        ];
        foreach ($invalid_structure as $invalid_str) {
            try {
                $validator->validate($dummy_data, $invalid_str);
            } catch (coding_exception $e) {
                self::assertStringContainsString(
                    "Expect the structure argument to be either dummy object or an array.",
                    $e->getMessage()
                );
            }
        }
    }
}