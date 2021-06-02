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

namespace contentmarketplace_linkedin\webapi\resolver\mutation;

use contentmarketplace_linkedin\config;
use contentmarketplace_linkedin\dto\course_creation_result;
use contentmarketplace_linkedin\interactor\catalog_import_interactor;
use core\notification;
use core\webapi\execution_context;
use core\webapi\middleware\require_login;
use core\webapi\mutation_resolver;
use core\webapi\resolver\has_middleware;
use moodle_url;
use Throwable as throwable;
use totara_contentmarketplace\webapi\middleware\require_content_marketplace;

final class catalog_import_create_course implements mutation_resolver, has_middleware {
    /**
     * Note: the resolver functionality will queue up to $SESSION for the notification of the process.
     *       This can cause the race conditions problem with a low chances, it is not ideally, but at
     *       the moment, there is no common prace around this notification for navigated page.
     *
     * {@inheritdoc}
     */
    public static function resolve(array $args, execution_context $ec): course_creation_result {
        (new catalog_import_interactor())->require_add_course();

        $input_params = $args['input'];
        $item_ids = $input_params['item_ids'];

        $threshold = config::get_max_selected_items_number();
        $redirect_url = new moodle_url('/totara/catalog/index.php');

        if (count($item_ids) <= $threshold) {
            // Creation on runtime here.
            return self::create_course_immediate($item_ids);
        }

        // Adhoc task queue here
        $result = new course_creation_result();
        $result->set_redirect_url($redirect_url);

        notification::info(get_string('course_content_delay_creation', 'contentmarketplace_linkedin'));
        return $result;
    }

    /**
     * @param array $item_ids
     * @return course_creation_result
     */
    private static function create_course_immediate(array $item_ids): course_creation_result {
        $result = new course_creation_result();
        $creation_completed = false;

        try {
            foreach ($item_ids as $item_id) {
                // todeo: proper creation
            }
        } catch (throwable $e) {
            debugging(
                sprintf(
                    "An exception was caught during the creation, '%s': %s",
                    get_class($e),
                    $e->getMessage()
                ),
                DEBUG_DEVELOPER
            );

            $identifier = 'content_creation_failure_no_course';
            if ($creation_completed) {
                $identifier = 'content_creation_failure';
            }

            notification::error(get_string($identifier, 'contentmarketplace_linkedin'));
            return $result;
        }

        // Successful creation
        notification::success(get_string('course_content_immediate_creation', 'contentmarketplace_linkedin'));
        $result->set_success(true);

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