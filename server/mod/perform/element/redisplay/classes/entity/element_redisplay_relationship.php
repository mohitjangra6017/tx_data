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

namespace performelement_redisplay\entity;

use core\orm\entity\entity;
use core\orm\entity\relations\belongs_to;
use mod_perform\entity\activity\element;

/**
 * Element redisplay relationship entity
 *
 * Properties:
 * @property-read int $id ID
 * @property int $source_activity_id ID of the activity that the selected element belongs to
 * @property int $source_section_element_id ID of the selected section element
 * @property int $redisplay_element_id ID of the redisplay element
 *
 * Relationships:
 * @property-read element $element
 *
 * @package performelement_redisplay\entity
 *
 */
class element_redisplay_relationship extends entity {
    public const TABLE = 'perform_element_redisplay_relationship';

    /**
     * Each element redisplay relationship belongs to a specific redisplay element
     *
     * @return belongs_to
     */
    public function section_element(): belongs_to {
        return $this->belongs_to(element::class, 'redisplay_element_id', 'id');
    }
}
