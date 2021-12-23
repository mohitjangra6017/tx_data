<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */
defined('MOODLE_INTERNAL') || die();

$watchers = [
    [
        'hookname' => \block_course_recommendations\hook\delete::class,
        'callback' => [\block_course_recommendations\watcher\course_recommendations::class, 'redirect_non_course'],
    ],
    [
        'hookname' => \block_course_recommendations\hook\disable::class,
        'callback' => [\block_course_recommendations\watcher\course_recommendations::class, 'redirect_non_course'],
    ],
    [
        'hookname' => \block_course_recommendations\hook\recommenders::class,
        'callback' => [\block_course_recommendations\watcher\course_recommendations::class, 'redirect_non_course'],
    ],
];
