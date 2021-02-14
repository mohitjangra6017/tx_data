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
namespace totara_notification\webapi\resolver\type;

use coding_exception;
use context_system;
use core\webapi\execution_context;
use core\webapi\type_resolver;
use totara_notification\loader\notification_preference_loader;
use totara_notification\local\helper;

/**
 * Type resolver for totara_notification_notifiable_event.
 */
class notifiable_event implements type_resolver {
    /**
     * Note that at this point we are going to use $source as the notifiable event class name
     * to resolve the field's value of a totara_notification_notifiable_event graphql type.
     *
     * Ideally the $source should be a model of notifiable_event, however it had not  yet
     * been implemented and will be done in TL-29288 & TL-29289
     *
     * @param string            $field
     * @param string            $source
     * @param array             $args
     * @param execution_context $ec
     * @return mixed|null
     */
    public static function resolve(string $field, $source, array $args, execution_context $ec) {
        if (!is_string($source) || !helper::is_valid_notifiable_event($source)) {
            throw new coding_exception("Invalid source passed to the resolver");
        }

        switch ($field) {
            case 'component':
                return helper::get_component_of_event_class_name($source);

            case 'class_name':
                return (string) $source;

            case 'name':
                return helper::get_human_readable_event_name($source);

            case 'notification_preferences':
                $context_id = $args['context_id'] ?? context_system::instance()->id;
                return notification_preference_loader::get_notification_preferences($context_id, $source);

            default:
                throw new coding_exception("The field '{$field}' had not yet supported");
        }
    }
}