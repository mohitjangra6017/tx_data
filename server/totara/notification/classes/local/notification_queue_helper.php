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
namespace totara_notification\local;

use totara_notification\entity\notification_queue;
use totara_notification\model\notification_event_data;
use totara_notification\model\notification_preference;

/**
 * A static class that contains helper methods to create the queue and what not.
 */
class notification_queue_helper {
    /**
     * notification_queue_helper constructor.
     */
    private function __construct() {
        // Prevent the construction of this class.
    }

    /**
     * @param notification_preference $preference
     * @param notification_event_data $event
     * @param int                     $event_time
     * @return notification_queue
     */
    public static function create_queue_from_preference(
        notification_preference $preference,
        notification_event_data $event,
        int $event_time
    ): notification_queue {
        $queue = new notification_queue();
        $queue->notification_preference_id = $preference->get_id();
        $queue->set_extended_context($event->get_extended_context());
        $queue->set_decoded_event_data($event->get_event_data());

        $queue->scheduled_time = schedule_helper::calculate_schedule_timestamp(
            $event_time,
            $preference->get_schedule_offset()
        );

        $queue->save();
        return $queue;
    }
}