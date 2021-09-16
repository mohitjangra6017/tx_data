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
use core_phpunit\testcase;
use totara_webapi\phpunit\webapi_phpunit_helper;
use mod_contentmarketplace\model\content_marketplace;
use mod_contentmarketplace\webapi\resolver\type\content_marketplace as type_content_marketplace;

/**
 * @group totara_contentmarketplace
 */
class mod_contentmarketplace_webapi_type_content_marketplace_testcase extends testcase {
    use webapi_phpunit_helper;

    /**
     * @return void
     */
    public function test_resolve_field_name(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $cm = $generator->create_module('contentmarketplace', ['course' => $course->id]);
        $content_marketplace = content_marketplace::from_course_module_id($cm->cmid);

        self::assertEquals(
            $cm->name,
            $this->resolve_graphql_type(
                $this->get_graphql_name(type_content_marketplace::class),
                'name',
                $content_marketplace,
                [],
                $content_marketplace->get_context()
            )
        );
    }

    /**
     * @return void
     */
    public function test_resolve_field_course(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $cm = $generator->create_module('contentmarketplace', ['course' => $course->id]);
        $content_marketplace = content_marketplace::from_course_module_id($cm->cmid);

        $course_resolved = $this->resolve_graphql_type(
            $this->get_graphql_name(type_content_marketplace::class),
            'course',
            $content_marketplace,
            [],
            $content_marketplace->get_context()
        );

        self::assertEquals(
            $course->id,
            $course_resolved->id
        );
        self::assertEquals($course->fullname, $course_resolved->fullname);
    }

    /**
     * @return void
     */
    public function test_resolve_field_id(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $cm = $generator->create_module('contentmarketplace', ['course' => $course->id]);
        $content_marketplace = content_marketplace::from_course_module_id($cm->cmid);

        self::assertEquals(
            $cm->id,
            $this->resolve_graphql_type(
                $this->get_graphql_name(type_content_marketplace::class),
                'id',
                $content_marketplace,
                [],
                $content_marketplace->get_context(),
            )
        );
    }

    /**
     * @return void
     */
    public function test_resolve_field_cm_id(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $cm = $generator->create_module('contentmarketplace', ['course' => $course->id]);
        $content_marketplace = content_marketplace::from_course_module_id($cm->cmid);

        self::assertEquals(
            $cm->cmid,
            $this->resolve_graphql_type(
                $this->get_graphql_name(type_content_marketplace::class),
                'cm_id',
                $content_marketplace,
                [],
                $content_marketplace->get_context()
            )
        );
    }

    /**
     * @return void
     */
    public function test_resolve_field_completion_condition(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $cm = $generator->create_module('contentmarketplace', ['course' => $course->id]);
        $content_marketplace = content_marketplace::from_course_module_id($cm->cmid);

        self::assertEquals(
            $cm->completion_condition,
            $this->resolve_graphql_type(
                $this->get_graphql_name(type_content_marketplace::class),
                'completion_condition',
                $content_marketplace,
                [],
                $content_marketplace->get_context()
            )
        );
    }

    /**
     * @return void
     */
    public function test_resolve_field_completion_status(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course(["enablecompletion" => 1]);

        $user = $generator->create_user();
        $generator-> enrol_user($user->id, $course->id);

        $instance = $generator->create_module(
            "contentmarketplace",
            [
                "course" => $course->id,
                "completion" => COMPLETION_TRACKING_MANUAL,
            ]
        );
        $content_marketplace = content_marketplace::from_course_module_id($instance->cmid);

        self::setUser($user);
        self::assertNull(
            $this->resolve_graphql_type(
                $this->get_graphql_name(type_content_marketplace::class),
                "completion_status",
                $content_marketplace,
                [],
                $content_marketplace->get_context()
            )
        );

        $db = builder::get_db();
        $completion_record = new stdClass();
        $completion_record->coursemoduleid = $instance->cmid;
        $completion_record->userid = $user->id;
        $completion_record->completionstate = COMPLETION_INCOMPLETE;
        $completion_record->timemodified = time();

        $completion_record->id = $db->insert_record("course_modules_completion", $completion_record);

        self::assertFalse(
            $this->resolve_graphql_type(
                $this->get_graphql_name(type_content_marketplace::class),
                "completion_status",
                $content_marketplace,
                [],
                $content_marketplace->get_context()
            )
        );

        $completion_record->completionstate = COMPLETION_COMPLETE;
        $db->update_record("course_modules_completion", $completion_record);

        self::assertTrue(
            $this->resolve_graphql_type(
                $this->get_graphql_name(type_content_marketplace::class),
                "completion_status",
                $content_marketplace,
                [],
                $content_marketplace->get_context()
            )
        );
    }

    /**
     * @return void
     */
    public function test_resolve_field_self_completion(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $user = $generator->create_user();
        $generator-> enrol_user($user->id, $course->id);

        $instance = $generator->create_module("contentmarketplace", ["course" => $course->id]);
        $content_marketplace = content_marketplace::from_course_module_id($instance->cmid);

        self::assertFalse(
            $this->resolve_graphql_type(
                $this->get_graphql_name(type_content_marketplace::class),
                "self_completion",
                $content_marketplace,
                [],
                $content_marketplace->get_context()
            )
        );
    }

    /**
     * @return void
     */
    public function test_resolve_field_completion_enabled(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course(["enablecompletion" => true]);

        $user = $generator->create_user();
        $generator-> enrol_user($user->id, $course->id);

        $instance = $generator->create_module(
            "contentmarketplace",
            [
                "course" => $course->id,
                "completion" => COMPLETION_TRACKING_MANUAL,
                ""
            ]
        );

        $content_marketplace = content_marketplace::from_course_module_id($instance->cmid);
        self::assertTrue($content_marketplace->completion_enabled);

        self::assertTrue(
            $this->resolve_graphql_type(
                $this->get_graphql_name(type_content_marketplace::class),
                "completion_enabled",
                $content_marketplace,
                [],
                $content_marketplace->get_context()
            )
        );
    }
}