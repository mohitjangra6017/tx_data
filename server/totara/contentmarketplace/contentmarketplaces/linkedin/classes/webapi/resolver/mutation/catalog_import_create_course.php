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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

namespace contentmarketplace_linkedin\webapi\resolver\mutation;

use contentmarketplace_linkedin\config;
use contentmarketplace_linkedin\interactor\catalog_import_interactor;
use contentmarketplace_linkedin\model\learning_object;
use core\notification;
use core\webapi\execution_context;
use core\webapi\middleware\require_login;
use core\webapi\mutation_resolver;
use core\webapi\resolver\has_middleware;
use Exception;
use totara_contentmarketplace\webapi\middleware\require_content_marketplace;

final class catalog_import_create_course implements mutation_resolver, has_middleware {
    /**
     * {@inheritdoc}
     */
    public static function resolve(array $args, execution_context $ec): array {
        (new catalog_import_interactor())->require_view_catalog_import_page();

        $input_params = $args['input'];
        $item_ids = $input_params['item_ids'];

        $item_size = count($item_ids);
        $result = [
            'success' => true,
            'has_notification' => false,
            'message' => ''
        ];
        $creation_completed = false;

        try {
            foreach ($item_ids as $item_id) {
                $learning_object = learning_object::load_by_id($item_id);
                if ($item_size < config::get_max_selected_items_number()) {
                    // TODO immediate create course
                    $creation_completed = true;
                } else {
                    // TODO delay create course
                }
            }
        } catch (Exception $e) {
            if ($item_size < config::get_max_selected_items_number()) {
                if ($creation_completed) {
                    notification::error(get_string('content_creation_failure', 'contentmarketplace_linkedin'));
                } else {
                    notification::error(get_string('content_creation_failure_no_course', 'contentmarketplace_linkedin'));
                }

                $result['has_notification'] = true;
            }

            $result['success'] = false;
            return $result;
        }

        $item_size < config::get_max_selected_items_number() ? notification::success(
            get_string('course_content_immediate_creation', 'contentmarketplace_linkedin')
        ) : notification::info(
            get_string('course_content_delay_creation', 'contentmarketplace_linkedin')
        );

        $result['has_notification'] = true;
        return $result;
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
}