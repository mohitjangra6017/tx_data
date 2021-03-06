<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2018 onwards Totara Learning Solutions LTD
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
 * @author Samantha Jayasinghe <samantha.jayasinghe@totaralearning.com>
 * @package core_course
 * @category totara_catalog
 */

namespace core_course\totara_catalog\course\observer;

defined('MOODLE_INTERNAL') || die();

use container_course\course;
use totara_catalog\observer\object_update_observer;

/**
 * update course catalog data based on removed tags
 */
class tag_removed extends object_update_observer {

    public function get_observer_events(): array {
        return [
            '\core\event\tag_removed'
        ];
    }

    /**
     * init all course update objects for removed tag id
     */
    protected function init_change_objects(): void {
        global $DB;
        $data = new \stdClass();

        $eventdata = $this->event->get_data();

        if ($eventdata['other']['itemtype'] == 'course') {
            $object_id = $eventdata['other']['itemid'];

            // The `itemid` in this case is pointing to the course's id. However, within PHPUnit evironment,
            // sometimes the 'itemid' is being given randomly which is not even existing in the system.
            $course = $DB->get_record('course', ['id' => $object_id], 'id, containertype');

            if ($course && course::get_type() === $course->containertype) {
                return;
            }

            $data->objectid = $eventdata['other']['itemid'];
            $data->contextid = $this->event->contextid;
            $this->register_for_update($data);
        }
    }
}
