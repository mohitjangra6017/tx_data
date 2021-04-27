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

use core\json\schema\field\field_text;

class core_json_field_text_testcase extends testcase {
    /**
     * @return void
     */
    public function test_validate_field(): void {
        $field = new field_text('field_1');
        self::assertEquals(
            "The value of field 'field_1' is not a string",
            $field->validate(true)
        );

        self::assertEquals(
            "The value of field 'field_1' is not a string",
            $field->validate(15)
        );

        self::assertEquals(
            "The value of field 'field_1' is not a string",
            $field->validate(false)
        );

        self::assertEquals(
            "The value of field 'field_1' is not a string",
            $field->validate(15.5)
        );

        // XSS is still a string.
        self::assertEquals(
            "The value of field 'field_1' is invalid",
            $field->validate('<script>alert("Hello world")</script>')
        );

        self::assertNull($field->validate('hello world'));
        self::assertNull($field->validate('hello+world'));
        self::assertNull($field->validate(null));
        self::assertNull($field->validate('textabcde'));
        self::assertNull($field->validate('heloo world 123 4 5 8 lpocaslpcsa'));
        self::assertNull($field->validate(json_encode(['c' => 'd'])));
    }

    /**
     * @return void
     */
    public function test_clean_value(): void {
        $field = new field_text('field_1');

        self::assertNull($field->clean(null));

        self::assertEquals("hello world", $field->clean('hello world'));
        self::assertEquals('hello+world', $field->clean('hello+world'));
        self::assertEquals('textabcde', $field->clean('textabcde'));
        self::assertEquals('heloo world 123 4 5 8 lpocaslpcsa', $field->clean('heloo world 123 4 5 8 lpocaslpcsa'));
        self::assertEquals(json_encode(['c' => 'd']), $field->clean(json_encode(['c' => 'd'])));
        self::assertEquals(
            'alert("Hello world")',
            $field->clean('<script>alert("Hello world")</script>')
        );
    }
}