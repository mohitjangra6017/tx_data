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
 * @author Marco Song <marco.song@totaralearning.com>
 * @package performelement_linked_review
 */

namespace performelement_linked_review\entity;

use core\orm\entity\entity;
use core\orm\entity\relations\belongs_to;
use mod_perform\entity\activity\section_element;

/**
 * Element subject instance review content entity
 *
 * Properties:
 * @property-read int $id ID
 * @property int $section_element_id
 * @property int $participant_instance_id
 * @property int $content_id
 * @property int $created_at
 *
 * @package performelement_linked_reivew\entity
 */
class linked_review_content extends entity {

    public const TABLE = 'perform_element_linked_review_content';

    public const CREATED_TIMESTAMP = 'created_at';

}
