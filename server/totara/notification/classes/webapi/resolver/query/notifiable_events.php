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
namespace totara_notification\webapi\resolver\query;

use context;
use context_system;
use core\webapi\execution_context;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;
use core\webapi\middleware\require_login;
use totara_notification\factory\notifiable_event_factory;

class notifiable_events implements query_resolver, has_middleware {
    /**
     * @param array             $args
     * @param execution_context $ec
     * @return string[]
     */
    public static function resolve(array $args, execution_context $ec): array {
        // Note: for now we are returning a list of notifiable_event class within the system.
        // However for future development, we might just do sort of DB looks up to get all the notifiable
        // event within configuration within the system
        $context = $args['context_id'] ? context::instance_by_id($args['context_id']) : context_system::instance();
        if (CONTEXT_SYSTEM !== $context->contextlevel && !$ec->has_relevant_context()) {
            $ec->set_relevant_context($context);
        }

        $component = $args['component'] ?? null;
        return notifiable_event_factory::get_notifiable_events($component);
    }

    /**
     * @return array
     */
    public static function get_middleware(): array {
        return [
            new require_login()
        ];
    }
}
