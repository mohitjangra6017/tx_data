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
 * @author Angela Kuznetsova <angela.kuznetsova@totaralearning.com>
 * @package performelement_static_content
 */

namespace performelement_static_content;

use mod_perform\models\activity\element_plugin;

class static_content extends element_plugin {
    /**
     * This method return element's user form vue component name
     * @return string
     */
    public function get_participant_form_component(): string {
        return $this->get_component_path('Participant');
    }

    /**
     * This method return element's user form vue component name
     * @return string
     */
    public function get_participant_response_component(): string {
        return $this->get_component_path('Participant');
    }

    /**
     * @inheritDoc
     */
    public function get_group(): int {
        return self::GROUP_OTHER;
    }

    /**
     * @inheritDoc
     */
    public function get_sortorder(): int {
        return 80;
    }
}
