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

namespace performelement_redisplay\watcher;

use coding_exception;
use mod_perform\hook\pre_section_element_deleted;
use performelement_redisplay\models\element_redisplay_relationship;

/**
 * Check if a section element can be deleted
 *
 * @package performelement_redisplay\watcher
 */
class section_element_deletion_check extends deletion_check_base {

    /**
     * Section element only can be deleted if it is not referenced by any redisplay element
     *
     * @param pre_section_element_deleted $hook
     * @throws coding_exception
     */
    public static function can_delete(pre_section_element_deleted $hook) {
        $section_element_id = $hook->get_section_element_id();
        $sections = element_redisplay_relationship::get_sections_by_source_section_element_id($section_element_id);

        $can_delete = $sections->count() < 1;

        if (!$can_delete) {
            $hook->add_reason(
                'is_referenced_by_redisplay_element',
                get_string('modal_can_not_delete_element_message', 'performelement_redisplay'),
                self::get_data($sections)
            );
        }
    }
}
