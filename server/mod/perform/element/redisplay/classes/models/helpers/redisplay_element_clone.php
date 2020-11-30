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

use mod_perform\models\activity\element;
use mod_perform\models\activity\helpers\element_clone_helper;
use mod_perform\models\activity\section_element;
use performelement_redisplay\models\element_redisplay_relationship;
use stdClass;

class redisplay_element_clone implements element_clone_helper {

    /**
     * @inheritDoc
     */
    public function restore(int $new_section_element_id, stdClass $data, element $element): void {
        if ($element->plugin_name !== 'redisplay') {
            return;
        }

        $element_data = json_decode($element->get_data(), true);
        $source_section_element_id = $element_data['sectionElementId'];
        $source_activity_id = section_element::load_by_id($source_section_element_id)->get_section()->get_activity()->get_id();

        if ($source_section_element_id && $source_activity_id) {
            element_redisplay_relationship::create(
                $source_activity_id, $source_section_element_id, $element->get_id()
            );
        }
    }
}
