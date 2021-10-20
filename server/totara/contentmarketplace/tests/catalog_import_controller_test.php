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

use contentmarketplace_linkedin\controllers\catalog_import as lil_catalog_import;
use core_phpunit\testcase;
use totara_contentmarketplace\controllers\catalog_import;
use totara_contentmarketplace\explorer as explorer_model;
use totara_contentmarketplace\plugininfo\contentmarketplace;

/**
 * @group totara_contentmarketplace
 */
class totara_contentmarketplace_catalog_import_controller_testcase extends testcase {
    /**
     * @return void
     */
    public function test_explorer_controller(): void {
        self::setAdminUser();
        $this->enable_plugin('linkedin');

        $_GET['marketplace'] = 'linkedin';

        $controller = new catalog_import();
        $controller->action();

        self::assertEquals('linkedin', $controller->get_marketplace());
        self::assertEquals(context_system::instance(), $controller->get_context());
        self::assertEquals(0, $controller->get_category_id());
        self::assertEquals(explorer_model::MODE_EXPLORE, $controller->get_mode());
    }

    /**
     * @return void
     */
    public function test_get_sub_controller(): void {
        self::setAdminUser();
        $this->enable_plugin('linkedin');

        $_GET['marketplace'] = 'linkedin';
        $controller = new catalog_import();
        $explorer = new ReflectionClass(catalog_import::class);
        $method = $explorer->getMethod('get_current_controller_class');
        $method->setAccessible(true);
        $sub_controller = $method->invoke($controller);

        self::assertEquals($sub_controller, lil_catalog_import::class);
    }

    /**
     * @return void
     */
    public function test_explorer_controller_capability(): void {
        $this->enable_plugin('goone');
        $generator = self::getDataGenerator();
        $user1 = $generator->create_user();

        // Login as user1
        self::setUser($user1);

        $_GET['marketplace'] = 'goone';

        self::expectExceptionMessage('Sorry, but you do not currently have permissions to do that (Add content marketplace)');
        self::expectException(required_capability_exception::class);
        $controller = new catalog_import();
        $controller->action();
    }

    /**
     * @return void
     */
    public function test_explorer_controller_with_lil_disabled(): void {
        self::setAdminUser();
        $_GET['marketplace'] = 'linkedin';

        self::expectException(moodle_exception::class);
        self::expectExceptionMessage('LinkedIn Learning content marketplace disabled');
        $controller = new catalog_import();
        $controller->action();
    }

    /**
     * @return void
     */
    public function test_explorer_controller_with_goone_disabled(): void {
        self::setAdminUser();
        $_GET['marketplace'] = 'goone';

        self::expectException(moodle_exception::class);
        self::expectExceptionMessage('Go1 content marketplace disabled');
        $controller = new catalog_import();
        $controller->action();
    }

    /**
     * @param string $plugin
     * @return void
     */
    private function enable_plugin(string $plugin): void {
        $plugin = contentmarketplace::plugin($plugin);
        if (!$plugin->is_enabled()) {
            $plugin->enable();
        }
    }

}