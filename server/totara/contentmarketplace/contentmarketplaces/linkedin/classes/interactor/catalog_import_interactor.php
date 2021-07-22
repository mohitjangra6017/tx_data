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
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\interactor;

use context;
use context_coursecat;
use context_system;
use required_capability_exception;
use totara_contentmarketplace\interactor\base;

/**
 * Interactor class for catalog import actions related.
 */
class catalog_import_interactor extends base {
    /**
     * Whether user is able to view the catalog import page.
     *
     * @return bool
     */
    public function can_view_catalog_import_page(): bool {
        // If the user is able to add a course, then user should be able to view the catalog import page.
        return $this->can_add_course();
    }

    /**
     * @return void
     */
    public function require_view_catalog_import_page(): void {
        $this->require_add_course();
    }

    /**
     * Whether the user is able to add a course.
     * @return bool
     */
    public function can_add_course(): bool {
        return has_capability_in_any_context(
            'totara/contentmarketplace:add',
            [CONTEXT_COURSECAT],
            $this->actor_id
        );
    }

    /**
     * @param string  $capability
     * @param context $context
     * @param string  $error_msg
     * @param string  $component
     *
     * @return required_capability_exception
     */
    private static function create_required_capability_exception(
        string $capability,
        context $context,
        string $error_msg = 'nopermissions',
        string $component = 'error'
    ): required_capability_exception {
        return new required_capability_exception(
            $context,
            $capability,
            $error_msg,
            $component
        );
    }

    /**
     * @return void
     */
    public function require_add_course(): void {
        if (!$this->can_add_course()) {
            // We are using the context system here, because we are checking the capabilities across
            // all the context categories. Since the actor does not have any caps enabled in any context
            // categories, hence we use the one above context category. Which it will cover all the context
            // categories. And the one above it is the context system
            $context = context_system::instance();
            throw self::create_required_capability_exception('totara/contentmarketplace:add', $context);
        }
    }

    /**
     * @param context_coursecat $context_category
     * @return bool
     */
    public function can_add_course_to_category(context_coursecat $context_category): bool {
        return has_capability(
            'totara/contentmarketplace:add',
            $context_category,
            $this->actor_id
        );
    }

    /**
     * @param context_coursecat $context_category
     * @return void
     */
    public function require_add_course_to_category(context_coursecat $context_category): void {
        require_capability('totara/contentmarketplace:add', $context_category, $this->actor_id);
    }
}