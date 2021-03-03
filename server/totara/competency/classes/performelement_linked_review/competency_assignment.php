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

namespace totara_competency\performelement_linked_review;

use core\format;
use performelement_linked_review\content_type;
use totara_competency\data_providers\assignments;
use totara_competency\entity\assignment;
use totara_competency\entity\assignment as assignment_entity;
use totara_competency\formatter\assignment as assignment_formatter;
use totara_competency\formatter\competency as competency_formatter;
use totara_competency\formatter\profile\scale_value_progress;
use totara_competency\formatter\scale_value;
use totara_competency\models\assignment as assignment_model;
use totara_competency\models\profile\proficiency_value;
use totara_core\advanced_feature;

class competency_assignment extends content_type {

    /**
     * @inheritDoc
     */
    public static function get_identifier(): string {
        return 'totara_competency';
    }

    /**
     * @inheritDoc
     */
    public static function get_display_name(): string {
        return get_string('pluginname', 'totara_competency');
    }

    /**
     * @inheritDoc
     */
    public static function get_table_name(): string {
        return assignment::TABLE;
    }

    /**
     * @inheritDoc
     */
    public static function is_enabled(): bool {
        return advanced_feature::is_enabled('competency_assignment');
    }

    /**
     * @inheritDoc
     */
    public static function get_admin_settings_component(): ?string {
        return 'totara_competency/components/performelement_linked_review/AdminEdit';
    }

    /**
     * @inheritDoc
     */
    public static function get_available_settings(): array {
        // TODO: This is a placeholder, we need to work out what settings we actually want in the future.
        return [
            'show_rating' => true,
        ];
    }

    /**
     * @param array $settings
     * @return array
     */
    public static function get_display_settings(array $settings): array {
        $rating_setting = !empty($settings['show_rating'])
            ? get_string('perform_show_rating_enabled', 'totara_competency')
            : get_string('perform_show_rating_disabled', 'totara_competency');

        return [
            get_string('perform_show_rating', 'totara_competency') => $rating_setting
        ];
    }

    /**
     * @inheritDoc
     */
    public static function get_content_picker_component(): string {
        return 'totara_competency/components/performelement_linked_review/ParticipantContentPicker';
    }

    /**
     * @inheritDoc
     */
    public static function get_participant_content_component(): string {
        return 'totara_competency/components/performelement_linked_review/ParticipantContent';
    }

    /**
     * @inheritDoc
    */
    public static function get_admin_view_component(): string {
        return 'totara_competency/components/performelement_linked_review/AdminView';
    }

    /**
     * Load the competency assignments
     *
     * @param int $user_id
     * @param array $content_ids
     * @param int $created_at
     * @return array
     */
    public function load_content_items(int $user_id, array $content_ids, int $created_at): array {
        if (empty($content_ids) || advanced_feature::is_disabled('competency_assignment')) {
            return [];
        }

        return assignments::for($user_id)
            ->set_filters([
                'ids' => $content_ids,
            ])
            ->fetch()
            ->get()
            ->key_by('id')
            ->map(function (assignment_entity $assignment) use ($user_id, $created_at) {
                return $this->creat_result_item($assignment, $user_id, $created_at);
            })
            ->all(true);
    }

    /**
     * Create the data for one competency content item
     *
     * @param assignment_entity $assignment
     * @param int $user_id
     * @param int $created_at
     * @return array
     */
    private function creat_result_item(assignment_entity $assignment, int $user_id, int $created_at): array {
        $proficiency_value =proficiency_value::value_at_timestamp($assignment, $user_id, $created_at);
        $assignment_model = assignment_model::load_by_entity($assignment);

        $format = format::FORMAT_HTML;
        $competency_formatter = new competency_formatter($assignment_model->get_competency(), $this->context);
        $assignment_formatter = new assignment_formatter($assignment_model, $this->context);

        return [
            'id' => $assignment_model->get_id(),
            'competency' => [
                'id' => $assignment_model->get_competency()->id,
                'display_name' => $competency_formatter->format('display_name', $format),
                'description' => $competency_formatter->format('description', $format),
            ],
            'assignment' => [
                'reason_assigned' => $assignment_formatter->format('reason_assigned', $format),
            ],
            'achievement' => $this->format_proficiency_value($proficiency_value),
            'scale_values' => $this->format_scale_values($assignment_model),
        ];
    }

    /**
     * Format the proficiency_value, making sure the data runs through our formatters
     *
     * @param proficiency_value $achievement
     * @return array|null
     */
    private function format_proficiency_value(proficiency_value $achievement): array {
        $format = format::FORMAT_HTML;

        $scale_value_formatter = new scale_value_progress($achievement, $this->context);

        return [
            'id' => $achievement->id,
            'name' => $scale_value_formatter->format('name', $format),
            'proficient' => $scale_value_formatter->format('proficient', $format),
        ];
    }

    /**
     * Format the proficiency_value, making sure the data runs through our formatters
     *
     * @param assignment_model $assignment
     * @return array
     */
    private function format_scale_values(assignment_model $assignment): array {
        $format = format::FORMAT_HTML;

        $scale = $assignment->get_assignment_specific_scale();

        $result = [];
        foreach ($scale->values as $value) {
            $formatter = new scale_value($value, $this->context);
            $result[] = [
                'id' => $value->id,
                'name' => $formatter->format('name', $format),
                'proficient' => (bool) $value->proficient
            ];
        }
        return $result;
    }

}
