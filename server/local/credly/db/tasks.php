<?php

defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        'classname' => local_credly\Task\Issue::class,
        'blocking' => 0,
        'minute' => '*',
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*',
    ],
];