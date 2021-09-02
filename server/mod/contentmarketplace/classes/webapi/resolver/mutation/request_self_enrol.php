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
 * @package mod_contentmarketplace
 */

namespace mod_contentmarketplace\webapi\resolver\mutation;

use coding_exception;
use container_course\course;
use core\webapi\execution_context;
use core\webapi\middleware\require_login_course_via_coursemodule;
use core\webapi\mutation_resolver;
use core\webapi\resolver\has_middleware;
use mod_contentmarketplace\interactor\content_marketplace_interactor;
use mod_contentmarketplace\model\content_marketplace;
use totara_contentmarketplace\course\enrol_manager;

/**
 * Self enrolment only for admin user and guest
 *
 * Mutation for Class request_self_enrol
 */
final class request_self_enrol implements mutation_resolver, has_middleware {
    /**
     * @param array $args
     * @param execution_context $ec
     * @return bool
     */
    public static function resolve(array $args, execution_context $ec): bool {
        $cmid = $args['cm_id'];
        if (!isset($cmid)) {
            throw new coding_exception("Missing required field {$cmid}");
        }

        $cm_model = content_marketplace::from_course_module_id($cmid);
        $interactor = new content_marketplace_interactor($cm_model);

        if (!$cm_model->get_self_enrol_enabled()) {
            throw new coding_exception('Self enrolment is not enabled');
        }

        if ($interactor->is_site_guest()) {
            throw new coding_exception('Site guest can not request self enrol');
        }

        $course = course::from_id($cm_model->get_course_id());
        $manager = new enrol_manager($course);

        $manager->self_enrol_as_student();
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function get_middleware(): array {
        return [
            new require_login_course_via_coursemodule('cm_id')
        ];
    }
}