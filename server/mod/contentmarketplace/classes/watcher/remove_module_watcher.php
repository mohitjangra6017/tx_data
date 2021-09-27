<?php
/**
 * This file is part of Totara Core
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
 * @package mod_contentmarketplace
 */
namespace mod_contentmarketplace\watcher;

use container_course\hook\remove_module_hook;

class remove_module_watcher {
    /**
     * remove_module_watcher constructor.
     */
    private function __construct() {
        // Prevent this class from instantiation.
    }

    /**
     * @param remove_module_hook $hook
     * @return void
     */
    public static function watch(remove_module_hook $hook): void {
        $component_area = $hook->get_component_area();
        if (null !== $component_area) {
            if ($component_area->get_component() === "core_completion"
                && $component_area->get_area() === "criteria_activity_form") {
                // We are in configuring activity form, hence skipping the execution.
                // As mod_contentmarketplace supports completion.
                return;
            }
        }

        if ($hook->has_module('contentmarketplace')) {
            $hook->remove_module('contentmarketplace');
        }
    }
}