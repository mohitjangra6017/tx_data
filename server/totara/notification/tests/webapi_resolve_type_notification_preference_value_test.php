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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */

use totara_notification\model\notification_preference_value as model;
use totara_webapi\phpunit\webapi_phpunit_helper;
use totara_notification_mock_notifiable_event as mock_event;
use totara_notification\webapi\resolver\type\notification_preference_value;

class totara_notification_webapi_resolve_type_notification_preference_value_testcase extends advanced_testcase {
    use webapi_phpunit_helper;

    /**
     * @var model|null
     */
    private $preference_value;

    /**
     * @return void
     */
    protected function setUp(): void {
        /** @var totara_notification_generator $notification_generator */
        $notification_generator = self::getDataGenerator()->get_plugin_generator('totara_notification');
        $notification_generator->include_mock_notifiable_event();

        $custom_notification = $notification_generator->create_notification_preference(
            mock_event::class,
            context_system::instance()->id,
            [
                'title' => 'This is custom title',
                'body' => 'This is custom body',
                'body_format' => FORMAT_MOODLE,
                'subject' => 'This is custom subject'
            ]
        );

        $this->preference_value = model::from_parent_notification_preference($custom_notification);
    }

    /**
     * @return void
     */
    protected function tearDown(): void {
        $this->preference_value = null;
    }

    /**
     * @return void
     */
    public function test_resolve_field_title(): void {
        self::assertEquals(
            'This is custom title',
            $this->resolve_graphql_type(
                $this->get_graphql_name(notification_preference_value::class),
                'title',
                $this->preference_value
            )
        );
    }

    /**
     * @return void
     */
    public function test_resolve_field_subject(): void {
        self::assertEquals(
            'This is custom subject',
            $this->resolve_graphql_type(
                $this->get_graphql_name(notification_preference_value::class),
                'subject',
                $this->preference_value
            )
        );
    }

    /**
     * @return void
     */
    public function test_resolve_field_body(): void {
        self::assertEquals(
            'This is custom body',
            $this->resolve_graphql_type(
                $this->get_graphql_name(notification_preference_value::class),
                'body',
                $this->preference_value
            )
        );
    }

    /**
     * @return void
     */
    public function test_resolve_field_body_format(): void {
        self::assertEquals(
            FORMAT_MOODLE,
            $this->resolve_graphql_type(
                $this->get_graphql_name(notification_preference_value::class),
                'body_format',
                $this->preference_value
            )
        );
    }
}