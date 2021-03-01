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
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package performelement_linked_review
 */

namespace performelement_linked_review\entity;

use core\orm\entity\entity;
use core\orm\entity\relations\belongs_to;
use mod_perform\entity\activity\element;
use mod_perform\entity\activity\participant_instance;

/**
 * Properties:
 * @property int $linked_review_content_id
 * @property int $element_id The child element that this is a response to
 * @property int $participant_instance_id
 * @property string $response_data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property-read linked_review_content $linked_review_content
 * @property-read participant_instance $participant_instance
 * @property-read element $element
 *
 * @package performelement_linked_reivew\entity
 */
class linked_review_content_response extends entity {

    public const TABLE = 'perform_element_linked_review_content_response';

    public const CREATED_TIMESTAMP = 'created_at';

    public const UPDATED_TIMESTAMP = 'updated_at';

    public const SET_UPDATED_WHEN_CREATED = true;

    /**
     * The linked content this is a response to.
     *
     * @return belongs_to
     */
    public function linked_review_content(): belongs_to {
        return $this->belongs_to(linked_review_content::class, 'linked_review_content_id');
    }

    /**
     * Participant instance that made the response.
     *
     * @return belongs_to
     */
    public function participant_instance(): belongs_to {
        return $this->belongs_to(participant_instance::class, 'participant_instance_id');
    }

    /**
     * The specific child element for this response.
     *
     * @return belongs_to
     */
    public function element(): belongs_to {
        return $this->belongs_to(element::class, 'element_id');
    }

}
