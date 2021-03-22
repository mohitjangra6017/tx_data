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
 * @author Jaron Steenson <jaron.steenson@totaralearning.com>
 */

namespace performelement_aggregation\data_provider;

use coding_exception;
use mod_perform\entity\activity\section_element_reference as section_element_reference_entity;
use mod_perform\models\activity\section_element_reference;
use performelement_aggregation\aggregation;

/**
 * Data provider class that adds extra information into the reference elements JSON data.
*/
class aggregation_data {

    /**
     * Add the source section element ids back into the json element data/settings.
     *
     * @param int $aggregation_element_id
     * @return array
     * @throws coding_exception
     */
    public function include_extra_info(int $aggregation_element_id): array {
        $element_settings = [];

        /** @var section_element_reference_entity[] $section_element_reference */
        $section_element_references = section_element_reference_entity::repository()
            ->where('referencing_element_id', $aggregation_element_id)
            ->order_by('id') // Keep display order consistent with insert order.
            ->get();

        if (count($section_element_references) === 0) {
            return $element_settings;
        }

        $element_settings[aggregation::SOURCE_SECTION_ELEMENT_IDS] = $section_element_references->pluck('source_section_element_id');

        return $element_settings;
    }

}
