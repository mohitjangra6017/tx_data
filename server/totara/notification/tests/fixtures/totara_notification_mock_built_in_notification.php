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

use totara_notification\notification\built_in_notification;

class totara_notification_mock_built_in_notification extends built_in_notification {
    /**
     * @return string
     */
    public static function get_event_class_name(): string {
        global $CFG;
        $event_class = totara_notification_mock_notifiable_event::class;

        if (!class_exists($event_class)) {
            require_once("{$CFG->dirroot}/totara/notification/tests/fixtures/totara_notification_mock_notifiable_event.php");
        }

        return totara_notification_mock_notifiable_event::class;
    }

    /**
     * @return string
     */
    public static function get_title(): string {
        return 'Mock built in notification';
    }

    /**
     * @return string
     */
    public static function get_recipient_name(): string {
        throw new coding_exception("Todo - implement me");
    }

    /**
     * @return mixed|void
     */
    public static function get_schedule() {
        throw new coding_exception("Todo - implement me");
    }
}