<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2019 onwards Totara Learning Solutions LTD
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
 * @author Valerii Kuznetsov <valerii.kuznetsov@totaralearning.com>
 * @package totara_engage
 */
namespace totara_engage\webapi\resolver\mutation;


use core\webapi\execution_context;
use core\webapi\mutation_resolver;
use totara_core\advanced_feature;
use totara_engage\upload\upload;

final class delete_draft_file implements mutation_resolver {
    /**
     * @param array             $args
     * @param execution_context $ec
     * @return array
     */
    public static function resolve(array $args, execution_context $ec): array {

        require_login();
        advanced_feature::require('engage_resources');

        upload::delete_draft_file($args['draftid'], $args['filename']);

        return [
            'success' => true
        ];
    }
}