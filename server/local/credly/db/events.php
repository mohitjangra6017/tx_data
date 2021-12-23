<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\totara_program\event\program_deleted',
        'callback' => '\local_credly\Observer\Learning::onProgramDeleted',
    ],
    [
        'eventname' => '\core\event\course_deleted',
        'callback' => '\local_credly\Observer\Learning::onCourseDeleted',
    ],
    [
        'eventname' => '\totara_program\event\program_completed',
        'callback' => '\local_credly\Observer\Learning::onCertificationCompleted',
    ],
];