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

use core\json\schema\field\field_alphaext;
use core_phpunit\testcase;

class core_json_field_alphaext_testcase extends testcase {
    /**
     * @return void
     */
    public function test_validate_value(): void {
        $field = new field_alphaext('name');
        self::assertEquals(
            "The value of field 'name' is not a string",
            $field->validate(true)
        );

        self::assertEquals(
            "The value of field 'name' is not a string",
            $field->validate(15)
        );

        self::assertEquals(
            "The value of field 'name' is not a string",
            $field->validate(false)
        );

        self::assertEquals(
            "The value of field 'name' is not a string",
            $field->validate(15.5)
        );

        self::assertEquals(
            "The value of field 'name' is invalid",
            $field->validate('hello world')
        );

        self::assertEquals(
            "The value of field 'name' is invalid",
            $field->validate('hello+world')
        );

        self::assertEquals(
            "The value of field 'name' is invalid",
            $field->validate('hello0012')
        );

        self::assertNull($field->validate(null));
        self::assertNull($field->validate('textabcde'));
        self::assertNull($field->validate('abcde_eee'));
        self::assertNull($field->validate('abcde-eee'));
    }

    /**
     * @return void
     */
    public function test_clean_value(): void {
        $field = new field_alphaext('name');
        self::assertEquals('abcdxe', $field->clean('abcdxe'));
        self::assertEquals(null, $field->clean(null));
        self::assertEquals('', $field->clean(false));
        self::assertEquals('', $field->clean(125));
        self::assertEquals('zxc_c', $field->clean('zxc_c'));
        self::assertEquals('zxc-c', $field->clean('zxc-c'));
        self::assertEquals('zxcc', $field->clean('zxc+c'));
    }
}