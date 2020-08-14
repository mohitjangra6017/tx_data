<?php
/**
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
 * @author Fabian Derschatta <fabian.derschatta@totaralearning.com>
 * @package mod_perform
 */

use core\event\cohort_member_added;
use core\event\cohort_member_removed;
use mod_perform\event\participant_instance_progress_updated;
use mod_perform\event\participant_section_progress_updated;
use mod_perform\event\subject_instance_activated;
use mod_perform\event\subject_instance_progress_updated;
use mod_perform\observers\participant_instance_availability;
use mod_perform\observers\participant_instance_progress;
use mod_perform\observers\participant_section_availability;
use mod_perform\observers\participant_section_progress;
use mod_perform\observers\subject_instance_availability;
use mod_perform\observers\subject_instance_manual_status;
use mod_perform\observers\track_assignment_user_groups;
use totara_cohort\event\members_updated;

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => members_updated::class,
        'callback' => track_assignment_user_groups::class.'::cohort_updated',
    ],
    [
        'eventname' => cohort_member_added::class,
        'callback' => track_assignment_user_groups::class.'::cohort_updated',
    ],
    [
        'eventname' => cohort_member_removed::class,
        'callback' => track_assignment_user_groups::class.'::cohort_updated',
    ],
    [
        'eventname' => participant_section_progress_updated::class,
        'callback' => [participant_section_availability::class, 'close_completed_section_availability'],
    ],
    [
        'eventname' => participant_section_progress_updated::class,
        'callback' => participant_section_progress::class.'::progress_updated',
    ],
    [
        'eventname' => participant_instance_progress_updated::class,
        'callback' => participant_instance_progress::class.'::progress_updated',
    ],
    [
        'eventname' => participant_instance_progress_updated::class,
        'callback' => participant_instance_availability::class.'::close_completed_participant_instance',
    ],
    [
        'eventname' => subject_instance_progress_updated::class,
        'callback' => subject_instance_availability::class.'::close_completed_subject_instance',
    ],
    [
        'eventname' => subject_instance_activated::class,
        'callback' => subject_instance_manual_status::class.'::subject_instance_activated',
    ],
    [
        'eventname' => hierarchy_organisation\event\organisation_deleted::class,
        'callback' => track_assignment_user_groups::class.'::organisation_deleted',
    ],
    [
        'eventname' => hierarchy_position\event\position_deleted::class,
        'callback' => track_assignment_user_groups::class.'::position_deleted',
    ],
    [
        'eventname' => totara_job\event\job_assignment_created::class,
        'callback' => track_assignment_user_groups::class.'::job_assignment_updated',
    ],
    [
        'eventname' => totara_job\event\job_assignment_updated::class,
        'callback' => track_assignment_user_groups::class.'::job_assignment_updated',
    ],
    [
        'eventname' => totara_job\event\job_assignment_deleted::class,
        'callback' => track_assignment_user_groups::class.'::job_assignment_updated',
    ],
];
