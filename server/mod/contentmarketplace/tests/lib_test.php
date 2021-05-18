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
 * @package mod_contentmarketplace
 */

use container_course\course;
use core\orm\query\builder;
use core_phpunit\testcase;
use mod_contentmarketplace\entity\content_marketplace as entity;
use mod_contentmarketplace\exception\non_exist_learning_object;
use totara_contentmarketplace\testing\generator as totara_content_marketplace_generator;

class mod_contentmarketplace_lib_testcase extends testcase {
    /**
     * @return void
     */
    protected function setUp(): void {
        global $CFG;
        require_once("{$CFG->dirroot}/mod/contentmarketplace/lib.php");
    }

    /**
     * @return void
     */
    public function test_add_instance_via_course(): void {
        global $CFG;
        $generator = self::getDataGenerator();

        $user = $generator->create_user();
        $course_record = $generator->create_course();

        // Enrol user to the course as editing teacher.
        $course = course::from_record($course_record);
        $generator->enrol_user(
            $user->id,
            $course->id,
            $CFG->creatornewroleid
        );

        $marketplace_generator = totara_content_marketplace_generator::instance();

        $learning_object = $marketplace_generator->create_learning_object('contentmarketplace_linkedin');

        $module_info = new stdClass();
        $module_info->course = $course->id;
        $module_info->modulename = 'contentmarketplace';
        $module_info->section = 0;
        $module_info->learning_object_id = $learning_object->get_id();
        $module_info->learning_object_marketplace_component = $learning_object::get_marketplace_component();
        $module_info->visible = 1;

        self::setUser($user);

        $course_module = $course->add_module($module_info);
        $db = builder::get_db();

        self::assertTrue($db->record_exists('course_modules', ['id' => $course_module->get_id()]));
        self::assertTrue($db->record_exists(entity::TABLE, ['id' => $course_module->get_instance()]));

        self::assertTrue(
            $db->record_exists(
                entity::TABLE,
                [
                    'id' => $course_module->get_instance(),
                    'name' => $learning_object->get_name()
                ]
            )
        );
    }

    /**
     * @return void
     */
    public function test_add_instance_with_non_existing_learning_object_via_course(): void {
        global $CFG;

        $generator = self::getDataGenerator();

        $user = $generator->create_user();
        $course_record = $generator->create_course();

        $course = course::from_record($course_record);
        $generator->enrol_user(
            $user->id,
            $course->id,
            $CFG->creatornewroleid
        );

        $module_info = new stdClass();
        $module_info->course = $course->id;
        $module_info->modulename = 'contentmarketplace';
        $module_info->section = 0;
        $module_info->learning_object_id = 42;
        $module_info->learning_object_marketplace_component = 'contentmarketplace_linkedin';
        $module_info->visible = 1;

        // Now add the module.
        self::setUser($user);

        try {
            $course->add_module($module_info);
            self::fail("Expect the adding course module process would yield error");
        } catch (coding_exception $e) {
            self::assertStringContainsString(
                "Incorrect function 'contentmarketplace_add_instance'",
                $e->getMessage()
            );
        }
    }

    /**
     * @return void
     */
    public function test_add_instance_directly(): void {
        $generator = self::getDataGenerator();
        $course_record = $generator->create_course();

        $marketplace_generator = totara_content_marketplace_generator::instance();
        $learning_object = $marketplace_generator->create_learning_object('contentmarketplace_linkedin');

        $module_info = new stdClass();
        $module_info->course = $course_record->id;
        $module_info->modulename = 'contentmarketplace';
        $module_info->section = 0;
        $module_info->learning_object_id = $learning_object->get_id();
        $module_info->learning_object_marketplace_component = $learning_object::get_marketplace_component();

        $id = contentmarketplace_add_instance($module_info);
        $db = builder::get_db();

        self::assertTrue($db->record_exists(entity::TABLE, ['id' => $id]));
        self::assertTrue(
            $db->record_exists(
                entity::TABLE,
                [
                    'id' => $id,
                    'name' => $learning_object->get_name()
                ]
            )
        );
    }

    /**
     * @return void
     */
    public function test_add_instance_with_non_existing_learning_object_directly(): void {
        $generator = self::getDataGenerator();
        $course_record = $generator->create_course();

        $module_info = new stdClass();
        $module_info->course = $course_record->id;
        $module_info->modulename = 'contentmarketplace';
        $module_info->section = 0;
        $module_info->learning_object_id = 42;
        $module_info->learning_object_marketplace_component = 'contentmarketplace_linkedin';

        try {
            contentmarketplace_add_instance($module_info);
            self::fail("Expect the add instance would yield errors");
        } catch (non_exist_learning_object $e) {
            self::assertEquals(
                get_string('error:cannot_find_learning_object', 'mod_contentmarketplace', 'contentmarketplace_linkedin'),
                $e->getMessage()
            );
        }
    }
}