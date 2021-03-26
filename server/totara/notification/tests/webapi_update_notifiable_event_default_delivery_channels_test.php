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
 * @author  Cody Finegan <cody.finegan@totaralearning.com>
 * @package totara_notification
 */

use core_phpunit\testcase;
use totara_core\extended_context;
use totara_notification\delivery\channel\delivery_channel;
use totara_notification\testing\generator;
use totara_notification\webapi\resolver\mutation\update_default_delivery_channels;
use totara_notification_mock_notifiable_event_resolver as mock_resolver;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_notification_webapi_update_notifiable_event_default_delivery_channels_testcase extends testcase {
    use webapi_phpunit_helper;

    /**
     * Confirm that we can update the delivery channels in the system context
     *
     * @return void
     */
    public function test_update_default_channels_for_system_context(): void {
        global $DB;

        // Confirm we have no existing preference
        $count = $DB->count_records('notifiable_event_preference');
        self::assertEquals(0, $count);

        $this->setAdminUser();
        $extended_context = extended_context::make_system();

        // Save the channels for the mock resolver
        /** @var delivery_channel[] $saved_channels */
        $saved_channels = $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_default_delivery_channels::class),
            [
                'resolver_class_name' => mock_resolver::class,
                'extended_context' => [
                    'context_id' => $extended_context->get_context_id(),
                    'component' => $extended_context->get_component(),
                    'area' => $extended_context->get_area(),
                    'item_id' => $extended_context->get_item_id(),
                ],
                'default_delivery_channels' => [
                    'email',
                    'msteams',
                ],
            ]
        );

        self::assertIsArray($saved_channels);
        self::assertTrue($saved_channels['email']->is_enabled);
        self::assertTrue($saved_channels['msteams']->is_enabled);
        self::assertFalse($saved_channels['totara_task']->is_enabled);
        self::assertFalse($saved_channels['totara_alert']->is_enabled);
        self::assertFalse($saved_channels['popup']->is_enabled);
        self::assertFalse($saved_channels['totara_airnotifier']->is_enabled);

        $count = $DB->count_records('notifiable_event_preference');
        self::assertEquals(1, $count);

        // Run it a second time, toggling the email off
        $saved_channels = $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_default_delivery_channels::class),
            [
                'resolver_class_name' => mock_resolver::class,
                'extended_context' => [
                    'context_id' => $extended_context->get_context_id(),
                    'component' => $extended_context->get_component(),
                    'area' => $extended_context->get_area(),
                    'item_id' => $extended_context->get_item_id(),
                ],
                'default_delivery_channels' => [
                    'msteams',
                ],
            ]
        );

        self::assertIsArray($saved_channels);
        self::assertFalse($saved_channels['email']->is_enabled);
        self::assertTrue($saved_channels['msteams']->is_enabled);
    }

    /**
     * Confirm that we cannot update a delivery channel in a non-system context
     *
     * @return void
     */
    public function test_update_default_channels_for_course_context(): void {
        global $DB;

        // Confirm we have no existing preference
        $count = $DB->count_records('notifiable_event_preference');
        self::assertEquals(0, $count);

        $this->setAdminUser();

        $generator = self::getDataGenerator();
        $course = $generator->create_course();
        $context_course = context_course::instance($course->id);
        $extended_context = extended_context::make_with_context($context_course);

        $this->expectException(\coding_exception::class);
        $this->expectExceptionMessage('Delivery channels are only available in the system context');

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(update_default_delivery_channels::class),
            [
                'resolver_class_name' => mock_resolver::class,
                'extended_context' => [
                    'context_id' => $extended_context->get_context_id(),
                    'component' => $extended_context->get_component(),
                    'area' => $extended_context->get_area(),
                    'item_id' => $extended_context->get_item_id(),
                ],
                'default_delivery_channels' => [
                    'email',
                    'msteams',
                ],
            ]
        );
    }

    protected function setUp(): void {
        global $DB;

        // We want nothing created
        $DB->execute('TRUNCATE TABLE {notifiable_event_preference}');

        $notification_generator = generator::instance();
        $notification_generator->include_mock_notifiable_event_resolver();
    }

}