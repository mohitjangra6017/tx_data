<?php
/*
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Samantha Jayasinghe <samantha.jayasinghe@totaralearning.com>
 * @package totara_notification
 */
namespace totara_notification\webapi\resolver\mutation;

use coding_exception;
use core\webapi\execution_context;
use core\webapi\middleware\require_login;
use core\webapi\mutation_resolver;
use core\webapi\resolver\has_middleware;
use totara_notification\model\notification_preference;

class delete_notification_preference implements mutation_resolver, has_middleware {
    /**
     * @param array             $args
     * @param execution_context $ec
     * @return bool
     */
    public static function resolve(array $args, execution_context $ec): bool {
        if (empty($args['id'])) {
            throw new coding_exception(get_string('error_preference_id_missing', 'totara_notification'));
        }
        $notification_preference = notification_preference::from_id($args['id']);

        if (!notification_preference::can_manage($notification_preference->get_extended_context())) {
            throw new coding_exception(get_string('error_manage_notification', 'totara_notification'));
        }

        return $notification_preference->delete_custom();
    }

    /**
     * @return array
     */
    public static function get_middleware(): array {
        return [
            new require_login(),
        ];
    }
}