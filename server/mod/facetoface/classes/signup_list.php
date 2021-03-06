<?php
/*
* This file is part of Totara Learn
*
* Copyright (C) 2018 onwards Totara Learning Solutions LTD
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
* @author Oleg Demeshev <oleg.demeshev@totaralearning.com>
* @package mod_facetoface
*/

namespace mod_facetoface;

use mod_facetoface\signup\state\{
    not_set,
    state
};

defined('MOODLE_INTERNAL') || die();

/**
 * Class signup_list represents signups in seminar event
 */
final class signup_list implements \Iterator, \Countable {

    use traits\seminar_iterator;

    /**
     * construct signup_list with sql conditions
     *
     * @param array $conditions optional array $fieldname => requestedvalue with AND in between
     * @param string $sort an order to sort the results in.
     * @return signup_list
     */
    public static function from_conditions(array $conditions = null, string $sort = ''): signup_list {
        global $DB;

        if ($conditions === null) {
            debugging('Passing null to the first parameter is deprecated. Please pass an empty array i.e. [] or array() if you really want it to return all records.', DEBUG_DEVELOPER);
        }
        $list = new static();
        $signupitems = $DB->get_records('facetoface_signups', $conditions, $sort, '*');
        foreach ($signupitems as $signupitem) {
            $signup = new signup();
            $list->add($signup->map_instance($signupitem));
        }
        return $list;
    }

    /**
     * Create a list of a users signups within a specific seminar, excluding any cancelled or declined signups.
     * Primarily used by the multiple signup common condition.
     *
     * @param int $userid
     * @param int $seminarid
     * @return signup_list
     */
    public static function user_active_signups_within_seminar(int $userid, int $seminarid) : signup_list {
        global $DB;

        // Note: We want to specifically exclude cancelled and declined signups because they are not 'active' signups.
        $sql = 'SELECT s.*
                  FROM {facetoface_signups} s
            INNER JOIN {facetoface_signups_status} st
                    ON st.signupid = s.id AND st.superceded = 0
            INNER JOIN {facetoface_sessions} e
                    ON s.sessionid = e.id
            INNER JOIN {facetoface} f
                    ON e.facetoface = f.id
                 WHERE s.userid = :uid
                   AND f.id = :sid
                   AND s.archived = 0
                   AND st.statuscode != :decl
                   AND st.statuscode != :ucan
                   AND st.statuscode != :ecan
            ORDER BY e.timecreated';
        $params = [
            'uid' => $userid,
            'sid' => $seminarid,
            'decl' => \mod_facetoface\signup\state\declined::get_code(),
            'ucan' => \mod_facetoface\signup\state\user_cancelled::get_code(),
            'ecan' => \mod_facetoface\signup\state\event_cancelled::get_code()
        ];

        $list = new static();
        $rawdata = $DB->get_records_sql($sql, $params);
        foreach ($rawdata as $data) {
            $signup = new signup();
            $list->add($signup->map_instance($data));
        }
        return $list;
    }

    /**
     * @param int   $userid
     * @param int   $seminarid
     * @param array $statuscodes
     *
     * @return signup_list
     */
    public static function user_signups_within_seminar_with_codes(int $userid, int $seminarid,
                                                                  array $statuscodes): signup_list {
        global $DB;

        [$asql, $params] = $DB->get_in_or_equal($statuscodes, SQL_PARAMS_NAMED);
        $sql = "
            SELECT su.*
            FROM {facetoface_signups} su
            INNER JOIN {facetoface_signups_status} ss
            ON ss.signupid = su.id AND ss.superceded = 0
            INNER JOIN {facetoface_sessions} s
            ON s.id = su.sessionid
            INNER JOIN {facetoface} f
            ON f.id = s.facetoface
            WHERE su.archived = 0
            AND f.id = :facetoface
            AND su.userid = :userid
            AND ss.statuscode {$asql}
        ";

        $params['facetoface'] = $seminarid;
        $params['userid'] = $userid;

        $records = $DB->get_records_sql($sql , $params);
        $list = new static();

        foreach ($records as $record) {
            $signup = new signup();
            $signup->map_instance($record);

            $list->add($signup);
        }

        return $list;
    }

    /**
     * Create a list itself that is including every signups within a seminar, and that signup should not be archived
     * at all. This means that, the list is including all the possible states, from any cancelled to fully attended.
     *
     * @param int $userid
     * @param int $seminarid
     *
     * @return signup_list
     */
    public static function user_signups_within_seminar(int $userid, int $seminarid): signup_list {
        $states = array_values(state::get_all_states());
        $statuscodes = [];

        foreach ($states as $state) {
            if (!method_exists($state, 'get_code')) {
                debugging("No method get_code for state: '{$state}'");
                continue;
            }

            $code = $state::get_code();
            if ($code == not_set::get_code()) {
                // Do not accept not_set.
                continue;
            }

            $statuscodes[] = $code;
        }

        return self::user_signups_within_seminar_with_codes($userid, $seminarid, $statuscodes);
    }

    /**
     * Create a list of user signups that are waitlisted for a specified event.
     *
     * @param int $eventid
     * @return signup_list
     */
    public static function signups_for_event(int $eventid): signup_list {
        global $DB;

        $list = new static();
        $rawdata = $DB->get_records('facetoface_signups', ['sessionid' => $eventid]);
        foreach ($rawdata as $data) {
            $signup = new signup();
            $list->add($signup->map_instance($data));
        }
        return $list;
    }

    /**
     * Create a list of user signups that match a given state for a specified event.
     *
     * @param int $eventid
     * @param int $statuscode
     * @return signup_list
     */
    public static function signups_by_statuscode_for_event(int $eventid, int $statuscode): signup_list {
        global $DB;

        $sql = 'SELECT s.*
                  FROM {facetoface_signups} s
                  JOIN {facetoface_signups_status} st
                    ON st.signupid = s.id and st.superceded = 0
                 WHERE s.sessionid = :eventid
                   AND st.statuscode = :code';
        $params = [
            'eventid' => $eventid,
            'code' => $statuscode
        ];

        $list = new static();
        $rawdata = $DB->get_records_sql($sql, $params);
        foreach ($rawdata as $data) {
            $signup = new signup();
            $list->add($signup->map_instance($data));
        }
        return $list;
    }

    /**
     * Add signup to item list
     * @param signup $item
     */
    public function add(signup $item): void {
        $this->items[$item->get_id()] = $item;
    }

    /**
     * @return signup|null
     */
    public function get_first(): ?signup {
        if (empty($this->items)) {
            return null;
        }

        $item = reset($this->items);
        return $item;
    }

    /**
     * Returning an array of signups within this list.
     *
     * @return signup[]
     */
    public function to_array(): array {
        return $this->items;
    }
}
