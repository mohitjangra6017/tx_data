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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Marco Song <marco.song@totaralearning.com>
 * @package mod_perform
 */

namespace performelement_redisplay\models\helpers;

use mod_perform\entity\activity\element as element_entity;
use mod_perform\models\activity\element;
use mod_perform\models\activity\helpers\element_clone_helper;
use stdClass;

class redisplay_element_clone implements element_clone_helper {

    /**
     * @inheritDoc
     */
    public function restore(int $new_section_element_id, stdClass $data, element $element): void {
        // Cannot completely restore a redisplay element before all other elements (of different types)
        // of the activity have been restored.
    }

    /**
     * The redisplay element's data references the section_element of the element it is pointing to. Adjusting that
     * can become necessary during cloning.
     *
     * @param element $element
     * @param int $new_source_section_element_id
     */
    public function update_source_section_element_id(element $element, int $new_source_section_element_id) {
        if ($element->plugin_name !== 'redisplay') {
            return;
        }
        // The source_section_element is referenced in the element data's json, so adjust that.
        /** @var element_entity $element_entity */
        $element_entity = element_entity::repository()->find($element->id);
        $element_data = json_decode($element_entity->data, true);
        if (is_array($element_data) && isset($element_data['sectionElementId'])) {
            $element_data = array_merge($element_data, ['sectionElementId' => $new_source_section_element_id]);
            $element_entity->data = json_encode($element_data);
            $element_entity->save();
        }
    }
}
