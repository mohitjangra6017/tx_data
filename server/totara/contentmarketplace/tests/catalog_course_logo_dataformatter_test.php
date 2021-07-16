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

use core_course\totara_catalog\course\dataholder_factory\course_logo;
use core_phpunit\testcase;
use totara_contentmarketplace\testing\generator;
use totara_contentmarketplace\course\course_builder;
use totara_contentmarketplace\testing\mock\create_course_interactor;
use totara_contentmarketplace\testing\helper;
use totara_catalog\dataformatter\formatter;
use totara_contentmarketplace\totara_catalog\course_logo_dataholder_factory;


class totara_contentmarketplace_catalog_course_logo_dataformatter_testcase extends testcase {

    /**
     * @return void
     */
    public function test_course_logo_dataformatter(): void {
        self::setAdminUser();
        $admin = get_admin();

        $marketplace_generator = generator::instance();
        $learning_object = $marketplace_generator->create_learning_object('contentmarketplace_linkedin');

        $course_builder = new course_builder(
            $learning_object,
            helper::get_default_course_category_id(),
            new create_course_interactor($admin->id)
        );
        $result = $course_builder->create_course();

        $data_holders = course_logo::get_dataholders();
        $this->assertCount(1, $data_holders);

        $data_holder = current($data_holders);
        self::assertEquals(course_logo_dataholder_factory::DATAHOLDER_KEY, $data_holder->key);
        self::assertEquals(course_logo_dataholder_factory::DATAHOLDER_NAME, $data_holder->name);

        $context = context_course::instance($result->get_course_id());
        $result = $data_holder->get_formatted_value(
            formatter::TYPE_PLACEHOLDER_IMAGE,
            [
                'marketplace_component' => $learning_object::get_marketplace_component(),
                'learning_object_id' => $learning_object->get_id(),
            ],
            $context
        );
        self::assertNotEmpty($result);
        self::assertNotEmpty($result->url);
        self::assertEquals(get_string('logo_alt',  $learning_object::get_marketplace_component()), $result->alt);
    }
}