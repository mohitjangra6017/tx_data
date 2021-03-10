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
use totara_notification\placeholder\placeholder_option;
use totara_core\extended_context;

/**
 * An interface to help us integrating the centralised notification system's event with the
 * current system's event.
 *
 * It provides all the available metadata (options) for a system admin to create a custom notification
 * for this very specific event or edit a built-in notification that come out-of-box.
 */
interface notifiable_event {
    /**
     * Returns the title for this notifiable event, which should be used
     * within the tree table of available notifiable events.
     *
     * @return string
     */
    public static function get_notification_title(): string;

    /**
     * Returns an array of available recipients (metadata) for this event (concrete class).
     *
     * @return array
     */
    public static function get_notification_available_recipients(): array;

    /**
     * Returns an array of available timing for this event (concrete class).
     *
     * @return array
     */
    public static function get_notification_available_schedules(): array;

    /**
     * Returns the default delivery channels that defined for the event by developers.
     * However, note that admin can override this default delivery channels.
     *
     * @return array
     */
    public static function get_notification_default_delivery_channels(): array;

    /**
     * Returns the list of available placeholder options.
     *
     * @return placeholder_option[]
     */
    public static function get_notification_available_placeholder_options(): array;

    /**
     * Returns a hash-map of data attributes that the event should be using to feed to all the
     * notifications, that can be produced by this event.
     *
     * @return array
     */
    public function get_notification_event_data(): array;

    /**
     * Returns the extended context of where this event occurred. Note that this should almost certainly be
     * either the same as the natural context (but wrapped in the extended context container class) or an
     * extended context where the natural context is the immediate parent.
     *
     * @return extended_context
     */
    public function get_notification_extended_context(): extended_context;
}