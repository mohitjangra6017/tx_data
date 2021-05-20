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

use core\orm\query\builder;
use core\orm\query\exceptions\record_not_found_exception;
use core_phpunit\testcase;
use mod_contentmarketplace\entity\content_marketplace as entity;
use mod_contentmarketplace\model\content_marketplace;

class mod_contentmarketplace_content_marketplace_model_testcase extends testcase {
    /**
     * @return void
     */
    public function test_instance_from_cm_id(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $cm = $generator->create_module(
            'contentmarketplace',
            [
                'course' => $course->id,
                'learning_object_marketplace_component' => 'contentmarketplace_linkedin',
            ]
        );

        $content_marketplace = content_marketplace::from_course_module_id($cm->cmid);
        $learning_object = $content_marketplace->get_learning_object();

        self::assertEquals($cm->learning_object_id, $learning_object->get_id());
        self::assertEquals($cm->name, $content_marketplace->name);
        self::assertEquals($cm->learning_object_marketplace_component, $learning_object::get_marketplace_component());
    }

    /**
     * @return void
     */
    public function test_instance_from_cm_id_that_is_not_content_marketplace(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $seminar = $generator->create_module(
            'facetoface',
            ['course' => $course->id]
        );

        try {
            content_marketplace::from_course_module_id($seminar->cmid);
            self::fail("Expect the fetch record to yield error");
        } catch (record_not_found_exception $e) {
            // Different db vendor give different error message, which
            // this message is the closet.
            self::assertStringContainsString(
                'Can not find data record in database',
                $e->getMessage()
            );
        }
    }

    /**
     * This is less likely a case, but who knows.
     * @return void
     */
    public function test_get_learning_object_that_does_not_exist(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $cm = $generator->create_module(
            'contentmarketplace',
            [
                'course' => $course->id,
                'learning_object_marketplace_component' => 'contentmarketplace_linkedin',
            ]
        );

        $model = content_marketplace::from_course_module_id($cm->cmid);

        // Delete the learning object record.
        $db = builder::get_db();
        $db->delete_records('marketplace_linkedin_learning_object', ['id' => $model->learning_object_id]);

        try {
            $model->get_learning_object();
            self::fail("Expect the fetch process to yield error");
        } catch (record_not_found_exception $e) {
            // Different db vendor give different error message, which
            // this message is the closet.
            self::assertStringContainsString(
                'Can not find data record in database',
                $e->getMessage()
            );
        }
    }

    /**
     * @return void
     */
    public function test_delete(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $cm = $generator->create_module(
            'contentmarketplace',
            [
                'course' => $course->id,
                'learning_object_marketplace_component' => 'contentmarketplace_linkedin',
            ]
        );

        $model = content_marketplace::from_course_module_id($cm->cmid);
        $learning_object_id = $model->learning_object_id;

        $result = $model->delete();

        self::assertTrue($result);
        self::assertTrue($model->is_deleted());

        $db = builder::get_db();
        self::assertFalse($db->record_exists(entity::TABLE, ['id' => $cm->id]));

        // Learning object should not be deleted.
        self::assertTrue($db->record_exists('marketplace_linkedin_learning_object', ['id' => $learning_object_id]));

        // Delete the instance again which should yield debugging messages.
        $result = $model->delete();
        self::assertFalse($result);
        self::assertDebuggingCalled("The record had already been deleted");
    }
}