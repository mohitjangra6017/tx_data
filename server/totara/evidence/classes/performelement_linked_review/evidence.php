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
 * @package mod_perform
 */

namespace totara_evidence\performelement_linked_review;

use performelement_linked_review\content_type;
use totara_core\advanced_feature;
use totara_evidence\entity\evidence_item;

class evidence extends content_type {

    /**
     * @inheritDoc
     */
    public static function get_identifier(): string {
        return 'totara_evidence';
    }

    /**
     * @inheritDoc
     */
    public static function get_display_name(): string {
        return get_string('pluginname', 'totara_evidence');
    }

    /**
     * @inheritDoc
     */
    public static function is_enabled(): bool {
        return advanced_feature::is_enabled('evidence');
    }

    /**
     * @inheritDoc
     */
    public static function get_table_name(): string {
        return evidence_item::TABLE;
    }

    /**
     * @inheritDoc
     */
    public static function get_admin_view_component(): string {
        return 'totara_evidence/components/performelement_linked_review/AdminView';
    }

    /**
     * @inheritDoc
     */
    public static function get_admin_settings_component(): ?string {
        return null;
    }

    /**
     * @inheritDoc
     */
    public static function get_available_settings(): array {
        // TODO: This is a placeholder, we need to work out what settings we actually want in the future.
        return [];
    }

    /**
     * @param array $settings
     * @return array
     */
    public static function get_display_settings(array $settings): array {
        return [];
    }


    /**
     * @inheritDoc
     */
    public static function get_participant_content_component(): string {
        return 'totara_evidence/components/performelement_linked_review/ParticipantContent';
    }

    /**
     * @inheritDoc
     */
    public static function get_content_picker_component(): string {
        return 'totara_evidence/components/performelement_linked_review/ParticipantContentPicker';
    }

    /**
     * @inheritDoc
     */
    public function load_content_items(int $user_id, array $content_ids, int $created_at): array {
        return [];
    }
}
