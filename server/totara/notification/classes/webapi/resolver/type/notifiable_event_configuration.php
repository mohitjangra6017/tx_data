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
namespace totara_notification\webapi\resolver\type;

use core\webapi\execution_context;
use core\webapi\type_resolver;
use coding_exception;
use totara_notification\event\notifiable_event;
use totara_notification\factory\built_in_notification_factory;
use totara_notification\local\helper;

final class notifiable_event_configuration implements type_resolver {
    /**
     * @param string $field
     * @param string $source
     * @param array $args
     * @param execution_context $ec
     *
     * @return mixed
     */
    public static function resolve(string $field, $source, array $args, execution_context $ec) {
        if (!is_string($source) || !helper::is_valid_notifiable_event($source)) {
            throw new coding_exception("'{$source}' is wrong type.");
        }

        switch ($field) {
            case 'event_key':
                return $source;
            case 'title':
                /** @see notifiable_event::get_notification_title() */
                return call_user_func([$source, 'get_notification_title']);
            case 'notification_configurations':
                // Note: TL-29556 will be used for refactoring this part to make it easier
                // for lazy loading the notification preferences.
                $classes = built_in_notification_factory::get_notification_classes_of_notifiable_event($source);
                if (empty($classes)) {
                    return [];
                }

                $data_return = [];
                foreach ($classes as $class_name) {
                    $data_return[] = new $class_name();
                }

                return $data_return;
            default:
                throw new coding_exception("Field '{$field}' is not yet supported");
        }
    }
}