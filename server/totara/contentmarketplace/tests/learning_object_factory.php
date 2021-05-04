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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_contentmarketplace
 */

use core_phpunit\testcase;
use totara_contentmarketplace\learning_object_factory;

/**
 * Test learning_object_metadata_task factory class
 *
 * @group totara_contentmarketplace
 */
class totara_contentmarketplace_learning_object_factory_testcase extends testcase {
    /**
     * @return void
     */
    public function test_learning_object_metadata_task(): void {
        $instance = learning_object_factory::create();
        self::assertInstanceOf(learning_object_factory::class, $instance);

        $method = new ReflectionMethod(learning_object_factory::class, 'load_sync_action_classes');
        $method->setAccessible(true);
        $method->invoke($instance);

        $classes = $instance->get_sync_action_classes();
        self::assertEmpty($classes);

        $plugins = core_plugin_manager::instance()->get_plugins_of_type('contentmarketplace');
        foreach ($plugins as $plugin) {
            $plugin->enable();
        }

        $method = new ReflectionMethod(learning_object_factory::class, 'load_sync_action_classes');
        $method->setAccessible(true);
        $method->invoke($instance);

        $classes = $instance->get_sync_action_classes();
        self::assertGreaterThanOrEqual(1, $classes);

    }
}