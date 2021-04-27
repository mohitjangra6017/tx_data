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

use core\json\schema\collection;
use core\json\schema\field\field_boolean;
use core\json\schema\field\field_int;
use core_phpunit\testcase;

class core_json_collection_container_testcase extends testcase {
    /**
     * @return void
     */
    public function test_validate_collection(): void {
        $collection = collection::create_from_fields_of_obj_container(
            new field_boolean('field_1'),
            new field_int('field_2', false)
        );

        self::assertEquals(
            "The value collection is not an array",
            $collection->validate(new stdClass())
        );

        self::assertEquals(
            "Missing field 'field_1' in the json object at index 1",
            $collection->validate([
                [
                    'field_1' => true,
                ],
                [
                    'field_2' => 15
                ]
            ])
        );

        self::assertEquals(
            "The value for object schema is not an array at index field_1",
            $collection->validate([
                'field_1' => true,
                'field_2' => 15
            ])
        );

        self::assertNull(
            $collection->validate([
                ['field_1' => true],
                [
                    'field_2' => '15',
                    'field_1' => 'yes'
                ]
            ])
        );
    }

    /**
     * @return void
     */
    public function test_clean_with_exception(): void {
        $collection = collection::create_from_fields_of_obj_container(
            new field_boolean('field_1'),
            new field_int('field_2', false)
        );

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('Expects the parameter value to be an array');

        $collection->clean(true);
    }

    /**
     * @return void
     */
    public function test_clean_with_missing_fields(): void {
        $col = collection::create_from_fields_of_obj_container(
            new field_boolean('field_1'),
            new field_boolean('field_2')
        );

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("Missing field 'field_1' within the json object");

        $col->clean([
            [
                'field_1' => true,
                'field_2' => 'false'
            ],
            [
                'field_2' => 'yes'
            ]
        ]);
    }

    /**
     * @return void
     */
    public function test_clean(): void {
        $col = collection::create_from_fields_of_obj_container(
            new field_boolean('field_1'),
            new field_int('field_2', false)
        );

        self::assertSame(
            [
                [
                    'field_1' => true,
                    'field_2' => 496
                ],
                [
                    'field_1' => false
                ]
            ],
            $col->clean([
                [
                    'field_1' => 'yes',
                    'field_2' => '496'
                ],
                [
                    'field_1' => 'false'
                ]
            ])
        );
    }
}