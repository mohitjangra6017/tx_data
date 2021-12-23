<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */
defined('MOODLE_INTERNAL') || die();

$watchers = [
    [
        'hookname' => \block_rate_course\hook\enrol::class,
        'callback' => [\block_rate_course\watcher\rate_course::class, 'redirect_non_course'],
    ],
    [
        'hookname' => \block_rate_course\hook\recommend::class,
        'callback' => [\block_rate_course\watcher\rate_course::class, 'redirect_non_course'],
    ],
    [
        'hookname' => \block_rate_course\hook\comment::class,
        'callback' => [\block_rate_course\watcher\rate_course::class, 'redirect_non_course'],
    ],
    [
        'hookname' => \block_rate_course\hook\delete_comment::class,
        'callback' => [\block_rate_course\watcher\rate_course::class, 'redirect_non_course'],
    ],
    [
        'hookname' => \block_rate_course\hook\delete_comment::class,
        'callback' => [\block_rate_course\watcher\rate_course::class, 'redirect_non_course'],
    ],
    [
        'hookname' => \block_rate_course\hook\delete_review::class,
        'callback' => [\block_rate_course\watcher\rate_course::class, 'redirect_non_course'],
    ],
    [
        'hookname' => \block_rate_course\hook\get_users::class,
        'callback' => [\block_rate_course\watcher\rate_course::class, 'redirect_non_course'],
    ],
    [
        'hookname' => \block_rate_course\hook\like_course::class,
        'callback' => [\block_rate_course\watcher\rate_course::class, 'redirect_non_course'],
    ],
    [
        'hookname' => \block_rate_course\hook\like_review::class,
        'callback' => [\block_rate_course\watcher\rate_course::class, 'redirect_non_course'],
    ],
    [
        'hookname' => \block_rate_course\hook\rate::class,
        'callback' => [\block_rate_course\watcher\rate_course::class, 'redirect_non_course'],
    ],
    [
        'hookname' => \block_rate_course\hook\unenrol::class,
        'callback' => [\block_rate_course\watcher\rate_course::class, 'redirect_non_course'],
    ],
];