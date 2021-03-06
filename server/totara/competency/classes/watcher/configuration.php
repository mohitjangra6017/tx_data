<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2019 onwards Totara Learning Solutions LTD
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
 * @author Riana Rossouw <riana.rossouw@totaralearning.com>
 * @package totara_competency
 */

namespace totara_competency\watcher;

use totara_competency\aggregation_users_table;
use totara_competency\entity\configuration_change;
use totara_competency\hook\competency_configuration_changed;

class configuration {

    /**
     * @param competency_configuration_changed
     * @throws \coding_exception
     */
    public static function configuration_changed(competency_configuration_changed $hook) {
        $competency_id = $hook->get_competency_id();
        $has_changed = null;
        // A change in the aggregation type has to trigger the competency reaggregation
        if ($hook->get_change_type() === configuration_change::CHANGED_AGGREGATION) {
            $has_changed = 1;
        }

        (new aggregation_users_table())->queue_all_assigned_users_for_aggregation($competency_id, $has_changed);
    }

}
