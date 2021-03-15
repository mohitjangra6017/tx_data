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
 * @author  Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_notification
 */

use totara_core\event\menuitem_created;
use totara_notification\factory\built_in_notification_factory;
use totara_notification\notification\built_in_notification;
use totara_notification\testing\generator;

/**
 * @group totara_notification
 */
class totara_notification_built_in_notification_factory_testcase extends advanced_testcase {
    /**
     * @return void
     */
    public function test_get_all_notification_classes_from_system(): void {
        $expected = core_component::get_namespace_classes(
            'totara_notification\\notification',
            built_in_notification::class
        );

        $result = built_in_notification_factory::get_notification_classes();
        self::assertSameSize($expected, $result);
    }

    /**
     * @return void
     */
    public function test_get_notification_of_an_event_name(): void {
        $generator = self::getDataGenerator();

        /** @var generator $notification_generator */
        $notification_generator = $generator->get_plugin_generator('totara_notification');
        $notification_generator->include_mock_notifiable_event();
        $notification_generator->add_mock_built_in_notification_for_component();

        $result = built_in_notification_factory::get_notification_classes_of_notifiable_event(
            totara_notification_mock_notifiable_event::class
        );

        self::assertCount(1, $result);
        $first_element = reset($result);

        self::assertEquals(totara_notification_mock_built_in_notification::class, $first_element);
    }

    /**
     * @return void
     */
    public function test_get_notification_of_an_non_implemented_event_name(): void {
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage(
            "Expecting the argument event class name to implement interface totara_notification\\event\\notifiable_event"
        );

        built_in_notification_factory::get_notification_classes_of_notifiable_event(menuitem_created::class);
    }

    /**
     * @return void
     */
    public function test_get_notification_of_an_non_existed_event_name(): void {
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage(
            "Expecting the argument event class name to implement interface totara_notification\\event\\notifiable_event"
        );

        built_in_notification_factory::get_notification_classes_of_notifiable_event('martin_garrix_classname');
    }
}