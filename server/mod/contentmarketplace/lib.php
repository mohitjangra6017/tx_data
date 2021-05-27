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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package mod_contentmarketplace
 */
defined('MOODLE_INTERNAL') || die();

use mod_contentmarketplace\local\helper;
use mod_contentmarketplace\model\content_marketplace;
use mod_contentmarketplace\output\content_marketplace_logo;

/**
 * A callback function from course's API to create an instance of content
 * marketplace module.
 *
 * @param stdClass $record
 * @return int
 */
function contentmarketplace_add_instance(stdClass $record): int {
    $content_marketplace = helper::create_content_marketplace(
        $record->course,
        $record->learning_object_id,
        $record->learning_object_marketplace_component
    );

    return $content_marketplace->id;
}

/**
 * Render the content for content marketplace.
 *
 * @return void
 */
function contentmarketplace_cm_info_view(cm_info $course_module): void {
    global $OUTPUT;

    $content_marketplace = content_marketplace::load_by_id($course_module->instance);
    $template = content_marketplace_logo::create_from_model($content_marketplace);

    $content = $OUTPUT->render($template);
    $course_module->set_after_link($content);
}

/**
 * Returns TRUE if we support such feature, FALSE for vice versa. Otherwise
 * NULL if it is unknown.
 *
 * @param string|mixed $feature
 * @return bool|null
 */
function contentmarketplace_supports($feature): ?bool {
    switch ($feature) {
        case FEATURE_BACKUP_MOODLE2:
            // Return false for now, but TL-30933 will help to resolve this.
            return false;

        case FEATURE_NO_VIEW_LINK:
            return false;

        default:
            // Everything is unknown for now.
            return null;
    }
}

/**
 * @param int $id
 * @return bool
 */
function contentmarketplace_delete_instance(int $id): bool {
    $content_marketplace = content_marketplace::load_by_id($id);
    return $content_marketplace->delete();
}