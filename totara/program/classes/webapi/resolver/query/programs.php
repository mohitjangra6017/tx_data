<?php
/**
 *
 * This file is part of Totara LMS
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
 * @author Simon Coggins <simon.coggins@totaralearning.com>
 * @author David curry <david.curry@totaralearning.com>
 * @package totara_program
 */

namespace totara_program\webapi\resolver\query;

use core\webapi\execution_context;

/**
 * Query to return all programs.
 */
class programs implements \core\webapi\query_resolver {

    /**
     * Returns all programs.
     *
     * @param array $args
     * @param execution_context $ec
     */
    public static function resolve(array $args, execution_context $ec) {
        global $CFG;
        require_once($CFG->dirroot . '/totara/program/lib.php');
        require_once($CFG->dirroot . '/totara/program/program.class.php');

        // TL-21305 will find a better, encapsulated solution for require_login calls.
        require_login(null, false, null, false, true);

        if (totara_feature_disabled('programs')) {
            throw new \coding_exception('Programs have been disabled.');
        }

        // This method handles visibility checks internally.
        $progs = prog_get_programs("all","p.sortorder ASC", "p.*");
        $programs = [];

        foreach ($progs as $p) {
            $programs[] = new \program($p);
        }

        return $programs;
    }
}

