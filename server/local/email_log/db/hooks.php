<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

defined('MOODLE_INTERNAL') || die();

$watchers = [
    [
        'hookname' => \local_core\Hook\Email\Sent::class,
        'callback' => [\local_email_log\Watcher\EmailSentWatcher::class, 'storeEmail'],
    ],
    [
        'hookname' => \local_core\Hook\Email\Failed::class,
        'callback' => [\local_email_log\Watcher\EmailFailedWatcher::class, 'handleFailedEmail'],
    ],
];