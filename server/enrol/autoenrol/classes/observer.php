<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Event observers used in this plugin
 *
 * @package    enrol_auto
 * @copyright  Eugene Venter <eugene@catalyst.net.nz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_autoenrol;

use enrol_autoenrol_plugin;
use stdClass;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/enrol/locallib.php');
require_once($CFG->dirroot . '/enrol/autoenrol/lib.php');

/**
 * Event observer for enrol_auto.
 */
class observer
{

    /**
     * Triggered via course_module_viewed event of a module.
     *
     * @param stdClass $event
     */
    public static function course_module_viewed($event)
    {
        global $DB;

        $eventdata = $event->get_data();

        if (!enrol_is_enabled('autoenrol')) {
            return;
        }

        if (is_siteadmin($eventdata['userid']) || isguestuser($eventdata['userid'])) {
            // Don't enrol site admins or guest users.
            return;
        }

        $autoplugin = enrol_get_plugin('autoenrol');

        if ((!$instance = $autoplugin->get_instance_for_course($eventdata['courseid']))
            || $instance->status == ENROL_INSTANCE_DISABLED) {
            return;
        }

        if ($instance->customint2 != enrol_autoenrol_plugin::ENROL_AUTO_MOD_VIEWED || empty($instance->customtext2)) {
            // nothing to see here :D
            return;
        }

        $enabledmods = explode(',', $instance->customtext2);
        $modname = str_replace('mod_', '', $eventdata['component']);
        if (!in_array($modname, $enabledmods)) {
            return;
        }

        $context = \context_module::instance($eventdata['contextinstanceid']);
        if ($context->is_user_access_prevented()) {
            return;
        }

        if (!$DB->record_exists('user_enrolments', ['enrolid' => $instance->id, 'userid' => $eventdata['userid']])) {
            $autoplugin->enrol_user($instance, $eventdata['userid'], $instance->roleid);

            if ($instance->customint7) {
                $user = $DB->get_record('user', ['id' => $eventdata['userid']]);
                (new enrol_autoenrol_plugin())->email_welcome_message($instance, $user);
            }
        }
    }

    /**
     * Triggered via the user_loggedin event, when a user logs in.
     *
     * @param stdClass $event
     */
    public static function user_loggedin($event)
    {
        global $DB;

        $eventdata = $event->get_data();

        if (!enrol_is_enabled('autoenrol')) {
            return;
        }

        if (is_siteadmin($eventdata['userid']) || isguestuser($eventdata['userid'])) {
            // Don't enrol site admins or guest users.
            return;
        }

        // Get all courses that have an auto enrol plugin, set to auto enrol on login, where the user isn't enrolled yet
        $sql = "SELECT e.courseid
            FROM {enrol} e
            LEFT JOIN {user_enrolments} ue ON e.id = ue.enrolid AND ue.userid = ?
            WHERE e.enrol = 'autoenrol'
            AND e.status = ?
            AND e.customint2 = ?
            AND ue.id IS NULL";
        if (!$courses =
            $DB->get_records_sql(
                $sql,
                [$eventdata['userid'], ENROL_INSTANCE_ENABLED, enrol_autoenrol_plugin::ENROL_AUTO_LOGIN]
            )) {
            return;
        }

        $autoplugin = enrol_get_plugin('autoenrol');
        foreach ($courses as $course) {
            if (!$instance = $autoplugin->get_instance_for_course($course->courseid)) {
                continue;
            }

            $context = \context_course::instance($course->courseid);
            if ($context->is_user_access_prevented()) {
                continue;
            }

            $autoplugin->enrol_user($instance, $eventdata['userid'], $instance->roleid);

            if ($instance->customint7) {
                $user = $DB->get_record('user', ['id' => $eventdata['userid']]);
                (new enrol_autoenrol_plugin())->email_welcome_message($instance, $user);
            }

        }
    }

}