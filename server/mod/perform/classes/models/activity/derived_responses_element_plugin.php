<?php
/*
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Jaron Steenson <jaron.steenson@totaralearning.com>
 * @package mod_perform
 */

namespace mod_perform\models\activity;

use core\orm\query\builder;
use mod_perform\entity\activity\element_response as element_response_entity;
use mod_perform\entity\activity\participant_section as participant_section_entity;
use mod_perform\entity\activity\section_element_reference as section_element_reference_entity;
use mod_perform\entity\activity\section_element as section_element_entity;
use mod_perform\entity\activity\element as element_entity;
use mod_perform\models\activity\helpers\displays_responses;
use mod_perform\state\participant_section\complete;
use performelement_aggregation\aggregation;
use performelement_aggregation\calculation_method;

/**
 * Class derived_responses_element_plugin
 *
 * Base class for defining elements that derive or calculate the responses
 * rather than accepting them from the users directly.
 *
 * @package mod_perform\models\activity
 */
abstract class derived_responses_element_plugin extends element_plugin implements displays_responses {

    /**
     * Format a response into lines ready to be displayed.
     *
     * @param string|null $encoded_response_data
     * @param string|null $encoded_element_data
     * @return string[]
     */
    abstract public function format_response_lines(?string $encoded_response_data, ?string $encoded_element_data): array;

    /**
     * This method return element's user form vue component name.
     *
     * @return string
     */
    public function get_participant_response_component(): string {
        return 'mod_perform/components/element/participant_form/ResponseDisplay';
    }

    /**
     * @inheritDoc
     */
    public function has_title(): bool {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function get_title_text(): string {
        return get_string('element_title', 'mod_perform');
    }

    /**
     * @inheritDoc
     */
    public function is_title_required(): bool {
        return true;
    }

    public static function calculate_derived_response_for_participant_section(participant_section_entity $participant_section): void {
        // TODO: For performance it may be better to only calculate the average for the referencing elements in the same section
        //       and queue any other for later processing

        $referencing_elements = section_element_reference_entity::repository()
            ->as('ref')
            ->join([section_element_entity::TABLE, 'sse'], 'sse.id', 'ref.source_section_element_id')
            ->where('sse.section_id', $participant_section->section_id)
            ->order_by('ref.referencing_element_id')
            ->get();

        $referencing_element_ids = array_unique($referencing_elements->pluck('referencing_element_id'));
        if (empty($referencing_element_ids)) {
            return;
        }
        
        foreach ($referencing_element_ids as $referencing_element_id) {
            $belonging_elements = $referencing_elements->filter(function ($item) use ($referencing_element_id) {
                return $item->referencing_element_id == $referencing_element_id;
            });

            $source_section_element_ids = $belonging_elements->pluck('source_section_element_id');
            self::calculate_derived_for_participant($referencing_element_id, $participant_section->participant_instance_id, $source_section_element_ids);
        }
    }

    /**
     * @param int $referencing_element_id
     * @param int $participant_instance_id
     * @param int[] $source_section_element_ids
     */
    protected static function calculate_derived_for_participant(int $referencing_element_id, int $participant_instance_id, array $source_section_element_ids): void {
        $derived_element_model = element::load_by_entity(new element_entity($referencing_element_id));
        $data = json_decode($derived_element_model->get_data(), true);
        // TODO move down to aggregation
        $methods = $data[aggregation::CALCULATIONS];

        // TODO Possible to reduce / simplify queries??
        
        $responses = element_response_entity::repository()
            ->as('r')
            ->join([section_element_entity::TABLE, 'se'], 'se.id', 'r.section_element_id')
            ->join([participant_section_entity::TABLE, 'ps'], function (builder $builder) use ($participant_instance_id) {
                $builder->where_field('ps.section_id', 'se.section_id')
                    ->where('ps.participant_instance_id', $participant_instance_id)
                    ->where('ps.progress', complete::get_code());
            })
            ->where('r.participant_instance_id', $participant_instance_id)
            ->where_in('r.section_element_id', $source_section_element_ids)
            ->get();

        // Now aggregate the values
        $to_aggregate = [];
        foreach ($responses as $response) {
            $section_element = new section_element_entity($response->section_element_id);
            $source_element_model = element::load_by_entity($section_element->element);
            $source_element = $source_element_model->get_element_plugin();
            $value = $source_element->get_aggregatable_value($response->response_data, $source_element_model->get_data());
            if ($value !== null) {
                // TODO: Excluded values
                $to_aggregate[] = $value;
            }
        }

        $response = [];
        foreach ($methods as $method) {
            $calculation = calculation_method::load_by_method($method);
            $response[$method] = $calculation->aggregate($to_aggregate);
        }

        $response = json_encode($response);
        $referencing_section_element_ids = section_element_entity::repository()
            ->where('element_id', $referencing_element_id)
            ->get()
            ->pluck('id');
        foreach ($referencing_section_element_ids as $referencing_section_element_id) {
            $aggregated_responses = element_response_entity::repository()
                ->where('participant_instance_id', $participant_instance_id)
                ->where('section_element_id', $referencing_section_element_id)
                ->get();
            $aggregated_response = $aggregated_responses->first();
            if (empty($aggregated_response)) {
                $aggregated_response = new element_response_entity();
                $aggregated_response->participant_instance_id = $participant_instance_id;
                $aggregated_response->section_element_id = $referencing_section_element_id;
            }
            $aggregated_response->response_data = $response;
            $aggregated_response->save();
        }
    }

    /**
     * Return true if element has reporting id
     *
     * @return bool
     */
    public function has_reporting_id(): bool {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function is_response_required_enabled(): bool {
        return false;
    }

}
