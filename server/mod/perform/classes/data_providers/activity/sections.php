<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
 */

namespace mod_perform\data_providers\activity;

use core\collection;
use core\orm\query\builder;
use mod_perform\entity\activity\element;
use mod_perform\entity\activity\section as section_entity;
use mod_perform\entity\activity\section_element;
use mod_perform\models\activity\element_plugin;
use mod_perform\models\activity\section as section_model;

class sections {

    /**
     * Get sections of an activity with respondable section elements.
     *
     * @param int $activity_id
     * @return collection
     */
    public function get_sections_with_respondable_section_elements(int $activity_id): collection {
        $respondable_plugins = element_plugin::get_element_plugins(true, false);

        return section_entity::repository()->as('s')
            ->where_exists(
                builder::table(section_element::TABLE, 'se')
                    ->join([element::TABLE, 'e'], 'element_id', 'id')
                    ->where('s.activity_id',$activity_id)
                    ->where_field('se.section_id', 's.id')
                    ->where_in('e.plugin_name', array_keys($respondable_plugins))
            )
            ->order_by('sort_order')
            ->with('respondable_section_elements.element')
            ->get()
            ->map_to(section_model::class);
    }
}