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
 * @package weka_notification_placeholder
 */
namespace weka_notification_placeholder\webapi\resolver\query;

use context;
use core\webapi\execution_context;
use core\webapi\middleware\require_login;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;
use totara_notification\event\notifiable_event;
use totara_notification\placeholder\placeholder_option;
use totara_notification\webapi\middleware\validate_event_class_name;

/**
 * A resolver for query weka_notification_placeholder_placeholders
 */
class placeholders implements query_resolver, has_middleware {
    /**
     * @param array             $args
     * @param execution_context $ec
     * @return array
     */
    public static function resolve(array $args, execution_context $ec): array {
        $context = context::instance_by_id($args['context_id']);
        if (CONTEXT_SYSTEM != $context->contextlevel && !$ec->has_relevant_context()) {
            $ec->set_relevant_context($context);
        }

        $event_class_name = $args['event_class_name'];

        // Empty string pattern will yield the whole list of available placeholders.
        $pattern = $args['pattern'] ?? '';

        /**
         * @see notifiable_event::get_notification_available_placeholder_options()
         * @var placeholder_option[] $placeholder_options
         */
        $placeholder_options = call_user_func([$event_class_name, 'get_notification_available_placeholder_options']);
        $options = [];

        foreach ($placeholder_options as $placeholder_option) {
            $group_options = $placeholder_option->find_map_group_options_match($pattern);
            $options = array_merge($options, $group_options);
        }

        return $options;
    }

    /**
     * @return array
     */
    public static function get_middleware(): array {
        return [
            new require_login(),
            new validate_event_class_name('event_class_name', true)
        ];
    }
}