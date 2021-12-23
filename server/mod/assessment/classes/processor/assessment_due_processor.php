<?php
/**
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010-2020 Kineo Group Inc.
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
 */

/**
 * Update user assessment due dates
 *
 * @package     mod_assessment
 * @author      Russell England <Russell.England@kineo.com>
 * @copyright   2020 Kineo Group Inc. <http://www.kineo.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

namespace mod_assessment\processor;

use mod_assessment\model;
use mod_assessment\helper;
use mod_assessment\model\assessment;
use moodle_database;

class assessment_due_processor
{

    /**
     * Update multiple assessments and/or users for a course
     *
     * Called by the user_enrolment_created event
     *
     * @param int $courseid
     * @param int $userid
     */
    public static function update_by_course($courseid = null, $userid = null)
    {
        $assessments = assessment::instances(array('course' => $courseid));
        foreach ($assessments as $assessment) {
            self::update_users($assessment, $userid);
        }
    }

    /**
     * Update all assessments dependent on profile field for a user
     *
     * Called by the user_updated event
     *
     * @param int $userid
     * @global moodle_database $DB ;
     */
    public static function update_by_user_profile(int $userid)
    {
        global $DB;

        // Get assessments that use the profile date field.
        // For courses that I am enrolled on.
        $sql = "SELECT a.id
                FROM {assessment} a
                WHERE a.duetype = " . helper\assessment_due_helper::DUE_TYPE_PROFILE_FIELD . "
                AND EXISTS (
                    SELECT ue.id
                    FROM {enrol} e
                    JOIN {user_enrolments} ue ON ue.enrolid = e.id AND ue.userid = :userid
                    WHERE e.courseid = a.course
                )";

        $params = array('userid' => $userid);

        if ($assessmentids = $DB->get_fieldset_sql($sql, $params)) {
            foreach ($assessmentids as $assessmentid) {
                $assessment = assessment::instance(array('id' => $assessmentid), MUST_EXIST);
                self::update_users($assessment, $userid);
            }
        }
    }

    /**
     * Update all assessments dependent on first login for a user
     *
     * Called by the user_loggedin event
     *
     * @param int $userid
     * @global moodle_database $DB ;
     */
    public static function update_by_user_login(int $userid)
    {
        global $DB;

        // Get assessments that use the first login date.
        // For courses that I am enrolled on.
        $sql = "SELECT a.id
                FROM {assessment} a
                WHERE a.duetype = " . helper\assessment_due_helper::DUE_TYPE_FIRST_LOGIN . "
                AND EXISTS (
                    SELECT ue.id
                    FROM {enrol} e
                    JOIN {user_enrolments} ue ON ue.enrolid = e.id AND ue.userid = :userid
                    WHERE e.courseid = a.course
                )";

        $params = array('userid' => $userid);

        if ($assessmentids = $DB->get_fieldset_sql($sql, $params)) {
            foreach ($assessmentids as $assessmentid) {
                $assessment = assessment::instance(array('id' => $assessmentid), MUST_EXIST);
                self::update_users($assessment, $userid);
            }
        }
    }

    /**
     * Update time due for users or a single user for a assessment
     *
     * @param assessment $assessment
     * @param int $userid optional for single user update
     * @return void
     * @global moodle_database $DB
     */
    public static function update_users(assessment $assessment, $userid = null)
    {
        global $DB;

        // First create any missing user records.
        // Could use insert via batch but its pretty much the same as this statement and this doesn't eat any memory.
        $sql = "INSERT INTO {assessment_due} (userid, assessmentid)
                SELECT ue.userid, a.id
                FROM {assessment} a
                JOIN {enrol} e ON e.courseid = a.course
                JOIN {user_enrolments} ue ON ue.enrolid = e.id
                WHERE a.id = :assessmentid
                AND NOT EXISTS (
                    SELECT d.id
                    FROM {assessment_due} d
                    WHERE d.assessmentid = a.id
                    AND d.userid = ue.userid
                )";

        $params = array('assessmentid' => $assessment->id);

        if (!empty($userid)) {
            // Selected user only.
            $sql .= "\nAND ue.userid = :userid";
            $params['userid'] = $userid;
        }

        $DB->execute($sql, $params);

        if ($assessment->duetype == helper\assessment_due_helper::DUE_TYPE_NONE) {
            // Easy peasy.
            $DB->set_field('assessment_due', 'timedue', null, $params);
            // Job done.
            return;
        }

        if ($assessment->duetype == helper\assessment_due_helper::DUE_TYPE_FIXED) {
            // Easy peasy.
            $DB->set_field('assessment_due', 'timedue', $assessment->timedue, $params);
            // Job done.
            return;
        }

        // Using dynamic days for the other due date types.
        $params['duedays'] = $assessment->duedays * 24 * 60 * 60;

        switch ($assessment->duetype) {
            case helper\assessment_due_helper::DUE_TYPE_FIRST_LOGIN :
                $sql = "SELECT d.id,
                            CASE
                                WHEN u.firstaccess > 0 THEN u.firstaccess
                                ELSE u.lastaccess
                            END + :duedays AS timedue
                        FROM {assessment_due} d
                        JOIN {user} u ON u.id = d.userid
                        WHERE d.assessmentid = :assessmentid";
                break;

            case helper\assessment_due_helper::DUE_TYPE_ENROLLED :
                $sql = "SELECT d.id, ue.timecreated + :duedays AS timedue
                        FROM {assessment_due} d
                        JOIN {assessment} a ON a.id = d.assessmentid
                        JOIN {enrol} e ON e.courseid = a.course
                        JOIN {user_enrolments} ue ON ue.enrolid = e.id
                        WHERE d.assessmentid = :assessmentid";
                break;

            case helper\assessment_due_helper::DUE_TYPE_PROFILE_FIELD :
                if (empty($assessment->duefieldid) || !$DB->record_exists('user_info_field', array('id' => $assessment->duefieldid))) {
                    debugging('No due date field or the field doesn\'t exist for assessment id ' . $assessment->id, DEBUG_DEVELOPER);
                    return;
                }

                $sql = "SELECT d.id, " . $DB->sql_cast_char2int('f.data', true) . " + :duedays AS timedue
                        FROM {assessment_due} d
                        JOIN {user_info_data} f ON f.userid = d.userid AND f.fieldid = :fieldid AND f.data > ''
                        WHERE d.assessmentid = :assessmentid";

                $params['fieldid'] = $assessment->duefieldid;
                break;

            default:
                debugging('Uknown due date type for assessment id ' . $assessment->id, DEBUG_DEVELOPER);
                return;

        }

        if (!empty($userid)) {
            // Selected user only.
            $sql .= "\nAND d.userid = :userid";
        }

        // Bit of a nuisance, there's no update by batch function.
        // And different databases have their own version of update using a join.
        $assessmentdues = $DB->get_recordset_sql($sql, $params);
        if ($assessmentdues->valid()) {
            foreach ($assessmentdues as $assessmentdue) {
                // The true means there might be more records to update.
                $DB->update_record('assessment_due', $assessmentdue, true);
            }
        }
        $assessmentdues->close();

    }

}