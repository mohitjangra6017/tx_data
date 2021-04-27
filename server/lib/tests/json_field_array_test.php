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
use core_phpunit\testcase;
use core\json\schema\field\field_array;

class core_json_field_array_testcase extends testcase {
    /**
     * @return void
     */
    public function test_validate_value(): void {
        $generic_field = new field_array('name_one');
        $specific_field = new field_array('name_two', PARAM_ALPHA);

        self::assertEquals(
            "The value for field 'name_one' is not an array",
            $generic_field->validate(1)
        );

        self::assertEquals(
            "The value for field 'name_two' is not an array",
            $specific_field->validate(1)
        );

        self::assertEquals(
            "The value for field 'name_one' is not an array",
            $generic_field->validate('c')
        );

        self::assertEquals(
            "The value for field 'name_two' is not an array",
            $specific_field->validate('c')
        );

        self::assertEquals(
            "The value for field 'name_one' is not an array",
            $generic_field->validate(true)
        );

        self::assertEquals(
            "The value for field 'name_two' is not an array",
            $specific_field->validate(true)
        );

        self::assertEquals(
            "The value for field 'name_one' is not an array",
            $generic_field->validate(false)
        );

        self::assertEquals(
            "The value for field 'name_two' is not an array",
            $specific_field->validate(false)
        );

        self::assertEquals(
            "The value for field 'name_one' is not an array",
            $generic_field->validate(15.5)
        );

        self::assertEquals(
            "The value for field 'name_two' is not an array",
            $specific_field->validate(15.5)
        );

        self::assertEquals(
            "The single value within array of field 'name_one' is not a scalar",
            $generic_field->validate(['c', ['c']])
        );

        self::assertEquals(
            "The single value within array of field 'name_two' is not a scalar",
            $specific_field->validate(['c', ['c']])
        );

        self::assertNull($generic_field->validate(null));
        self::assertNull($specific_field->validate(null));
        self::assertNull($generic_field->validate([15, 'c', '3']));
        self::assertNull($specific_field->validate([15, 'c', '3']));
    }

    /**
     * @return void
     */
    public function test_clean_value(): void {
        $generic_field = new field_array('name_one');
        $specific_field = new field_array('name_two', PARAM_ALPHANUMEXT);

        self::assertEquals(
            [15, 'cd', true],
            $generic_field->clean([15, 'cd', true])
        );

        self::assertEquals(
            ['15', 'cd', 'cdx_1', ''],
            $specific_field->clean([15, 'cd', 'cdx_1', false])
        );

        self::assertEquals([], $specific_field->clean(null));
        self::assertEquals([], $generic_field->clean(null));
    }

    /**
     * @return void
     */
    public function test_clean_value_with_exception_for_non_array(): void {
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('The value for field \'name_two\' is not an array');

        $specific_field = new field_array('name_two', PARAM_ALPHANUM);
        $specific_field->clean('cd');
    }

    /**
     * @return void
     */
    public function test_clean_value_that_contains_non_scalar_value(): void {
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage(
            "There is non scalar value within the array value of field 'name_two'"
        );

        $specific_field = new field_array('name_two', PARAM_ALPHANUM);
        $specific_field->clean([new stdClass(), $this, []]);
    }
}