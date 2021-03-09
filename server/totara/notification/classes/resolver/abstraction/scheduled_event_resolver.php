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
namespace totara_notification\resolver\abstraction;

use totara_notification\model\notification_event_data;
use totara_notification\schedule\schedule_on_event;

/**
 * For the resolver that support those events that are scheduled to send out the notifications.
 */
interface scheduled_event_resolver {
    /**
     * Get all the events that happened/will happen within the time frame.
     *
     * Note that we are return the instance of a wrapper for the event. So that we can stay away
     * from the actual event in the system and easily prevent the triggering/dispatching the events
     * by any mistakes.
     *
     * This function is a static as we do not want those notification_event_data wrappers to be depending on
     * the dependencies data from the resolver. Basically it is to provide a collection of data
     * that we can instantiate the resolver.
     *
     * @param int $min_time
     * @param int $max_time
     *
     * @return notification_event_data[]
     */
    public static function get_scheduled_events(int $min_time, int $max_time): array;

    /**
     * Returns an array of available timing for this event (concrete class).
     * Note that you can return {@see schedule_on_event} within the result here
     * to tell that you are supporting on_event queue as well.
     *
     * @return string[]
     */
    public static function get_notification_available_schedules(): array;
}