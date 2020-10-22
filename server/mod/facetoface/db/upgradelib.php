<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
 * @author  Oleg Demeshev <oleg.demeshev@totaralearning.com>
 * @package mod_facetoface
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Fixed the orphaned records with statuscode 50 as we deprecated "Approved" status.
 */
function facetoface_upgradelib_approval_to_declined_status() {
    global $DB;

    $superceded = 0;
    $statuscode = 50;
    $statuses = $DB->get_records_sql(
        'SELECT fss.*, u.id AS userid, f.id AS facetofaceid
           FROM {facetoface_signups_status} fss
           JOIN {facetoface_signups} fs ON fs.id = fss.signupid
           JOIN {facetoface_sessions} s ON s.id = fs.sessionid
           JOIN {facetoface} f ON f.id = s.facetoface
           JOIN {user} u ON u.id = fs.userid
          WHERE superceded = :superceded AND statuscode = :statuscode',
        ['superceded' => $superceded, 'statuscode' => $statuscode]
    );
    /** @see \mod_facetoface\signup\state\declined::get_code() */
    $declined_status = 30;
    $upgrade_log_notice = defined('UPGRADE_LOG_NOTICE') ? UPGRADE_LOG_NOTICE : 1;
    $trans = $DB->start_delegated_transaction();
    foreach ($statuses as $status) {
        // Update the record.
        $DB->set_field('facetoface_signups_status', 'statuscode', $declined_status, ['id' => $status->id]);

        // Add a log message.
        upgrade_log(
            $upgrade_log_notice,
            'mod_facetoface',
            'Invalid user signup cancelled: userid ' . $status->userid . ', facetofaceid ' . $status->facetofaceid
        );
    }
    $trans->allow_commit();
}