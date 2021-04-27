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

use core\json\schema\field\field_alpha;
use core\json\schema\field\field_alphanum;
use core\json\schema\field\field_int;
use core\json\schema\field\field_text;
use core\json\schema\object_container;
use core_phpunit\testcase;

class core_json_object_container_testcase extends testcase {
    /**
     * @return void
     */
    public function test_instantiate_with_exception(): void {
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("Cannot instantiate the object container with empty fields");

        object_container::create();
    }

    /**
     * @return void
     */
    public function test_validate_container(): void {
        $container = object_container::create(
            new field_alpha('field_1', false),
            new field_alphanum('field_2'),
            new field_int('field_3')
        );

        self::assertEquals(
            "Missing field 'field_2' in the json object",
            $container->validate([
                'field_1' => 'ccc',
                'field_3' => 1
            ])
        );

        self::assertEquals(
            "The value for object schema is not an array",
            $container->validate('dwqccc')
        );

        self::assertEquals(
            "The value of field 'field_2' is invalid within the json object",
            $container->validate([
                'field_2' => 'hello 1',
                'field_3' => 15
            ])
        );

        self::assertNull(
            $container->validate([
                'field_1' => 'helloworld',
                'field_2' => 'helloworld2',
                'field_3' => 496
            ])
        );

        self::assertNull(
            $container->validate([
                'field_2' => 'helloworld2',
                'field_3' => 496
            ])
        );
    }

    /**
     * @return void
     */
    public function test_clean_container(): void {
        $container = object_container::create(
            new field_text('text'),
            new field_int('number')
        );

        self::assertSame(
            [
                'text' => 'alert("hello world")',
                'number' => 496
            ],
            $container->clean([
                'text' => '<script>alert("hello world")</script>',
                'number' => '496'
            ])
        );

        self::assertSame(
            [
                'text' => 'alert("hello world")',
                'number' => 496
            ],
            $container->clean([
                'text' => '<script>alert("hello world")</script>',
                'number' => 496
            ])
        );
    }

    /**
     * @return void
     */
    public function test_clean_container_with_missing_field_and_does_not_yield_error(): void {
        $container = object_container::create(
            new field_text('text', false),
            new field_int('number')
        );

        self::assertSame(
            ['number' => 496],
            $container->clean(['number' => '496'])
        );

        self::assertSame(
            [
                'text' => 'hello world',
                'number' => 496
            ],
            $container->clean([
                'text' => 'hello world',
                'number' => 496
            ])
        );
    }

    /**
     * @return void
     */
    public function test_clean_container_with_missing_field_and_yeild_error(): void {
        $container = object_container::create(
            new field_text('text'),
            new field_int('number')
        );

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("Missing field 'text' within the json object");

        $container->clean(['number' => 496]);
    }
}