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
use core\json\schema\field\field_collection;
use core\json\schema\field\field_int;
use core\json\schema\field\field_text;
use core_phpunit\testcase;

class core_json_field_collection_testcase extends testcase {
    /**
     * @return void
     */
    public function test_validate(): void {
        $field = new field_collection(
            'field_col',
            collection::create_from_fields_of_obj_container(
                new field_text('field_1'),
                new field_int('field_2', false)
            )
        );

        self::assertEquals(
            "Missing field 'field_1' in the json object at index 1 of field 'field_col'",
            $field->validate([
                [
                    'field_1' => 'hello world',
                    'field_2' => 1
                ],
                [
                    'field_2' => 496
                ]
            ])
        );

        self::assertNull(
            $field->validate([
                ['field_1' => 'hello_world'],
                [
                    'field_1' => 'hello',
                    'field_2' => 496
                ]
            ])
        );

        self::assertEquals(
            "The value collection is not an array of field 'field_col'",
            $field->validate(false)
        );
    }
}