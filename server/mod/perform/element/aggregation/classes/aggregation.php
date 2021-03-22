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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Jaron Steenson <jaron.steenson@totaralearning.com>
 * @package performelement_aggregation
 */
namespace performelement_aggregation;

use coding_exception;
use mod_perform\entity\activity\element as element_entity;
use mod_perform\entity\activity\section_element as section_element_entity;
use mod_perform\models\activity\derived_responses_element_plugin;
use mod_perform\models\activity\section_element;
use mod_perform\models\activity\element;
use mod_perform\models\activity\element as element_model;
use mod_perform\models\activity\section_element_reference;
use performelement_aggregation\data_provider\aggregation_data;

class aggregation extends derived_responses_element_plugin {

    /**
     * @string The serialized key for a reference element's (with multiple sources) source section element ids.
     */
    public const SOURCE_SECTION_ELEMENT_IDS = 'sourceSectionElementIds';

    /**
     * @inheritDoc
     */
    public function get_sortorder(): int {
        return 100;
    }

    /**
     * @inheritDoc
     */
    public function validate_element(element_entity $element): void {
        $data = json_decode($element->data, true, 512, JSON_THROW_ON_ERROR);

        if (!array_key_exists(self::SOURCE_SECTION_ELEMENT_IDS, $data) ||
            $data[self::SOURCE_SECTION_ELEMENT_IDS] === null ||
            count($data[self::SOURCE_SECTION_ELEMENT_IDS]) === 0
        ) {
            throw new coding_exception(self::SOURCE_SECTION_ELEMENT_IDS . ' must be specified in the element data field');
        }

        $source_section_element_ids = array_unique($data[self::SOURCE_SECTION_ELEMENT_IDS]);
        $source_section_elements = section_element_reference::get_source_section_elements($source_section_element_ids);

        if (count($source_section_element_ids) !== count($source_section_elements)) {
            throw new coding_exception('Not all supplied source section elements exist');
        }

        foreach ($source_section_elements as $source_section_element) {
            if (!$source_section_element->get_element()->get_element_plugin()->get_is_aggregatable()) {
                throw new coding_exception(
                    "The supplied source section elements are not all aggregatable: \"{$source_section_element->get_element()->title}\" is not aggregatable"
                );
            }
        }

        foreach ($source_section_elements as $source_section_element) {
            $this->ensure_source_section_element_is_in_referencing_activity($element, $source_section_element);
        }
    }

    /**
     * @inheritDoc
     */
    public function get_group(): int {
        return self::GROUP_OTHER;
    }

    public function process_data(element_entity $element): ?string {
        $modified_data = (new aggregation_data())->include_extra_info($element->id);

        return json_encode($modified_data, JSON_THROW_ON_ERROR);
    }

    /**
     * @inheritDoc
     */
    public function post_create(element_model $element): void {
        $data = json_decode($element->get_raw_data(), true, 512, JSON_THROW_ON_ERROR);
        $source_section_element_ids = $data[self::SOURCE_SECTION_ELEMENT_IDS];

        foreach ($source_section_element_ids as $source_section_element_id) {
            section_element_reference::create($source_section_element_id, $element->id);
        }

        // Strip this from the data, otherwise it can become incorrect if an element/activity is cloned.
        // We can safely do this because all data for this type of element is saved in the section_element_reference table.
        $element->clear_data();
    }

    /**
     * @inheritDoc
     */
    public function post_update(element_model $element): void {
        $data = json_decode($element->get_raw_data(), true, 512, JSON_THROW_ON_ERROR);
        $source_section_element_id = $data[self::SOURCE_SECTION_ELEMENT_IDS];

        section_element_reference::patch_multiple($source_section_element_id, $element->id);

        // Strip this from the data, otherwise it can become incorrect if an element/activity is cloned.
        // We can safely do this because all data for this type of element is saved in the section_element_reference table.
        $element->clear_data();
    }

    protected function ensure_source_section_element_is_in_referencing_activity(element_entity $element, section_element $source_section_element): void {
        $source_activity_id = $source_section_element->section->activity_id;

        // This breaks down if/when we implement question banks (re-usable abstract element configurations).
        /** @var section_element[] $referencing_section_elements */
        $referencing_section_elements = section_element_entity::repository()
            ->where('element_id', $element->id)
            ->with('section')
            ->get();

        foreach ($referencing_section_elements as $referencing_section_element) {
            $referencing_activity_id = $referencing_section_element->section->activity_id;

            if ((int) $referencing_activity_id !== (int) $source_activity_id) {
                throw new coding_exception(
                    'Source section elements must be from the same activity as a referencing aggregation element'
                );
            }
        }
    }

    public function format_response_lines(?string $encoded_response_data, ?string $encoded_element_data): array {
        // TODO: Implement format_response_lines() method.
    }

}