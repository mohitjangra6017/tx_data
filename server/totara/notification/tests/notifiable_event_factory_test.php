<?php
/**
 * This file is part of Totara Learn
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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */

use totara_notification\event\notifiable_event;
use totara_notification\factory\notifiable_event_factory;

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
        global $CFG;
        require_once("{$CFG->dirroot}/totara/notification/tests/fixtures/totara_notification_mock_notifiable_event.php");

        notifiable_event_factory::phpunit_add_notifiable_event_class(
            'totara_notification',
            totara_notification_mock_notifiable_event::class
        );

        $event_classes = notifiable_event_factory::get_notifiable_events('totara_notification');
        self::assertCount(1, $event_classes);

        $first_event = reset($event_classes);
        self::assertEquals(totara_notification_mock_notifiable_event::class, $first_event);
    }

    /**
     * @return void
     */
    public function test_phpunit_add_invalid_event(): void {
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage(
            "Expecting the event class to implement interface totara_notification\\event\\notifiable_event"
        );

        notifiable_event_factory::phpunit_add_notifiable_event_class('core', 'martin_Garrix');
    }
}