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

class mod_contentmarketplace_webapi_resolver_mutation_set_self_completion_testcase extends testcase {
    use webapi_phpunit_helper;

    /**
     * @var string
     */
    private const TOGGLE_MUTATION = 'mod_contentmarketplace_set_self_completion';

    /**
     * @var stdClass
     */
    protected $course;

    /**
     * @var stdClass
     */
    protected $cm;

    /**
     * @var generator
     */
    protected $generator;

    /**
     * @inheritDoc
     */
    protected function setUp(): void {
        $this->generator = self::getDataGenerator();
        $this->course = $this->generator->create_course(['enablecompletion' => 1]);
   
        $this->cm = $this->generator->create_module(
            'contentmarketplace',
            [
                'course' => $this->course->id,
                'completion' => COMPLETION_TRACKING_MANUAL
            ]
        );
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void {
        $this->course = null;
        $this->generator = null;
        $this->cm = null;
    }

    /**
     * @return void
     */
    public function test_toggle_self_completion_with_enrol_user(): void {
        $user = $this->generator->create_user();
        self::setUser($user);
        $this->generator->enrol_user($user->id, $this->course->id);

        $record = $this->get_course_module();
        self::assertEquals(COMPLETION_TRACKING_MANUAL, $record->completion);

        $result = $this->resolve_graphql_mutation(
            self::TOGGLE_MUTATION,
            [
                'cm_id' => $this->cm->cmid,
                'status' => COMPLETION_COMPLETE
            ]
        );

        self::assertNotEmpty($result);
        self::assertIsBool($result);
        self::assertEquals(COMPLETION_COMPLETE, $result);
    }

    /**
     * @return void
     */
    public function test_toggle_self_completion_without_enrol_user(): void {
        $user = $this->generator->create_user();
        self::setUser($user);

        $this->expectException(require_login_exception::class);
        $this->expectExceptionMessage('Course or activity not accessible. (Not enrolled)');
        $this->resolve_graphql_mutation(
            self::TOGGLE_MUTATION,
            [
                'cm_id' => $this->cm->cmid,
                'status' => COMPLETION_COMPLETE
            ]
        );
    }

    /**
     * @return void
     */
    public function test_toggle_self_completion_with_admin(): void {
        self::setAdminUser();

        // Admin should have accessibility.
        $result = $this->resolve_graphql_mutation(
            self::TOGGLE_MUTATION,
            [
                'cm_id' => $this->cm->cmid,
                'status' => COMPLETION_COMPLETE
            ]
        );

        self::assertNotEmpty($result);
        self::assertIsBool($result);
        self::assertEquals(COMPLETION_COMPLETE, $result);
    }

    /**
     * @return void
     */
    public function test_prepared_date(): void {
        global $DB;
        
        self::assertTrue(
            $DB->record_exists(
                'course_modules',
                ['course' => $this->course->id, 'instance' => $this->cm->id]
            )
        );
    }

    /**
     * @return object
     */
    private function get_course_module(): object {
        global $DB;
        return $DB->get_record(
            'course_modules',
            ['course' => $this->course->id, 'instance' => $this->cm->id]
        );
    }
}