<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\mod_assessment\event\stage_completed',
        'callback' => '\mod_assessment\observer::stage_completed',
    ],
    [
        'eventname' => \mod_assessment\event\attempt_created::class,
        'callback' => '\mod_assessment\observer::attempt_created',
    ],
    [
        'eventname' => '\core\event\user_enrolment_created',
        'callback' => '\mod_assessment\observer::user_enrolment_created',
    ],
    [
        'eventname' => '\core\event\cohort_deleted',
        'callback' => '\mod_assessment\observer::cohort_deleted',
    ],
    [
        'eventname' => '\core\event\cohort_member_added',
        'callback' => '\mod_assessment\observer::cohort_member_added',
    ],
    [
        'eventname' => '\core\event\cohort_member_removed',
        'callback' => '\mod_assessment\observer::cohort_member_removed',
    ],
    [
        'eventname' => '\core\event\group_member_added',
        'callback' => '\mod_assessment\observer::group_member_added',
    ],
    [
        'eventname' => '\core\event\group_member_removed',
        'callback' => '\mod_assessment\observer::group_member_removed',
    ],
    [
        'eventname' => '\core\event\role_assigned',
        'callback' => '\mod_assessment\observer::role_assigned',
    ],
    [
        'eventname' => '\core\event\role_unassigned',
        'callback' => '\mod_assessment\observer::role_unassigned',
    ],
    [
        'eventname' => '\totara_job\event\job_assignment_updated',
        'callback' => '\mod_assessment\observer::job_assignment_updated',
    ],
    [
        'eventname' => '\totara_job\event\job_assignment_deleted',
        'callback' => '\mod_assessment\observer::job_assignment_updated',
    ],
    [
        'eventname' => '\core\event\user_deleted',
        'callback' => '\mod_assessment\observer::user_deleted',
    ],
    [
        'eventname' => '\core\event\user_updated',
        'callback' => '\mod_assessment\observer::user_updated',
    ],
    [
        'eventname' => '\core\event\user_loggedin',
        'callback' => '\mod_assessment\observer::user_loggedin',
    ],
];
