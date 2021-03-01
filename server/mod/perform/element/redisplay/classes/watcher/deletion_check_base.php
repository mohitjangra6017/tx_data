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
use core\format;
use core\webapi\formatter\field\string_field_formatter;
use mod_perform\models\activity\section;

abstract class deletion_check_base {

    /**
     * Concat section name with the activity name it belongs to
     *
     * @param collection|section[] $sections
     * @return array
     * @throws coding_exception
     */
    protected static function get_data(collection $sections) {
        $data = [];
        foreach ($sections as $section) {
            $formatter = new string_field_formatter(format::FORMAT_PLAIN, $section->activity->get_context());

            $data[] = get_string(
                'activity_name_with_section_name',
                'performelement_redisplay',
                (object) [
                    'activity_name' => $formatter->format($section->activity->name),
                    'section_name' => $formatter->format($section->display_title),
                ]
            );
        }

        return $data;
    }
}
