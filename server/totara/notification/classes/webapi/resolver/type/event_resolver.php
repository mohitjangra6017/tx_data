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
use core\webapi\execution_context;
use core\webapi\type_resolver;
use totara_core\extended_context;
use totara_notification\loader\notification_preference_loader;
use totara_notification\local\helper;
use totara_notification\local\schedule_helper;
use totara_notification\resolver\resolver_helper;

/**
 * Type resolver for totara_notification_event_resolver.
 */
class event_resolver implements type_resolver {
    /**
     * Note that at this point we are going to use $source as the notifiable event class name
     * to resolve the field's value of a totara_notification_event_resolver graphql type.
     *
     * Ideally the $source should be a model of notifiable_event_preference, however it had not  yet
     * been implemented and will be done in TL-29288 & TL-29289
     *
     * @param string            $field
     * @param string            $source
     * @param array             $args
     * @param execution_context $ec
     * @return mixed|null
     */
    public static function resolve(string $field, $source, array $args, execution_context $ec) {
        if (!is_string($source) || !resolver_helper::is_valid_event_resolver($source)) {
            throw new coding_exception("Invalid source passed to the resolver (event_resolver)");
        }
        switch ($field) {
            case 'component':
                return resolver_helper::get_component_of_resolver_class_name($source);

            case 'plugin_name':
                return resolver_helper::get_human_readable_plugin_name($source);

            case 'class_name':
                return (string) $source;

            case 'name':
                return resolver_helper::get_human_readable_resolver_name($source);

            case 'notification_preferences':
                $extended_context_args = $args['extended_context'] ?? [];

                // Default extended context.
                $extended_context = extended_context::make_system();

                if (isset($extended_context_args['context_id'])) {
                    $extended_context = extended_context::make_with_id(
                        $extended_context_args['context_id'],
                        $extended_context_args['component'] ?? extended_context::NATURAL_CONTEXT_COMPONENT,
                        $extended_context_args['area'] ?? extended_context::NATURAL_CONTEXT_AREA,
                        $extended_context_args['item_id'] ?? extended_context::NATURAL_CONTEXT_ITEM_ID
                    );
                } else if ($ec->has_relevant_context()) {
                    $context = $ec->get_relevant_context();
                    $extended_context = extended_context::make_with_context($context);
                }

                return notification_preference_loader::get_notification_preferences($extended_context, $source);

            case 'valid_schedules':
                return schedule_helper::get_available_schedules_for_resolver($source);

            case 'recipients':
                return helper::get_component_of_recipients($source);

            case 'status':
                // Default extended context.
                $extended_context = extended_context::make_system();

                $resolver_class_name = $source;

                if (isset($extended_context_args['context_id'])) {
                    $extended_context = extended_context::make_with_id(
                        $extended_context_args['context_id'],
                        $extended_context_args['component'] ?? extended_context::NATURAL_CONTEXT_COMPONENT,
                        $extended_context_args['area'] ?? extended_context::NATURAL_CONTEXT_AREA,
                        $extended_context_args['item_id'] ?? extended_context::NATURAL_CONTEXT_ITEM_ID
                    );
                } else if ($ec->has_relevant_context()) {
                    $context = $ec->get_relevant_context();
                    $extended_context = extended_context::make_with_context($context);
                }

                $is_enabled = helper::is_resolver_enabled_for_all_parent_contexts(
                    $resolver_class_name,
                    $extended_context
                );

                return [
                    'is_enabled' => $is_enabled
                ];

            default:
                throw new coding_exception("The field '{$field}' is not yet supported");
        }
    }
}
