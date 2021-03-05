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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Murali Nair <murali.nair@totaralearning.com>
 * @package performelement_linked_review
 */

namespace performelement_linked_review\entity;

use core\orm\entity\repository;

class linked_review_content_repository extends repository {
    /**
     * Returns the no of records matching the specified content type and ids.
     *
     * @param string content_type content type to look up.
     * @param int[] content_ids list of content ids to further filter the result
     *
     * @return int the matching record count.
     */
    public function get_content_count_for_type(
        string $content_type,
        array $content_ids = []
    ): int {
        if (!empty($content_ids)) {
             $this->where('content_id', $content_ids);
        }

        return $this
            ->where('content_type', $content_type)
            ->count();
    }
}
