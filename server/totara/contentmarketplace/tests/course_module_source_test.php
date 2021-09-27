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

use container_course\module\course_module;
use core_phpunit\testcase;
use totara_contentmarketplace\course\course_builder;
use totara_contentmarketplace\entity\course_module_source;
use totara_contentmarketplace\testing\generator;
use totara_contentmarketplace\testing\helper;
use totara_contentmarketplace\testing\mock\create_course_interactor;

/**
 * @group totara_contentmarketplace
 */
class totara_contentmarketplace_course_module_source_testcase extends testcase {
    /**
     * @return void
     */
    public function test_course_module_source_created(): void {
        global $DB, $USER;

        self::setAdminUser();
        self::assertEquals(0, course_module_source::repository()->count());

        $marketplace_generator = generator::instance();
        $learning_object = $marketplace_generator->create_learning_object('contentmarketplace_linkedin');

        $course_builder = new course_builder(
            $learning_object,
            helper::get_default_course_category_id(),
            new create_course_interactor($USER->id)
        );

        $result = $course_builder->create_course();
        self::assertTrue($result->is_successful());

        $entities = course_module_source::repository()->get();
        self::assertCount(1, $entities);

        /** @var course_module_source $entity */
        $entity = $entities->first();
        self::assertEquals($learning_object->get_id(), $entity->learning_object_id);
        self::assertEquals($learning_object::get_marketplace_component(), $entity->marketplace_component);
        self::assertEquals($result->get_course_id(), $entity->course_id);

        // Delete the course module
        $course_module = course_module::from_id($entity->cm_id);
        $course_module->delete();

        self::assertEquals(0, course_module_source::repository()->count());
    }
}