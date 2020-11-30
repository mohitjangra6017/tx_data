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
use core\collection;

abstract class deletion_check_base {

    /**
     * Concat section name with the activity name it belongs to
     *
     * @param collection $sections
     * @return array
     * @throws coding_exception
     */
    protected static function get_data(collection $sections) {
        $data = [];
        foreach ($sections as $section) {
            $data[] = get_string(
                'activity_name_with_section_name',
                'performelement_redisplay',
                (object) [
                    'activity_name' => $section->activity->name,
                    'section_name' => $section->display_title,
                ]
            );
        }

        return $data;
    }
}
