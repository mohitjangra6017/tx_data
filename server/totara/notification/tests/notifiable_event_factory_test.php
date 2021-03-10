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

use totara_notification\event\notifiable_event;
use totara_notification\factory\notifiable_event_factory;
use totara_notification\testing\generator;

/**
 * @group totara_notification
 */
class totara_notification_notifiable_event_factory_testcase extends advanced_testcase {
    /**
     * @return void
     */
    public function test_get_events(): void {
        $events = notifiable_event_factory::get_notifiable_events();
        $result = core_component::get_namespace_classes('event', notifiable_event::class);

        self::assertEquals($result, $events);
    }

    /**
     * @return void
     */
    public function test_get_events_from_component(): void {
        $generator = self::getDataGenerator();

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $notification_generator->include_mock_notifiable_event();

        // Before adding mock to the component.
        self::assertCount(0, notifiable_event_factory::get_notifiable_events('totara_notification'));

        $notification_generator->add_mock_notifiable_event_for_component(
            totara_notification_mock_notifiable_event::class
        );

        $event_classes = notifiable_event_factory::get_notifiable_events('totara_notification');
        self::assertCount(1, $event_classes);

        $first_event = reset($event_classes);
        self::assertEquals(totara_notification_mock_notifiable_event::class, $first_event);
    }
}