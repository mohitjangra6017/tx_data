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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Kunle Odusan <kunle.odusan@totaralearning.com>
 */

namespace performelement_redisplay\data_provider;

use coding_exception;
use mod_perform\entity\activity\section_element;
use mod_perform\models\activity\section_element as section_element_model;

class redisplay_data {

    /**
     * Adds extra info to redisplay element data.
     *
     * @param array $redisplay_settings
     * @return array
     */
    public function include_extra_info(array $redisplay_settings): array {
        /** @var $section_element_entity section_element */
        $section_element_entity = section_element::repository()
            ->where('id', $redisplay_settings['sectionElementId'])
            ->with(
                [
                    'element',
                    'section.activity',
                ]
            )
            ->one();

        if (empty($section_element_entity)) {
            throw new coding_exception('section element does not exist');
        }
        $section_element = section_element_model::load_by_entity($section_element_entity);

        $redisplay_settings['activityId'] = $section_element->section->activity->id;
        $redisplay_settings['activityName'] = $section_element->section->activity->name;
        $redisplay_settings['activityStatus'] = $section_element->section->activity->get_state_details()::get_display_name();
        $redisplay_settings['elementTitle'] = $section_element->element->title;
        $redisplay_settings['elementPluginName'] = $section_element->element->get_element_plugin()->get_name();

        $relationships = $section_element->section->activity->anonymous_responses
            ? $this->get_anonymous_relationship_string()
            : $this->get_relationships($section_element->section->get_answering_section_relationships());
        $redisplay_settings['relationships'] = $relationships;

        return $redisplay_settings;
    }

    /**
     * Get relationship string for answerable section relationships.
     *
     * @param $section_relationships
     * @return string
     */
    private function get_relationships($section_relationships): string {
        $relationships = $section_relationships
            ->map(function ($section_relationship) {
                return $section_relationship->core_relationship;
            })
            ->sort('sort_order')
            ->pluck('name');

        if (empty($relationships)) {
            return get_string('no_responding_relationships', 'performelement_redisplay');
        }

        return get_string(
            'responses_from_relationships',
            'performelement_redisplay',
            (object) [
                'relationships' => implode(', ', $relationships)
            ]
        );
    }

    /**
     * Get relationship string for anonymized activity.
     *
     * @return string
     */
    private function get_anonymous_relationship_string(): string {
        return get_string('responses_from_anonymous_relationships', 'performelement_redisplay');
    }
}
