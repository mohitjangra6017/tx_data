<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2015 onwards Totara Learning Solutions LTD
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
 * @author Petr Skoda <petr.skoda@totaralms.com>
 * @package auth_connect
 */


namespace auth_connect\task;

defined('MOODLE_INTERNAL') || die();

/**
 * General cleanup task.
 */
class cleanup_task extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('taskcleanup', 'auth_connect');
    }

    /**
     * Do the job.
     */
    public function execute() {
        global $DB;

        if (!is_enabled_auth('connect')) {
            $DB->delete_records('auth_connect_sso_sessions');
            $DB->delete_records('auth_connect_sso_requests');
            return;
        }

        $select = "timecreated < ?";
        $params = array(time() - \auth_connect\util::REQUEST_LOGIN_TIMEOUT);
        $DB->delete_records_select('auth_connect_sso_requests', $select, $params);
    }
}
