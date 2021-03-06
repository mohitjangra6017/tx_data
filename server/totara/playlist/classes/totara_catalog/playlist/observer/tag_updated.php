<?php
/*
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
 * @author David Curry <david.curry@totaralearning.com>
 * @package totara_playlist
 * @category totara_catalog
 */

namespace totara_playlist\totara_catalog\playlist\observer;

defined('MOODLE_INTERNAL') || die();

use totara_catalog\observer\object_update_observer;

/**
 * update catalog item data based on updated tags
 */
class tag_updated extends object_update_observer {

    public function get_observer_events(): array {
        return [
            '\core\event\tag_updated',
        ];
    }

    /**
     * init all update catalog items for updated tag
     */
    protected function init_change_objects(): void {
        global $DB;
        $data = new \stdClass();

        $eventdata = $DB->get_records(
            'tag_instance',
            ['itemtype' => 'playlist', 'id' => $this->event->objectid],
            '',
            'id, itemid, contextid'
        );

        foreach ($eventdata as $updatetag) {
            $data->objectid = $updatetag->itemid;
            $data->contextid = $updatetag->contextid;
            $this->register_for_update($data);
        }
    }
}
