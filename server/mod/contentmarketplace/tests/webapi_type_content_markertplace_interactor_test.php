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
use mod_contentmarketplace\model\content_marketplace;
use mod_contentmarketplace\webapi\resolver\type\content_marketplace_interactor as type_content_marketplace_interactor;
use mod_contentmarketplace\interactor\content_marketplace_interactor;

class mod_contentmarketplace_webapi_type_content_markertplace_interactor_testcase extends testcase {
    use webapi_phpunit_helper;

    /**
     * @var content_marketplace_interactor
     */
    protected $interacter;

    /**
     * @var enrol_plugin
     */
    protected $self_plugin;

    /**
     * @var enrol_plugin
     */
    protected $guest_plugin;

    /**
     * @inheritDoc
     */
    protected function setUp(): void {
        global $DB;
        $generator = self::getDataGenerator();
        $course = $generator->create_course(['enablecompletion' => 1]);
        $cm = $generator->create_module(
            'contentmarketplace',
            [
                'course' => $course->id,
                'completion' => COMPLETION_TRACKING_MANUAL
            ]
        );
        $cm = content_marketplace::load_by_id($cm->id);
        $this->interacter = new content_marketplace_interactor(content_marketplace::load_by_id($cm->id));

        // Enabled self enrolment.
        $this->self_plugin = enrol_get_plugin('self');
        $instance = $DB->get_record(
            'enrol',
            [
                'courseid' => $cm->get_course_id(),
                'enrol' => 'self'
            ],
            '*',
            MUST_EXIST
        );
        $this->self_plugin->update_status($instance, ENROL_INSTANCE_ENABLED);

        // Enabled guest access.
        $enrol_instance = $DB->get_record(
            'enrol',
            ['enrol' => 'guest', 'courseid' => $cm->get_course_id()],
            '*',
            MUST_EXIST
        );
        $this->guest_plugin = enrol_get_plugin('guest');
        $this->guest_plugin->update_status($enrol_instance, ENROL_INSTANCE_ENABLED);
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void {
        $this->self_plugin = null;
        $this->guest_plugin = null;
        $this->interacter = null;
    }

    /**
     * @param string $enrol_plugin
     *  @return void
     */
    private function disable_enrol_plugin(string $enrol_plugin = 'guest'): void {
        global $DB;

        // Disabled gusest access.
        $enrol_instance = $DB->get_record(
            'enrol',
            ['enrol' => $enrol_plugin, 'courseid' => $this->interacter->get_course_id()],
            '*',
            MUST_EXIST
        );

        if ($enrol_plugin == 'guest') {
            $this->guest_plugin->update_status($enrol_instance, ENROL_INSTANCE_DISABLED);
            return;
        }

        $this->self_plugin->update_status($enrol_instance, ENROL_INSTANCE_DISABLED);
    }

    /**
     * @return void
     */
    public function test_content_markertplace_interactor_type_is_admin(): void {
        self::setAdminUser();
        self::assertEquals(
            $this->interacter->is_admin(),
            $this->resolve_graphql_type(
                $this->get_graphql_name(type_content_marketplace_interactor::class),
                'is_admin',
                $this->interacter
            )
        );
    }

    /**
     * @return void
     */
    public function test_content_markertplace_interactor_type_can_enrol(): void {
        self::setAdminUser();
        self::assertTrue($this->interacter->can_enrol());
        self::assertEquals(
            $this->interacter->can_enrol(),
            $this->resolve_graphql_type(
                $this->get_graphql_name(type_content_marketplace_interactor::class),
                'can_enrol',
                $this->interacter
            )
        );
    }

    /**
     * @return void
     */
    public function test_content_markertplace_interactor_type_is_site_guest(): void {
        self::setGuestUser();
        self::assertEquals(
            $this->interacter->is_site_guest(),
            $this->resolve_graphql_type(
                $this->get_graphql_name(type_content_marketplace_interactor::class),
                'is_site_guest',
                $this->interacter
            )
        );
    }
}