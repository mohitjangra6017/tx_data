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

use performelement_linked_review\content_type;
use totara_competency\entity\assignment;
use totara_core\advanced_feature;

class competency_assignment implements content_type {

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
     * @inheritDoc
     */
    public static function get_content_picker_component(): string {
        return 'totara_competency/components/performelement_linked_review/ParticipantPicker';
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

}
