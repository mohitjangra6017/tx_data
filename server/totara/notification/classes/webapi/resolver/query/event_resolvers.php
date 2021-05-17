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
namespace totara_notification\webapi\resolver\query;

use context;
use context_system;
use core\webapi\execution_context;
use core\webapi\middleware\require_login;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;
use totara_core\extended_context;
use totara_notification\exception\notification_exception;
use totara_notification\factory\notifiable_event_resolver_factory;
use totara_notification\interactor\notification_preference_interactor;
use totara_notification\local\helper;
use totara_notification\resolver\notifiable_event_resolver;

class event_resolvers implements query_resolver, has_middleware {
    /**
     * @param array             $args
     * @param execution_context $ec
     * @return string[]
     */
    public static function resolve(array $args, execution_context $ec): array {
        global $USER;

        // Note: for now we are returning a list of notifiable_event_resolver classes within the system.
        // However for future development, we might just do sort of DB looks up to get all the notifiable
        // event within configuration within the system
        $context = context_system::instance();
        $extended_context_args = $args['extended_context'];

        if (isset($extended_context_args['context_id'])) {
            $context = context::instance_by_id($args['extended_context']['context_id']);
        }

        $extended_context = extended_context::make_with_context(
            $context,
            $extended_context_args['component'] ?? extended_context::NATURAL_CONTEXT_COMPONENT,
            $extended_context_args['area'] ?? extended_context::NATURAL_CONTEXT_AREA,
            $extended_context_args['item_id'] ?? extended_context::NATURAL_CONTEXT_ITEM_ID
        );

        if (CONTEXT_SYSTEM != $extended_context->get_context_level() && !$ec->has_relevant_context()) {
            $ec->set_relevant_context($context);
        }

        $interactor = new notification_preference_interactor($extended_context, $USER->id);
        // Check if the user has any of the capabilities provided by the integrated plugins or not.
        if (!$interactor->has_any_capability_for_context()) {
            // Note: i'm not sure if throwing exception is the ideal way or return an empty result is.
            throw notification_exception::on_manage();
        }

        $resolver_classes = notifiable_event_resolver_factory::get_resolver_classes();

        return array_filter(
            $resolver_classes,
            function ($resolver_class) use ($interactor, $extended_context): bool {
                /**
                 * @see notifiable_event_resolver::supports_context()
                 * @var bool $support
                 */
                $support = call_user_func_array([$resolver_class, 'supports_context'], [$extended_context]);
                if (!$support) {
                    return false;
                }

                $enabled_in_parent_contexts = helper::is_resolver_enabled_for_all_parent_contexts($resolver_class, $extended_context);
                if (!$enabled_in_parent_contexts) {
                    return false;
                }

                // We are only displaying the event resolver if the user has the capability to
                // manage/interact with the resolver.
                return $interactor->can_manage_notification_preferences_of_resolver($resolver_class);
            }
        );
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
