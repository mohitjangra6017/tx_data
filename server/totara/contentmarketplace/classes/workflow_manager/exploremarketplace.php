<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2018 onwards Totara Learning Solutions LTD
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
 * @author Simon Coggins <simon.coggins@totaralearning.com>
 * @package totara_contentmarketplace
 */

namespace totara_contentmarketplace\workflow_manager;

use context_coursecat;
use totara_contentmarketplace\local;
use totara_workflow\workflow_manager\base;

defined('MOODLE_INTERNAL') || die();

/**
 * Workflow manager singleton class for managing explore marketplace workflow instances.
 */
class exploremarketplace extends base {

    public function get_name(): string {
        return get_string('explore_totara_content', 'totara_contentmarketplace');
    }

    protected function can_access(): bool {
        if (!local::is_enabled()) {
            return false;
        }

        // Allowed to add content from marketplaces.
        $params = $this->get_params();

        if (isset($params['category'])) {
            $context = context_coursecat::instance($params['category']);
            return has_capability('totara/contentmarketplace:add', $context);
        }

        if (during_initial_install()) {
            return true;
        }

        return has_capability_in_any_context('totara/contentmarketplace:add');
    }
}
