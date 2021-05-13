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
 * @package contentmarketplace_linkedin
 */

use core_phpunit\testcase;
use contentmarketplace_linkedin\workflow\core_course\coursecreate\contentmarketplace;
use totara_contentmarketplace\plugininfo\contentmarketplace as contentmarketplace_plugin;

class lil_workflow_test extends testcase {
    /**
     * @return void
     */
    public function test_coursecreate_workflow_basis(): void {
        $obj = contentmarketplace::instance();
        self::assertInstanceOf('contentmarketplace_linkedin\workflow\core_course\coursecreate\contentmarketplace', $obj);
        self::assertTrue($obj->is_enabled());
        self::assertEquals(get_string('add_linkedin_courses', 'contentmarketplace_linkedin'), $obj->get_name());
        self::assertEquals(get_string('add_linkedin_courses_description', 'contentmarketplace_linkedin'), $obj->get_description());
    }

    /**
     * @return void
     */
    public function test_coursecreate_workflow_access(): void {
        self::setAdminUser();
        $obj = contentmarketplace::instance();
        self::assertFalse($obj->can_access());

        $plugin = contentmarketplace_plugin::plugin('linkedin');
        $plugin->enable();
        $obj->set_params(['category' => 1]);
        self::assertTrue($obj->can_access());
    }
}