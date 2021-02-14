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
use totara_notification\local\helper;
use totara_notification\model\notification_preference as model;
use totara_notification\webapi\formatter\notification_preference_formatter;

class notification_preference implements type_resolver {
    /**
     * @param string            $field
     * @param model             $source
     * @param array             $args
     * @param execution_context $ec
     * @return mixed
     */
    public static function resolve(string $field, $source, array $args, execution_context $ec) {
        if (!($source instanceof model)) {
            throw new coding_exception("Expected notification preference model");
        }

        if ('component' === $field) {
            $event_class_name = $source->get_event_class_name();
            return helper::get_component_of_event_class_name($event_class_name);
        } else if ('parent_id' === $field) {
            $parent = $source->get_parent();
            return (null === $parent) ? null : $parent->get_id();
        }

        $context = context_system::instance();
        if ($ec->has_relevant_context()) {
            $context = $ec->get_relevant_context();
        }

        // Note, we will have to do sort of caching to help improve the speed performance.
        // Because for every fetching body/subject/title and all sort of other fields,
        // our model will try to look up DB for its parent, unless its parent is already
        // fetched in the model itself.
        $formatter = new notification_preference_formatter($source, $context);
        return $formatter->format($field, $args['format'] ?? null);
    }
}