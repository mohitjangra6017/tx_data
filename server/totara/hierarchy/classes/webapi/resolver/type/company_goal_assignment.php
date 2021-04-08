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
 * @author Murali Nair <murali.nair@totaralearning.com>
 * @package totara_hierarchy
 */

namespace totara_hierarchy\webapi\resolver\type;

use coding_exception;
use context_system;

use core\webapi\type_resolver;
use core\webapi\execution_context;

use hierarchy_goal\company_goal_assignment as company_goal_assignment_model;
use hierarchy_goal\formatter\company_goal_assignment as company_goal_assignment_formatter ;

/**
 * Populates a GraphQL totara_hierarchy_company_goal_assignment type.
 */
class company_goal_assignment implements type_resolver {
    /**
     * {@inheritdoc}
     */
    public static function resolve(string $field, $source, array $args, execution_context $ec) {
        if (!$source instanceof company_goal_assignment_model) {
            throw new coding_exception(__METHOD__ . ' requires a company_goal_assignment model');
        }

        $format = $args['format'] ?? null;
        $context = $ec->has_relevant_context()
            ? $ec->get_relevant_context()
            : context_system::instance();
        $formatter = new company_goal_assignment_formatter($source, $context);

        return $formatter->format($field, $format);
    }
}
