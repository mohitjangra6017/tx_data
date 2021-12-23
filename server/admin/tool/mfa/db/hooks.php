<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Thornett <simon.thornett@kineo.com>
 */

$watchers = [
    [
        'hookname' => \local_core\Hook\AfterRequireLogin::class,
        'callback' => [\tool_mfa\watcher\AfterRequireLoginWatcher::class, 'onLoginRequired'],
        'priority' => 100,
    ],
    [
        'hookname' => \local_core\Hook\AfterConfigInit::class,
        'callback' => [\tool_mfa\watcher\AfterConfigInitWatcher::class, 'onConfigInit'],
        'priority' => 100,
    ],
];