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

use context_system;
use coding_exception;
use core\webapi\execution_context;
use core\webapi\middleware\require_login;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;
use totara_core\extended_context;
use totara_notification\loader\notification_preference_loader;
use totara_notification\model\notification_preference as model;

/**
 * Resolving query totara_notification_notification_preferences
 */
class notification_preferences implements query_resolver, has_middleware {
    /**
     * @param array             $args
     * @param execution_context $ec
     *
     * @return model[]
     */
    public static function resolve(array $args, execution_context $ec): array {
        $extended_context = extended_context::make_with_id(
            $args['context_id'],
            $args['component'] ?? extended_context::NATURAL_CONTEXT_COMPONENT,
            $args['area'] ?? extended_context::NATURAL_CONTEXT_AREA,
            $args['item_id'] ?? extended_context::NATURAL_CONTEXT_ITEM_ID
        );

        if (!model::can_manage($extended_context)) {
            throw new coding_exception(get_string('error_manage_notification', 'totara_notification'));
        };

        if ($extended_context->get_context_id() != context_system::instance()->id &&
            !$ec->has_relevant_context()
        ) {
            $ec->set_relevant_context($extended_context->get_context());
        }

        $resolver_class_name = $args['resolver_class_name'] ?? null;

        return notification_preference_loader::get_notification_preferences(
            $extended_context,
            $resolver_class_name,
            $args['at_context_only'] ?? false
        );
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