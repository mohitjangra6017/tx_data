<?php
/**
 * This file is part of Totara Core
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
 * @package totara_oauth2
 */

namespace totara_oauth2\webapi\resolver\query;

use core\collection;
use core\webapi\execution_context;
use core\webapi\middleware\require_login;
use core\webapi\middleware\require_system_capability;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;
use totara_oauth2\data_provider\client_provider;

/**
 * Class client_provide_detail
 * @package totara_oauth2\webapi\resolver\query
 */
class client_providers implements query_resolver, has_middleware {
    /**
     * @param array $args
     * @param execution_context $ec
     */
    public static function resolve(array $args, execution_context $ec): collection {
        $filters = $args['input']['filters'] ?? [];
        return (new client_provider())->add_filters($filters)->get();
    }

    /**
     * @inheritDoc
     */
    public static function get_middleware(): array {
        return [
            new require_login(),
            new require_system_capability('moodle/site:config')
        ];
    }
}
