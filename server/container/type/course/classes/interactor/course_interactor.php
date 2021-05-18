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
 * @package container_course
 */
namespace container_course\interactor;

use container_course\course;
use core_container\factory;
use moodle_exception;

class course_interactor {
    /**
     * @var int
     */
    private $actor_id;

    /**
     * @var course
     */
    private $course;

    /**
     * course_interactor constructor.
     * @param course   $course
     * @param int|null $user_id
     */
    public function __construct(course $course, ?int $user_id = null) {
        global $USER;

        $this->course = $course;
        $this->actor_id = $user_id ?? $USER->id;
    }

    /**
     * @param int      $course_id
     * @param int|null $user_id
     * @return course_interactor
     */
    public static function from_course_id(int $course_id, ?int $user_id = null): course_interactor {
        /** @var course $course */
        $course = factory::from_id($course_id);
        return new static($course, $user_id);
    }

    /**
     * External view.
     * Viewing the course as non participant of the course. The actor does not
     * have to enrolled into the course to view it.
     *
     * @return bool
     */
    public function can_view(): bool {
        global $CFG;
        if (!function_exists('totara_course_is_viewable')) {
            require_once("{$CFG->dirroot}/totara/core/totara.php");
        }

        return totara_course_is_viewable($this->course->id, $this->actor_id);
    }

    /**
     * @return void
     */
    public function require_view(): void {
        if (!$this->can_view()) {
            throw new moodle_exception('error:course_hidden', 'container_course');
        }
    }

    /**
     * @return void
     */
    public function require_access(): void {
        if (!$this->can_access()) {
            throw new moodle_exception('error:course_access', 'container_course');
        }
    }

    /**
     * Checks if the user is able to access to course.
     * This is different from view. View allow you to view the course item
     * in find learning, or view the course enrol page.
     *
     * Access means that user is able to access the course after all thet enrolments
     * are resolved.
     *
     * @return bool
     */
    public function can_access(): bool {
        $course_record = $this->course->to_record();
        return can_access_course($course_record, $this->actor_id);
    }
}