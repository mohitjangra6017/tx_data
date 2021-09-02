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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_contentmarketplace
 */
namespace totara_contentmarketplace\course;

use coding_exception;
use container_course\course;
use core\entity\enrol;

class enrol_manager {
    /**
     * @var course
     */
    private $course;

    /**
     * enrol_manager constructor.
     * @param course $course
     */
    public function __construct(course $course) {
        global $CFG;
        require_once("{$CFG->dirroot}/lib/enrollib.php");

        $this->course = $course;
    }

    /**
     * @param string $enrol_name
     * @return void
     */
    public function enable_enrol(string $enrol_name): void {
        if (!enrol_is_enabled($enrol_name)) {
            // The enrolment is not enabled at the system level
            return;
        }

        $course_id = $this->course->get_id();
        $repository = enrol::repository();

        // The process of course cration should create the enrolment method entry by
        // default. Hence at this point of the code, this function should just mainly
        // be about enable the the enrol method.
        $enrol = $repository->find_enrol($enrol_name, $course_id, true);

        $plugin = enrol_get_plugin($enrol_name);
        $enrol_record = $enrol->to_record();

        // Clone the enrol record, but only update the status. This happens because
        // the plugin's API might expect different attributes, but not just the status.
        $update_enrol = clone $enrol_record;
        $update_enrol->status = ENROL_INSTANCE_ENABLED;

        $plugin->update_instance($enrol_record, $update_enrol);
    }

    /**
     * @param int $user_id
     * @return void
     */
    public function enrol_course_creator(int $user_id): void {
        global $CFG;
        $context = $this->course->get_context();

        if (isguestuser($user_id)) {
            debugging(
                "Cannot enrol the guest user as the course creator to the course",
                DEBUG_DEVELOPER
            );

            return;
        }

        $is_viewing = is_viewing($context, $user_id, 'moodle/role:assign');
        $is_enrolled = is_enrolled($context, $user_id, 'moodle/role:assign');

        if (!empty($CFG->creatornewroleid) && !$is_viewing && !$is_enrolled) {
            enrol_try_internal_enrol(
                $this->course->get_id(),
                $user_id,
                $CFG->creatornewroleid
            );
        }
    }

    /**
     * @param int|null $user_id
     */
    public function self_enrol_as_student(int $user_id = null): void {
        global $USER;

        if(is_null($user_id)) {
            $user_id = $USER->id;
        }

        if (!is_enrolled($this->course->get_context(), $user_id)) {
            $this->do_self_enrol($this->course->get_id());
        }
    }

    /**
     * @param int $course_id
     * @return void
     */
    private function do_self_enrol(int $course_id): void {
        global $DB;

        if (!enrol_is_enabled('self')) {
            throw new coding_exception("Self enrolment is not enabled");
        }

        if (!$enrol = enrol_get_plugin('self')) {
            throw new coding_exception("Can not get self enrol plugin");
        }

        if (!$instances = $DB->get_records('enrol', ['enrol'=>'self', 'courseid'=>$course_id, 'status'=>ENROL_INSTANCE_ENABLED], 'sortorder,id ASC')) {
            throw new coding_exception("Self enrol for the course with ${$course_id} is not enabled");
        }
        $instance = reset($instances);
        $enrol->enrol_self($instance);
    }
}