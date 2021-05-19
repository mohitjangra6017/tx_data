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
 * @package container_course
 */

use container_course\hook\remove_module_hook;
use core_phpunit\testcase;
use container_course\course_helper;

class container_course_course_helper_testcase extends testcase {
    /**
     * @return void
     */
    public function test_get_all_modules_execute_hook(): void {
        $hook_sink = self::redirectHooks();
        self::assertEquals(0, $hook_sink->count());
        self::assertEmpty($hook_sink->get_hooks());

        course_helper::get_all_modules(true);
        self::assertEquals(2, $hook_sink->count());
        $hooks = $hook_sink->get_hooks();

        self::assertCount(2, $hooks);

        // We will not be interesting in the first hook, but the last for this test.
        $second_hook = end($hooks);
        self::assertInstanceOf(remove_module_hook::class, $second_hook);
    }

    /**
     * @return void
     */
    public function test_get_all_modules_skip_execute_hook(): void {
        $hook_sink = self::redirectHooks();
        self::assertEquals(0, $hook_sink->count());
        self::assertEmpty($hook_sink->get_hooks());

        course_helper::get_all_modules(true, false, false);
        self::assertEquals(1, $hook_sink->count());
        $hooks = $hook_sink->get_hooks();

        self::assertCount(1, $hooks);

        // We will not be interesting in the first hook, but the last for this test.
        $hook = end($hooks);
        self::assertNotInstanceOf(remove_module_hook::class, $hook);
    }
}