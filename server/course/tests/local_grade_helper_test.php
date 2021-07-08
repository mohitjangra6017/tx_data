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
 * @package core_course
 */

use core_course\local\grade_helper;
use core_phpunit\testcase;

class core_course_local_grade_helper_testcase extends testcase {
    /**
     * @return void
     */
    public function test_does_course_need_regrade(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        self::assertFalse(grade_helper::does_course_need_regrade($course->id));
        $generator->create_module('assign', ['course' => $course->id]);

        self::assertTrue(grade_helper::does_course_need_regrade($course->id));
    }
}