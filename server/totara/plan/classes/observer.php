<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
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
 * @author David Curry <david.curry@totaralms.com>
 * @author Alastair Munro <alastair.munro@totaralms.com>
 * @package totara_plan
 */

defined('MOODLE_INTERNAL') || die();

use hierarchy_competency\event\competency_deleted;

class totara_plan_observer {

    /**
     * Clears relevant user data when the user is deleted
     *  - Evidence records
     *
     * @deprecated since Totara 13
     *
     * @param \core\event\user_deleted $event
     *
     */
    public static function user_deleted(\core\event\user_deleted $event) {
        debugging('\totara_plan_observer::user_deleted has been deprecated and is no longer used, please use totara_evidence\observer::user_deleted instead.', DEBUG_DEVELOPER);
        totara_evidence\observer::user_deleted($event);
    }

    /*
     * This function is to clean up any references to courses within
     * programs when they are deleted. Any coursesets that become empty
     * due to this are also deleted as programs does not allow empty
     * coursesets.
     *
     * @param \core\event\course_deleted $event
     * @return boolean True if all references to the course are deleted correctly
     */
    public static function course_deleted(\core\event\course_deleted $event) {
        global $DB;

        $courseid = $event->objectid;

        $transaction = $DB->start_delegated_transaction();

        // Remove relations.
        $sql = "DELETE FROM {dp_plan_component_relation} WHERE
                    (component1 = 'course' AND itemid1 IN (SELECT id FROM {dp_plan_course_assign} WHERE courseid = :courseid1))
                OR
                    (component2 = 'course' AND itemid2 IN (SELECT id FROM {dp_plan_course_assign} WHERE courseid = :courseid2))";

        $params = array('courseid1' => $courseid, 'courseid2' => $courseid);
        $DB->execute($sql, $params);

        // Remove records of courses assigned to plans.
        $DB->delete_records('dp_plan_course_assign', array('courseid' => $courseid));

        $transaction->allow_commit();

        return true;
    }

    /**
     * Delete all plan related records if the competency gets deleted
     *
     * @param competency_deleted $event
     */
    public static function competency_deleted(competency_deleted $event) {
        global $DB;

        // Delete all plan related records if the competency gets deleted
        $DB->delete_records('dp_plan_competency_assign', ['competencyid' => $event->objectid]);
        $DB->delete_records('dp_plan_competency_value', ['competency_id' => $event->objectid]);
    }
}
