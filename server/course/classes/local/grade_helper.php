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
 * @package core_course
 */
namespace core_course\local;

use core\orm\query\builder;

class grade_helper {
    /**
     * grade_helper constructor.
     * Prevent this class from instantiation.
     */
    private function __construct() {
    }

    /**
     * @param int $course_id
     * @return bool
     */
    public static function does_course_need_regrade(int $course_id): bool {
        $db = builder::get_db();
        return $db->record_exists('grade_items', ['courseid' => $course_id, 'needsupdate' => 1]);
    }
}