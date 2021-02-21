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
 * @package totara_competency
 */

namespace totara_competency\webapi\resolver\query;

use core\orm\collection;
use core\webapi\middleware\require_advanced_feature;
use performelement_linked_review\webapi\resolver\query\content_items;
use totara_competency\data_providers\assignments;
use totara_competency\entity\assignment as assignment_entity;
use totara_competency\models\assignment as assignment_model;
use totara_competency\models\profile\proficiency_value;

class perform_linked_competencies extends content_items {

    /**
     * @inheritDoc
     */
    protected static function query_content(int $user_id, collection $content): array {
        $created_at = $content->first() ? $content->first()->created_at : null;

        return assignments::for($user_id)
            ->set_filters([
                'ids' => $content->pluck('content_id'),
            ])
            ->fetch()
            ->get()
            ->map(static function (assignment_entity $assignment) use ($user_id, $created_at) {
                return [
                    'progress' => assignment_model::load_by_entity($assignment),
                    'achievement' => proficiency_value::value_at_timestamp($assignment, $user_id, $created_at)
                ];
            })
            ->all();
    }

    /**
     * @inheritDoc
     */
    public static function get_middleware(): array {
        return array_merge(parent::get_middleware(), [
            new require_advanced_feature('competency_assignment'),
        ]);
    }

}
