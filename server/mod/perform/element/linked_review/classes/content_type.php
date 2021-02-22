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
 * @package performelement_linked_review
 */

namespace performelement_linked_review;

interface content_type {

    /**
     * The unique name identifier of this content type.
     *
     * @return string
     */
    public static function get_identifier(): string;

    /**
     * The display name of this content type.
     * Shown when selecting it from menus etc.
     *
     * @return string
     */
    public static function get_display_name(): string;

    /**
     * Get the database table that the content ID is a foreign key for.
     *
     * @return string
     */
    public static function get_table_name(): string;

    /**
     * Is this content type enabled?
     *
     * @return bool
     */
    public static function is_enabled(): bool;

    /**
     * The component path of the vue component for rendering on the admin view.
     *
     * @return string
     */
    public static function get_admin_view_component(): string;

    /**
     * The component path of the vue component for allowing admins to configure extra settings. (Optional)
     *
     * @return string|null
     */
    public static function get_admin_settings_component(): ?string;

    /**
     * Array of available settings that can be configured by the admin (keys) and their default values (values)
     *
     * Example: ['allow_rating' => true, 'show_description' => false]
     *
     * @return array
     */
    public static function get_available_settings(): array;

    /**
     * The component path of the vue component for picking the content items.
     *
     * @return string
     */
    public static function get_participant_picker_component(): string;

    /**
     * The component path of the vue component for rendering the content response display.
     *
     * @return string
     */
    public static function get_participant_content_component(): string;

}
