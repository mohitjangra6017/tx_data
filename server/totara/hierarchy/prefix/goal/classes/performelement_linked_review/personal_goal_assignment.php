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
 * @author Matthias Bonk <matthias.bonk@totaralearning.com>
 * @package hierarchy_goal
 */

namespace hierarchy_goal\performelement_linked_review;

use core\collection;
use hierarchy_goal\entity\personal_goal as personal_goal_entity;
use mod_perform\entity\activity\participant_section as participant_section_entity;
use mod_perform\models\activity\subject_instance;

class personal_goal_assignment extends goal_assignment_content_type {

    /**
     * @inheritDoc
     */
    public static function get_identifier(): string {
        return 'personal_goal';
    }

    /**
     * @inheritDoc
     */
    public static function get_display_name(): string {
        return get_string('personalgoal', 'totara_hierarchy');
    }

    /**
     * @inheritDoc
     */
    public static function get_table_name(): string {
        return personal_goal_entity::TABLE;
    }

    /**
     * @inheritDoc
     */
    public function load_content_items(
        subject_instance $subject_instance,
        collection $content_items,
        ?participant_section_entity $participant_section,
        bool $can_view_other_responses,
        int $created_at
    ): array {
        // TODO TL-29506
        return [];
    }
}
