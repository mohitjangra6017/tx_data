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
 * @package mod_contentmarketplace
 */

use core_phpunit\testcase;
use totara_webapi\phpunit\webapi_phpunit_helper;
use mod_contentmarketplace\webapi\resolver\query\content_marketplace;
use mod_contentmarketplace\model\content_marketplace as model;
use container_course\course;

class mod_contemplate_webapi_resolver_query_content_marketplace_testcase extends testcase {
    use webapi_phpunit_helper;

    /**
     * @return void
     */
    public function test_content_marketplace_query_with_admin(): void {
        self::setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $cm = $generator->create_module('contentmarketplace', ['course' => $course->id]);
        
        $result = $this->resolve_graphql_query(
            $this->get_graphql_name(content_marketplace::class),
            ['cm_id' => $cm->cmid]
        );

        self::assertInstanceOf(model::class, $result);
        self::assertEquals($result->course, $cm->course);
        self::assertEquals($result->id, $cm->id);
        self::assertEquals($result->name, $cm->name);

        self::assertEquals(
            $result->name,
            $this->resolve_graphql_type(
                'mod_contentmarketplace_content_marketplace',
                'name',
                $result
            )
        );

        self::assertEquals(
            $result->id,
            $this->resolve_graphql_type(
                'mod_contentmarketplace_content_marketplace',
                'id',
                $result
            )
        );

        self::assertEquals(
            $result->course,
            $this->resolve_graphql_type(
                'mod_contentmarketplace_content_marketplace',
                'course',
                $result
            )
        );

        self::assertEquals(
            $result->get_cm_id(),
            $this->resolve_graphql_type(
                'mod_contentmarketplace_content_marketplace',
                'cm_id',
                $result
            )
        );

        self::assertEquals(
            $result->get_learning_object(),
            $this->resolve_graphql_type(
                'mod_contentmarketplace_content_marketplace',
                'learning_object',
                $result
            )
        );

        $learning_object = $result->get_learning_object();
        self::assertEquals(
            $learning_object->get_id(),
            $this->resolve_graphql_type(
                'mod_contentmarketplace_learning_obejct',
                'id',
                $learning_object
            )
        );

        self::assertEquals(
            $learning_object->get_name(),
            $this->resolve_graphql_type(
                'mod_contentmarketplace_learning_obejct',
                'name',
                $learning_object
            )
        );

        self::assertEquals(
            $learning_object->get_language(),
            $this->resolve_graphql_type(
                'mod_contentmarketplace_learning_obejct',
                'language',
                $learning_object
            )
        );

        self::assertEquals(
            $learning_object->get_image_url(),
            $this->resolve_graphql_type(
                'mod_contentmarketplace_learning_obejct',
                'image_url',
                $learning_object
            )
        );
    }

    /**
     * @return void
     */
    public function test_content_marketplace_query_with_logged_user(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $user = $generator->create_user();

        self::setUser($user);

        $cm = $generator->create_module('contentmarketplace', ['course' => $course->id]);

        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage('Course or activity not accessible. (Not enrolled)');
        $this->resolve_graphql_query(
            $this->get_graphql_name(content_marketplace::class),
            ['cm_id' => $cm->cmid]
        );
    }

    /**
     * @return void
     */
    public function test_content_marketplace_query_with_guest(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        self::setGuestUser();
        $cm = $generator->create_module('contentmarketplace', ['course' => $course->id]);

        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage('Course or activity not accessible. (Not enrolled)');
        $this->resolve_graphql_query(
            $this->get_graphql_name(content_marketplace::class),
            ['cm_id' => $cm->cmid]
        );
    }

    /**
     * @return void
     */
    public function test_content_marketplace_query_with_enrol_user(): void {
        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $user = $generator->create_user();

        self::setUser($user);

        $generator->enrol_user($user->id, $course->id);
        $cm = $generator->create_module('contentmarketplace', ['course' => $course->id]);

        $result = $this->resolve_graphql_query(
            $this->get_graphql_name(content_marketplace::class),
            ['cm_id' => $cm->cmid]
        );

        self::assertNotNull($result);
        self::assertInstanceOf(model::class, $result);
    }

    /**
     * @return void
     */
    public function test_content_marketplace_query_with_guest_enrol(): void {
        global $DB, $CFG;
        require_once("{$CFG->dirroot}/lib/enrollib.php");

        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $cm = $generator->create_module('contentmarketplace', ['course' => $course->id]);

        self::setGuestUser();

        $enrol = $DB->get_record(
            'enrol',
            ['enrol' => 'guest', 'courseid' => $course->id],
            '*',
            MUST_EXIST
        );

        $plugin = enrol_get_plugin('guest');

        $new_enrol = new stdClass();
        $new_enrol->status = ENROL_INSTANCE_ENABLED;
        $plugin->update_instance($enrol, $new_enrol);

        $result = $this->resolve_graphql_query(
            $this->get_graphql_name(content_marketplace::class),
            ['cm_id' => $cm->cmid]
        );

        self::assertNotNull($result);
        self::assertInstanceOf(model::class, $result);
    }
}