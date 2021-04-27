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
use core\json\schema\field\field_boolean;

class core_json_field_boolean_testcase extends testcase {
    /**
     * There is no validation for boolean.
     * @return void
     */
    public function test_validate_value(): void {
        $field = new field_boolean('bool');

        self::assertNull($field->validate(1));
        self::assertNull($field->validate(0));
        self::assertNull($field->validate(true));
        self::assertNull($field->validate(false));

        self::assertNull($field->validate('on'));
        self::assertNull($field->validate('off'));
        self::assertNull($field->validate('yes'));
        self::assertNull($field->validate('no'));
        self::assertNull($field->validate('true'));
        self::assertNull($field->validate('false'));

        self::assertEquals(
            "The boolean like string 'c' is invalid for field 'bool'",
            $field->validate('c')
        );

        self::assertEquals(
            "The boolean like numeric '15' is invalid for field 'bool'",
            $field->validate(15)
        );
    }

    /**
     * @return void
     */
    public function test_clean_value(): void {
        $field = new field_boolean('bool');

        self::assertFalse($field->clean('c'));
        self::assertFalse($field->clean(false));
        self::assertFalse($field->clean('false'));
        self::assertFalse($field->clean('off'));
        self::assertFalse($field->clean('no'));
        self::assertFalse($field->clean(0));
        self::assertFalse($field->clean(15));

        self::assertTrue($field->clean(true));
        self::assertTrue($field->clean(1));
        self::assertTrue($field->clean('yes'));
        self::assertTrue($field->clean('on'));
        self::assertTrue($field->clean('true'));
    }
}