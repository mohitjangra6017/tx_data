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
 * @author Riana Rossouw <riana.rossouw@totaralearning.com>
 * @package totara_notification
 */

namespace totara_notification\local;

use totara_notification\entity\notifiable_event_user_preference as notifiable_event_user_preference_entity;
use totara_notification\resolver\resolver_helper;

class notifiable_event_user_preference_helper {
    /**
     * Preventing this class from construction.
     */
    private function __construct() {
    }
    
    /**
     * @param int $user_id
     * @param string $resolver_class_name
     * @param notifiable_event_user_preference_entity|null $user_preference
     * @return array
     */
    public static function format_response_data(int $user_id, string $resolver_class_name, ?notifiable_event_user_preference_entity $user_preference = null): array {
        return [
            'user_id' => $user_id,
            'component' => resolver_helper::get_component_of_resolver_class_name($resolver_class_name),
            'plugin_name' => resolver_helper::get_human_readable_plugin_name($resolver_class_name),
            'resolver_class_name' => $resolver_class_name,
            'name' => resolver_helper::get_human_readable_resolver_name($resolver_class_name),
            'enabled' => $user_preference === null ? true : (bool)$user_preference->enabled,
            'user_preference_id' => $user_preference === null ? null : $user_preference->id,
        ];
    }
}