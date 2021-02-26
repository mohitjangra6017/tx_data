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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package performelement_linked_review
 */

namespace performelement_linked_review;

use coding_exception;
use core\collection;
use mod_perform\entity\activity\element as element_entity;
use mod_perform\models\activity\element;
use mod_perform\models\activity\respondable_element_plugin;
use totara_core\entity\relationship;

class linked_review extends respondable_element_plugin {

    /**
     * Get the content type definition class for the element.
     *
     * @param element $element
     * @return string|content_type
     */
    public function get_content_type(element $element): string {
        $data = json_decode($element->data, true);
        return content_type_factory::get_class_name_from_identifier($data['content_type']);
    }

    /**
     * Get the settings for
     *
     * @param element $element
     * @return array
     */
    public function get_content_settings(element $element): array {
        $data = json_decode($element->data, true);
        return $data['content_type_settings'];
    }

    /**
     * @inheritDoc
     */
    public function validate_response(
        ?string $encoded_response_data,
        ?element $element,
        $is_draft_validation = false
    ): collection {
        // TODO
        return new collection();
    }

    /**
     * Pull the answer text string out of the encoded json data.
     *
     * @param string|null $encoded_response_data
     * @param string|null $encoded_element_data
     * @return string|null
     */
    public function decode_response(?string $encoded_response_data, ?string $encoded_element_data): ?string {
        return ''; // TODO
    }

    /**
     * @inheritDoc
     */
    public function validate_element(element_entity $element) {
        parent::validate_element($element);

        $data = json_decode($element->data, true);

        if (empty($data)) {
            throw new coding_exception('No additional data was specified when saving the element with ID ' . $element->id);
        }

        $this->validate_content_type_and_settings($data);
    }

    /**
     * Validate the settings saved for the element.
     *
     * @param array $data Element data
     */
    private function validate_content_type_and_settings(array $data): void {
        $supported_keys = ['content_type', 'content_type_settings', 'selection_relationships'];
        $saved_keys = array_keys($data);
        if ($supported_keys != $saved_keys) {
            throw new coding_exception(
                'The saved data must contain and only contain these keys: ' . json_encode($supported_keys) .
                ', but the following keys were specified: ' . json_encode($saved_keys)
            );
        }

        $content_type = content_type_factory::get_class_name_from_identifier($data['content_type']);
        $available_settings = $content_type::get_available_settings();
        $saved_settings = $data['content_type_settings'] ?? [];

        // Check if the settings specified by the front end are actually supported.
        $invalid_saved_settings = array_diff(array_keys($saved_settings), array_keys($available_settings));
        if (!empty($invalid_saved_settings)) {
            throw new coding_exception(
                'Invalid setting(s) keys were saved: ' . json_encode($invalid_saved_settings) .
                '. Supported settings: ' . json_encode(array_keys($available_settings))
            );
        }

        $selection_relationship_ids = $data['selection_relationships'];
        if (empty($selection_relationship_ids)) {
            throw new coding_exception('No selection relationship IDs were specified.');
        }
        foreach ($selection_relationship_ids as $relationship_id) {
            if (!relationship::repository()->where('id', $relationship_id)->exists()) {
                throw new coding_exception('Invalid selection relationship ID specified: ' . $relationship_id);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function post_create(element $element): void {
        $data = json_decode($element->data, true);

        $data = $this->set_content_type_and_settings($data);

        $element->set_data(json_encode($data));
    }

    /**
     * Set any missing settings with their respective defaults.
     *
     * @param array $data Element data
     * @return array Element data with the corrected settings.
     */
    private function set_content_type_and_settings(array $data): array {
        $content_type = content_type_factory::get_class_name_from_identifier($data['content_type']);

        $available_settings = $content_type::get_available_settings();
        $saved_settings = $data['content_type_settings'] ?? [];
        $settings = array_merge($available_settings, $saved_settings);

        return array_merge($data, ['content_type_settings' => $settings]);
    }

    /**
     * @inheritDocs
     */
    public function process_data(?string $data): ?string {
        $data = json_decode($data, true);

        $content_type = content_type_factory::get_class_name_from_identifier($data['content_type']);

        $components = [
            'admin_settings' => $content_type::get_admin_settings_component(),
            'admin_view' => $content_type::get_admin_view_component(),
            'content_picker' => $content_type::get_content_picker_component(),
            'participant_content' => $content_type::get_participant_content_component(),
        ];

        return json_encode(array_merge($data, ['components' => $components]));
    }

    /**
     * @inheritDoc
     */
    public function get_participant_response_component(): string {
        // TODO may need a special response display element
        return 'mod_perform/components/element/participant_form/HtmlResponseDisplay';
    }

    /**
     * @inheritDoc
     */
    public function get_sortorder(): int {
        return 1000; // TODO: Change this
    }

    /**
     * @inheritDoc
     */
    public function format_response_lines(?string $encoded_response_data, ?string $encoded_element_data): array {
        // The response is displayed as HTML instead of individual lines, so nothing is returned here.
        return [];
    }

    /**
     * @inheritDocs
     */
    public function supports_child_elements(): bool {
        return true;
    }
}
