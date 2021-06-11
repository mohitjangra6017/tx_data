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

use core\format;
use performelement_linked_review\content_type;
use performelement_linked_review\rb\helper\content_type_response_report;
use totara_core\advanced_feature;
use totara_core\relationship\relationship;
use totara_core\relationship\relationship as relationship_model;

abstract class goal_assignment_content_type extends content_type {

    /**
     * The format type to use when formatting strings for output.
     */
    protected const TEXT_FORMAT = format::FORMAT_PLAIN;

    /**
     * @inheritDoc
     */
    public static function is_enabled(): bool {
        return advanced_feature::is_enabled('goals');
    }

    /**
     * @inheritDoc
     */
    public static function get_admin_settings_component(): ?string {
        return 'hierarchy_goal/components/performelement_linked_review/AdminEdit';
    }

    /**
     * @inheritDoc
     */
    public static function get_available_settings(): array {
        return [
            'enable_status_change' => false,
            'status_change_relationship' => null,
        ];
    }

    /**
     * @param array $settings
     * @return array
     */
    public static function get_display_settings(array $settings): array {
        $display_settings = [];

        $status_change_enabled = $settings['enable_status_change'] ?? false;
        $display_settings[get_string('enable_goal_status_change', 'hierarchy_goal')] = $status_change_enabled
            ? get_string('yes', 'core')
            : get_string('no', 'core');

        if ($status_change_enabled && !empty($settings['status_change_relationship'])) {
            $display_settings[get_string('enable_goal_status_change_participant', 'hierarchy_goal')] =
                relationship_model::load_by_id($settings['status_change_relationship'])->get_name();
        }

        return $display_settings;
    }

    /**
     * Append the actual human readable name of the status changing relationship if changing status is enabled.
     *
     * @param array $content_type_settings
     * @return array
     */
    public static function get_content_type_settings(array $content_type_settings): array {
        if (empty($content_type_settings['status_change_relationship']) || !$content_type_settings['enable_status_change']) {
            return $content_type_settings;
        }

        $relationship = relationship::load_by_id($content_type_settings['status_change_relationship']);
        $content_type_settings['status_change_relationship_name'] = $relationship->get_name();

        return $content_type_settings;
    }

    /**
     * Remove/clean any unwanted settings attributes before saving.
     *
     * @param array $content_type_settings
     * @return array
     */
    public static function clean_content_type_settings(array $content_type_settings): array {
        if ($content_type_settings['enable_status_change'] === false) {
            $content_type_settings['status_change_relationship'] = null;
        }
        unset($content_type_settings['status_change_relationship_name']);

        return $content_type_settings;
    }

    /**
     * @inheritDoc
     */
    public static function get_content_picker_component(): string {
        return 'hierarchy_goal/components/performelement_linked_review/ParticipantContentPicker';
    }

    /**
     * @inheritDoc
     */
    public static function get_participant_content_component(): string {
        return 'hierarchy_goal/components/performelement_linked_review/ParticipantContent';
    }

    /**
     * @inheritDoc
     */
    public static function get_admin_view_component(): string {
        return 'hierarchy_goal/components/performelement_linked_review/AdminView';
    }

    /**
     * @inheritDoc
     */
    public static function get_participant_content_footer_component(): string {
        return 'hierarchy_goal/components/ChangeStatusForm';
    }

    /**
     * @inheritDoc
     */
    public static function get_admin_content_footer_component(): string {
        return 'hierarchy_goal/components/ChangeStatusFormPreview';
    }

    /**
     * @inheritDoc
     */
    public static function get_response_report_helper(): content_type_response_report {
        return new response_report();
    }
}
