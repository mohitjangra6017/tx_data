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
 * @package totara_notification
 */
namespace totara_notification\event;

use core\event\abstraction\context_aware;

/**
 * An interface to provide all the metadata that allow users to create a custom notification,
 * nor edit a custom
 */
interface notifiable_event extends context_aware {
    /**
     * Returning the title for this notifiable event, which should be used
     * within the tree table of available notifiable events.
     *
     * @return string
     */
    public static function get_notification_title(): string;

    /**
     * Returning an array of available recipients (metadata) for this event (concrete class).
     *
     * @return array
     */
    public static function get_notification_available_recipients(): array;

    /**
     * Returning an array of available timing for this event (concrete class).
     *
     * @return array
     */
    public static function get_notification_available_schedules(): array;

    /**
     * Return the default deliviry channels that defined for the event by developers.
     * However, note that admin can override this default delivery channels.
     *
     * @return array
     */
    public static function get_notification_default_delivery_channels(): array;

    /**
     * Returning a hash-map of data attributes that the event should be using to feed to all the
     * notifications, that can be produced by this event.
     *
     * @return array
     */
    public function get_notification_event_data(): array;
}