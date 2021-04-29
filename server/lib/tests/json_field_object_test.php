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

use core\json\schema\field\field_boolean;
use core_phpunit\testcase;
use core\json\schema\field\field_object;
use core\json\schema\object_container;
use core\json\schema\field\field_text;
use core\json\schema\field\field_int;

class core_json_field_object_testcase extends testcase {
    /**
     * @return void
     */
    public function test_validate_field(): void {
        $field = new field_object(
            'field_obj',
            object_container::create(
                new field_text('field_1'),
                new field_int('field_2'),
                new field_boolean('field_3', false)
            )
        );

        self::assertEquals(
            "Missing field 'field_2' in the json object at field 'field_obj'",
            $field->validate([
                'field_1' => 'Hello world',
                'field_3' => false
            ])
        );

        self::assertNull(
            $field->validate([
                'field_1' => 'hello world',
                'field_2' => 496,
                'field_3' => 'yes'
            ])
        );

        self::assertNull(
            $field->validate([
                'field_1' => 'hello world',
                'field_2' => 496,
            ])
        );
    }

    /**
     * @return void
     */
    public function test_clean_field_with_exception(): void {
        $field = new field_object(
            'field_obj',
            object_container::create(
                new field_text('field_1'),
                new field_int('field_2')
            )
        );

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("Missing field 'field_1' within the json object");

        $field->clean([
            'field_2' => '496'
        ]);
    }

    /**
     * @return void
     */
    public function test_clean_field(): void {
        $field = new field_object(
            'field_obj',
            object_container::create(
                new field_text('field_1', false),
                new field_boolean('field_2')
            )
        );

        self::assertSame(
            ['field_2' => false],
            $field->clean(['field_2' => 'no'])
        );

        self::assertSame(
            [
                'field_1' => 'abcde',
                'field_2' => true
            ],
            $field->clean([
                'field_1' => 'abcde',
                'field_2' => 'true'
            ])
        );

        self::assertSame(
            [
                'field_1' => 'alert("hello world")',
                'field_2' => false
            ],
            $field->clean([
                'field_1' => /** @lang text */ '<script>alert("hello world")</script>',
                'field_2' => 'false'
            ])
        );
    }
}