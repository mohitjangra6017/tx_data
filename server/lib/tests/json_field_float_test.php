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

use core\json\schema\field\field_float;
use core_phpunit\testcase;

class core_json_field_float_testcase extends testcase {
    /**
     * @return void
     */
    public function test_validate_field(): void {
        $field = new field_float('float');

        self::assertNull($field->validate(1));
        self::assertNull($field->validate(1.0));
        self::assertNull($field->validate('1'));
        self::assertNull($field->validate('1.1'));

        self::assertEquals(
            "The value of field 'float' is not a number",
            $field->validate('c')
        );

        self::assertEquals(
            "The value of field 'float' is not a number",
            $field->validate(false)
        );
    }

    /**
     * @return void
     */
    public function test_clean_field(): void {
        $field = new field_float('float');

        self::assertEquals(0, $field->clean(0));
        self::assertEquals(1.0, $field->clean(1.0));
        self::assertEquals(0, $field->clean('c'));
        self::assertEquals(0, $field->clean(false));
        self::assertEquals(1, $field->clean(true));
    }
}