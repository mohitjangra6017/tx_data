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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Kunle Odusan <kunle.odusan@totaralearning.com>
 * @package mod_perform
 */

namespace mod_perform\webapi\resolver\mutation;

use coding_exception;
use core\webapi\execution_context;
use core\webapi\middleware\require_advanced_feature;
use core\webapi\mutation_resolver;
use core\webapi\resolver\has_middleware;
use mod_perform\models\activity\element;
use mod_perform\webapi\middleware\require_activity;
use mod_perform\webapi\middleware\require_manage_capability;

class update_child_element implements mutation_resolver, has_middleware {

    public static function resolve(array $args, execution_context $ec) {
        $input = $args['input'];
        $element_details = $input['element_details'];
        $element = element::load_by_id($input['element_id']);

        if ($element->parent === null) {
            throw new coding_exception("Element is not a child element.");
        }

        $element->update_details(
            $element_details['title'],
            $element_details['data'] ?? null,
            $element_details['is_required'] ?? null,
            $element_details['identifier'] ?? '',
        );

        return [
            'element' => $element,
        ];
    }

    public static function get_middleware(): array {
        return [
            new require_advanced_feature('performance_activities'),
            require_activity::by_element_id('input.element_id'),
            require_manage_capability::class,
        ];
    }

}