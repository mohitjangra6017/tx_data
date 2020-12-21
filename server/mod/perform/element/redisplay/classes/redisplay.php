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
 * @package performelement_redisplay
 */

namespace performelement_redisplay;

use coding_exception;
use mod_perform\models\activity\element as element_model;
use mod_perform\models\activity\element_plugin;
use mod_perform\models\activity\helpers\element_clone_helper;
use performelement_redisplay\data_provider\redisplay_data;
use performelement_redisplay\models\element_redisplay_relationship;
use performelement_redisplay\models\helpers\redisplay_element_clone;

class redisplay extends element_plugin {

    /**
     * @inheritDoc
     */
    public function get_sortorder(): int {
        return 90;
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
    public function has_title(): bool {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function get_title_text(): string {
        return get_string('instruction_text', 'performelement_redisplay');
    }

    /**
     * @inheritDoc
     */
    public function is_title_required(): bool {
        return false;
    }

    /**
     * Modify json data to add extra information to it.
     *
     * @param string|null $data
     * @return string|null
     */
    public function process_data(?string $data): ?string {
        if (empty($data)) {
            return $data;
        }
        $modified_data = (new redisplay_data())->include_extra_info(json_decode($data, true));

        return json_encode($modified_data);
    }

    /**
     * Create element redisplay relationship
     *
     * @param element_model $element
     * @throws coding_exception
     */
    public function post_create(element_model $element): void {
        $data = json_decode($element->data, true);
        $source_activity_id = $data['activityId'];
        $source_section_element_id = $data['sectionElementId'];

        if (isset($source_activity_id) && isset($source_section_element_id)) {
            element_redisplay_relationship::create($source_activity_id, $source_section_element_id, $element->id);
        }
    }

    /**
     * Update element redisplay relationship
     *
     * @param element_model $element
     * @throws coding_exception
     */
    public function post_update(element_model $element): void {
        $data = json_decode($element->data, true);
        $source_activity_id = $data['activityId'];
        $source_section_element_id = $data['sectionElementId'];

        if (isset($source_activity_id) && isset($source_section_element_id)) {
            element_redisplay_relationship::update($source_activity_id, $source_section_element_id, $element->id);
        }
    }

    /**
     * @inheritDoc
     */
    public function get_participant_print_component(): string {
        return $this->get_participant_form_component();
    }

    /**
     * @inheritDoc
     */
    public function get_clone_helper(): ?element_clone_helper {
        return new redisplay_element_clone();
    }
}