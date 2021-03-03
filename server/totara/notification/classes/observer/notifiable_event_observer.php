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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
namespace totara_notification\observer;

use core\event\base;
use totara_notification\event\notifiable_event;
use totara_notification\entity\notifiable_event_queue;

/**
 * Event observer for notifiable event interface.
 */
final class notifiable_event_observer {
    /**
     * notification_observer constructor.
     */
    private function __construct() {
    }

    /**
     * @param notifiable_event|base $event
     * @return void
     */
    public static function watch_notifiable_event(notifiable_event $event): void {
        $event_data = $event->get_notification_event_data();

        $queue = new notifiable_event_queue();
        $queue->event_name = get_class($event);
        $queue->event_time = $event_data['event_time'] ?? $event->timecreated;
        $queue->set_decoded_event_data($event_data);
        $queue->context_id = $event->get_context()->id;

        $queue->save();
    }
}