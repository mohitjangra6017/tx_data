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

namespace totara_notification;

use totara_notification\entity\notifiable_event_queue;
use totara_notification\resolver\notifiable_event_resolver;

/**
 * This class can be called for other plugins
 *
 * Class external_helper
 */
class external_helper {
    /**
     * helper constructor.
     * Preventing this class from instantiation.
     */
    private function __construct() {
    }

    /**
     * @param notifiable_event_resolver $resolver
     */
    public static function create_notifiable_event_queue(notifiable_event_resolver $resolver): void {
        $queue = new notifiable_event_queue();
        $queue->resolver_class_name = get_class($resolver);
        $queue->set_decoded_event_data($resolver->get_event_data());
        $queue->set_extended_context($resolver->get_extended_context());

        $queue->save();
    }
}