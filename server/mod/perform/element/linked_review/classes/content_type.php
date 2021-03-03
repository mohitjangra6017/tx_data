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

use context;

/**
 * This is the base class for all linked review content types.
 *
 * Extend this class in another plugin to make a new type available.
 * Make sure all the functions required return valid values.
 *
 * The @see content_type::load_content_items() method has to return
 * the content which the VUE component @see content_type::get_participant_content_component()
 * uses to display the item.
 *
 * @package performelement_linked_review
 */
abstract class content_type {

    /**
     * @var context
     */
    protected $context;

    /**
     * @param context $context
     */
    public function __construct(context $context) {
        $this->context = $context;
    }

    /**
     * The unique name identifier of this content type.
     *
     * @return string
     */
    abstract public static function get_identifier(): string;

    /**
     * The display name of this content type.
     * Shown when selecting it from menus etc.
     *
     * @return string
     */
    abstract public static function get_display_name(): string;

    /**
     * Get the database table that the content ID is a foreign key for.
     *
     * @return string
     */
    abstract public static function get_table_name(): string;

    /**
     * Is this content type enabled?
     *
     * @return bool
     */
    abstract public static function is_enabled(): bool;

    /**
     * The component path of the vue component for rendering on the admin view.
     *
     * @return string
     */
    abstract public static function get_admin_view_component(): string;

    /**
     * The component path of the vue component for allowing admins to configure extra settings. (Optional)
     *
     * @return string|null
     */
    abstract public static function get_admin_settings_component(): ?string;

    /**
     * Array of available settings that can be configured by the admin (keys) and their default values (values)
     *
     * Example: ['allow_rating' => true, 'show_description' => false]
     *
     * @return array
     */
    abstract public static function get_available_settings(): array;

    /**
     * Returns the settings in a human readable form. The key is the display name of the setting and the value is human readable form of the value
     *
     * @example
     *
     * [
     *     'Is rating enabled?' => 'Yes',
     *     'Final rating participant' => 'Manager'
     * ]
     *
     * @param array $settings
     * @return array
     */
    abstract public static function get_display_settings(array $settings): array;

    /**
     * The component path of the vue component for picking the content items.
     *
     * @return string
     */
    abstract public static function get_content_picker_component(): string;

    /**
     * The component path of the vue component for rendering the content response display.
     *
     * @return string
     */
    abstract public static function get_participant_content_component(): string;

    /**
     * This function is responsible for loading the actual items when requested by the
     * @see \performelement_linked_review\webapi\resolver\query\content_items query.
     * This data is injected in the content items and used for display in the
     * VUE component returned by @see content_type::get_participant_content_component().
     *
     * Make sure this method returns the array keyed by the content_ids passed in
     * otherwise the content won't be returned to the frontend.
     *
     * Each individual content item returned needs to have an id property or key.
     *
     * @param int $user_id the user the items belong to
     * @param array $content_ids
     * @param int $created_at the timestamp the content got created, this might be needed for point in time / static data
     * @return array the array needs to be keyed by the id of the item
     */
    abstract public function load_content_items(int $user_id, array $content_ids, int $created_at): array;

}
