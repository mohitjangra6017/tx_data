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

namespace performelement_linked_review\webapi\resolver\mutation;

use core\webapi\execution_context;
use core\webapi\middleware\require_advanced_feature;
use core\webapi\middleware\require_login;
use core\webapi\mutation_resolver;
use core\webapi\resolver\has_middleware;
use core_user;
use mod_perform\models\activity\section_element;
use mod_perform\webapi\middleware\require_activity;
use moodle_exception;
use performelement_linked_review\models\linked_review_content;
use totara_core\relationship\relationship;

class update_linked_review_content implements mutation_resolver, has_middleware {

    /**
     * @inheritDoc
     */
    public static function resolve(array $args, execution_context $ec) {
        $content_ids = $args['input']['content_ids'];
        $section_element_id = $args['input']['section_element_id'];
        $participant_instance_id = $args['input']['participant_instance_id'];

        $linked_review_contents = linked_review_content::load_by_section_element_and_participant_instance(
            $section_element_id, $participant_instance_id
        );

        $can_update = false;
        $description = '';
        if ($linked_review_contents->count() == 0) {
            // Currently you can only create the content items once per instance for a linked review question
            linked_review_content::create_multiple($content_ids, $section_element_id, $participant_instance_id);
            $can_update = true;
        } else {
            $user = core_user::get_user($linked_review_contents->first()->selector_id, '*', MUST_EXIST);
            $relationship_name = self::get_relationship_name($section_element_id);
            $description = get_string(
                'can_not_select_content_message', 'performelement_linked_review',
                ['selector' => fullname($user), 'relationship' => $relationship_name]
            );
        }

        return [
            'validation_info' => [
                'can_update'  => $can_update,
                'description' => $description,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function get_middleware(): array {
        return [
            new require_advanced_feature('performance_activities'),
            new require_login(),
            require_activity::by_participant_instance_id('input.participant_instance_id', true),
        ];
    }

    /**
     * Get relationship name which selected review contents
     *
     * @param $section_element_id
     * @return string
     * @throws moodle_exception
     */
    private static function get_relationship_name($section_element_id): string {
        $element = section_element::load_by_id($section_element_id)->element;
        $element_data = json_decode($element->get_data(), true);
        $selection_relationships = $element_data['selection_relationships'] ?? null;
        if (empty($selection_relationships)) {
            throw new moodle_exception("can not find selection relationships");
        }
        return relationship::load_by_id($selection_relationships[0])->get_name();
    }

}
