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
namespace totara_notification\webapi\resolver\type;
use coding_exception;
use core\webapi\execution_context;
use core\webapi\type_resolver;
use totara_notification\local\schedule_helper;
use totara_notification\model\notification_preference_value as model;

/**
 * Resolver for type 'totara_notification_notification_preference_value'.
 */
class notification_preference_value implements type_resolver {
    /**
     * @param string            $field
     * @param model             $source
     * @param array             $args
     * @param execution_context $ec
     * @return mixed|void
     */
    public static function resolve(string $field, $source, array $args, execution_context $ec) {
        if (!($source instanceof model)) {
            throw new coding_exception(
                "Invalid source passed to the resolver"
            );
        }

        switch ($field) {
            case 'body':
                return $source->get_body();

            case 'body_format':
                return $source->get_body_format();

            case 'subject':
                return $source->get_subject();

            case 'title':
                return $source->get_title();

            case 'schedule_offset':
                return schedule_helper::get_schedule_offset($source->get_scheduled_offset());

            case 'schedule_type':
                return schedule_helper::get_schedule_identifier($source->get_scheduled_offset());

            default:
                throw new coding_exception(
                    "Invalid field '{$field}' is not yet supported"
                );
        }
    }
}