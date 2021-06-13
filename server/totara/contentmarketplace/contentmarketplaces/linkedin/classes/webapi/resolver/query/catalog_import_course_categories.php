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
 * @author  Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

namespace contentmarketplace_linkedin\webapi\resolver\query;

use contentmarketplace_linkedin\interactor\catalog_import_interactor;
use core\webapi\execution_context;
use core\webapi\middleware\require_login;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;
use coursecat;
use totara_contentmarketplace\webapi\middleware\require_content_marketplace;

final class catalog_import_course_categories implements query_resolver, has_middleware {
    /**
     * {@inheritdoc}
     */
    public static function resolve(array $args, execution_context $ec) {
        (new catalog_import_interactor())->require_view_catalog_import_page();
        return self::build_category_list([], coursecat::get(0));
    }

    /**
     * {@inheritdoc}
     */
    public static function get_middleware(): array {
        return [
            new require_login(),
            new require_content_marketplace('linkedin'),
        ];
    }

    /**
     * Recursively builds a flat list of categories that are the child of the specified parent category.
     * TODO: Modify this in TL-31124 so the actual hierarchy tree of categories is built.
     *
     * @param coursecat[] $categories
     * @param coursecat $parent_category
     * @return coursecat[]
     */
    private static function build_category_list(array $categories, coursecat $parent_category): array {
        foreach ($parent_category->get_children() as $child_category) {
            $categories[] = $child_category;
            $categories = self::build_category_list($categories, $child_category);
        }
        return $categories;
    }
}