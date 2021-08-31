<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
 * @author David Curry <david.curry@totaralearning.com>
 * @package totara_mobile
 */

defined('MOODLE_INTERNAL') || die();

use totara_webapi\phpunit\webapi_phpunit_helper;

/**
 * Tests the totara job assignment query resolver
 */
class totara_mobile_webapi_resolver_query_course_testcase extends advanced_testcase {

    use webapi_phpunit_helper;

    /**
     *
     * Create some programs and assign some users for testing.
     * @return []
     */
    private function create_faux_data(): array {
        $users = [];
        $users[] = $this->getDataGenerator()->create_user();
        $users[] = $this->getDataGenerator()->create_user();
        $users[] = $this->getDataGenerator()->create_user();

        $courses = [];
        $courses[] = $this->getDataGenerator()->create_course([
            'fullname' => 'course1',
            'shortname' => 'c1',
            'summary' => 'The first course',
        ]);
        $this->getDataGenerator()->enrol_user($users[0]->id, $courses[0]->id, 'student', 'manual');
        $this->getDataGenerator()->enrol_user($users[1]->id, $courses[0]->id, 'student', 'manual');

        $courses[] = $this->getDataGenerator()->create_course([
            'fullname' => 'course2',
            'shortname' => 'c2',
            'summary' => 'The second course'
        ]);
        $this->getDataGenerator()->enrol_user($users[0]->id, $courses[1]->id, 'student', 'manual');
        $this->getDataGenerator()->enrol_user($users[1]->id, $courses[1]->id, 'student', 'manual');

        $courses[] = $this->getDataGenerator()->create_course([
            'fullname' => 'course3',
            'shortname' => 'c3',
            'summary' => 'The third course',
            'visible' => 0
        ]);

        return [$users, $courses];
    }
    /**
     * Test the results of the query when the current user is not logged in.
     */
    public function test_resolve_no_login(): void {
        list($users, $courses) = $this->create_faux_data();

        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage('Course or activity not accessible. (You are not logged in)');

        $this->resolve_graphql_query('totara_mobile_course', ['courseid' => $courses[0]->id]);
    }

    /**
     * Test the results of the query when the current user is logged in as the guest user.
     */
    public function test_resolve_guest_user(): void {
        list($users, $courses) = $this->create_faux_data();
        $this->setGuestUser();

        $this->expectException(require_login_exception::class);
        $this->expectExceptionMessage('Course or activity not accessible. (Not enrolled)');

        // By default guests cannot view courses, only when the guest enrol plugin is enabled
        $this->resolve_graphql_query('core_course', ['courseid' => $courses[0]->id]);
    }

    /**
     * Test that users can access the course via guest access (no pw) when not enrolled.
     */
    public function test_resolve_guest_access_allowed(): void {
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user->id);

        $plugin = enrol_get_plugin('guest');

        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'guest course',
            'shortname' => 'guest',
        ]);

        $plugin->add_instance(
            $course,
            [
                'status' => ENROL_INSTANCE_ENABLED,
                'name' => 'Guest Access',
                'customint6' => 1,
            ]
        );

        try {
            // This should fail with the incorrect guest password.
            $result = $this->resolve_graphql_query(
                'totara_mobile_course',
                [
                    'courseid' => $course->id,
                ]
            );
            $this->assertEquals($course->fullname, $result['course']->fullname);
        } catch (\require_login_exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * Test that users can't access the course via guest access when missing a required password.
     */
    public function test_resolve_guest_access_password_missing(): void {
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user->id);

        $plugin = enrol_get_plugin('guest');

        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'guest course',
            'shortname' => 'guest',
        ]);

        $plugin->add_instance(
            $course,
            [
                'status' => ENROL_INSTANCE_ENABLED,
                'name' => 'Guest Access',
                'customint6' => 1,
                'password' => 'abc123'
            ]
        );

        try {
            // This should fail with the incorrect guest password.
            $result = $this->resolve_graphql_query(
                'totara_mobile_course',
                [
                    'courseid' => $course->id,
                ]
            );
            $this->fail('fatal error expected');
        } catch (\require_login_exception $e) {
            $this->assertSame('Course or activity not accessible. (Not enrolled)', $e->getMessage());
        }
    }

    /**
     * Test that users can't access the course via guest access when handing through the incorrect password.
     */
    public function test_resolve_guest_access_password_fail(): void {
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user->id);

        $plugin = enrol_get_plugin('guest');

        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'guest course',
            'shortname' => 'guest',
        ]);

        $plugin->add_instance(
            $course,
            [
                'status' => ENROL_INSTANCE_ENABLED,
                'name' => 'Guest Access',
                'customint6' => 1,
                'password' => 'abc123'
            ]
        );

        try {
            // This should fail with the incorrect guest password.
            $result = $this->resolve_graphql_query(
                'totara_mobile_course',
                [
                    'courseid' => $course->id,
                    'guestpw' => 'xyz987'
                ]
            );
            $this->fail('fatal error expected');
        } catch (\require_login_exception $e) {
            $this->assertSame('Course or activity not accessible. (Not enrolled)', $e->getMessage());
        }
    }

    /**
     * Test that users can access the course via guest access when handing through the correct password.
     */
    public function test_resolve_guest_access_password_success(): void {
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user->id);

        $plugin = enrol_get_plugin('guest');

        // Test guest access without a password.
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'guest course',
            'shortname' => 'guest',
        ]);

        $plugin->add_instance(
            $course,
            [
                'status' => ENROL_INSTANCE_ENABLED,
                'name' => 'Guest Access',
                'customint6' => 1,
                'password' => 'abc123'
            ]
        );

        try {
            // This should work without any worries.
            $result = $this->resolve_graphql_query(
                'totara_mobile_course',
                [
                    'courseid' => $course->id,
                    'guestpw' => 'abc123'
                ]
            );
            $this->assertEquals($course->fullname, $result['course']->fullname);
        } catch (\require_login_exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * Test that users can't access the course via guest access when the instance is disabled
     */
    public function test_resolve_guest_access_password_disabled(): void {
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user->id);

        $plugin = enrol_get_plugin('guest');

        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'guest course',
            'shortname' => 'guest',
        ]);

        $plugin->add_instance(
            $course,
            [
                'status' => ENROL_INSTANCE_DISABLED,
                'name' => 'Guest Access',
                'customint6' => 1,
                'password' => 'abc123'
            ]
        );

        try {
            // This should fail even with the correct guest password, as the instance is disabled.
            $result = $this->resolve_graphql_query(
                'totara_mobile_course',
                [
                    'courseid' => $course->id,
                    'guestpw' => 'abc123'
                ]
            );
            $this->fail('fatal error expected');
        } catch (\require_login_exception $e) {
            $this->assertSame('Course or activity not accessible. (Not enrolled)', $e->getMessage());
        }
    }

    /**
     * Test the results of the query when the current user is the site administrator.
     */
    public function test_resolve_user(): void {
        list($users, $courses) = $this->create_faux_data();
        $this->setUser($users[0]->id);

        // Admins should be able to see programs, again without completion data.
        $result = $this->resolve_graphql_query('totara_mobile_course', ['courseid' => $courses[0]->id]);
        $this->assertEquals($courses[0]->id, $result['course']->id);

        // They should not be able to see hidden courses.
        $this->expectException(require_login_exception::class);
        $this->expectExceptionMessage('Course or activity not accessible. (Course is hidden)');
        $this->resolve_graphql_query('core_course', ['courseid' => $courses[2]->id]);
    }

    /**
     * Test the results of the query when the current user is the site administrator.
     */
    public function test_resolve_admin_user(): void {
        global $PAGE;

        list($users, $courses) = $this->create_faux_data();
        $this->setAdminUser();

        // Admins should be able to see programs, again without completion data.
        $result = $this->resolve_graphql_query('totara_mobile_course', ['courseid' => $courses[0]->id]);
        $this->assertEquals($courses[0]->id, $result['course']->id);

        // There is an issue with require_login_course being called multiple times from within the same test.
        $PAGE->reset_theme_and_output();

        // They should also be able to see hidden courses.
        $result = $this->resolve_graphql_query('totara_mobile_course', ['courseid' => $courses[2]->id]);
        $this->assertEquals($courses[2]->id, $result['course']->id);
    }

    /**
     * Test the results of the embedded query contain the expected data.
     * this doesn't test all the data in the course object since there are already tests
     * for that, main tests that the expected data is there and the extra data is correct.
     */
    public function test_embedded_query(): void {
        list($users, $courses) = $this->create_faux_data();
        $this->setUser($users[0]);

        try {
            $result = \totara_webapi\graphql::execute_operation(
                \core\webapi\execution_context::create('mobile', 'totara_mobile_course'),
                ["courseid" => $courses[0]->id]
            );
            $data = $result->toArray()['data']['mobile_course'];

            $this->assertNotEmpty($data);

            $course = $data['course'];
            $this->assertIsArray($course);
            $this->assertArrayHasKey('id', $course);
            $this->assertArrayHasKey('fullname', $course);
            $this->assertArrayHasKey('shortname', $course);
            $this->assertArrayHasKey('summary', $course);
            $this->assertArrayHasKey('startdate', $course);
            $this->assertArrayHasKey('enddate', $course);
            $this->assertArrayHasKey('lang', $course);
            $this->assertArrayHasKey('image', $course);
            $this->assertArrayHasKey('format', $course);
            $this->assertArrayHasKey('__typename', $course);

            $this->assertArrayHasKey('sections', $course);
            $this->assertIsArray($course['sections']);
            $section = array_shift($course['sections']);

            $this->assertIsArray($section);
            $this->assertArrayHasKey('id', $section);
            $this->assertArrayHasKey('title', $section);
            $this->assertArrayHasKey('available', $section);
            $this->assertArrayHasKey('availablereason', $section);
            $this->assertArrayHasKey('summary', $section);
            $this->assertArrayHasKey('__typename', $section);

            // Note: it might be nice to test each of these objects contain the
            // expected data, but with the current setup they are empty. And there
            // is already some behat coverage of the area.
            $this->assertArrayHasKey('data', $section); // Aka, modules.
            $this->assertArrayHasKey('criteriaaggregation', $course);
            $this->assertArrayHasKey('criteria', $course);
            $this->assertArrayHasKey('showGrades', $course);
            $this->assertArrayHasKey('completionEnabled', $course);
            $this->assertArrayHasKey('completion', $course);
        } catch (\moodle_exception $ex) {
            $this->fail($ex->getMessage());
        }
    }
}
