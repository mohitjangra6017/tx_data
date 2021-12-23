<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

$watchers = [
    [
        'hookname' => \local_core\Hook\AfterConfigInit::class,
        'callback' => [\local_core\Watcher\ConfigInitWatcher::class, 'onConfigInit'],
        // INT_MAX means this will absolutely be the first watcher fired for this event. Very important this one is first.
        'priority' => PHP_INT_MAX,
    ],
    [
        'hookname' => \local_core\Hook\AdminTreeInitHook::class,
        'callback' => [\local_core\Watcher\AdminSettingsWatcher::class, 'onAdminTreeInit'],
        // INT_MAX means this will absolutely be the first watcher fired for this event. Very important this one is first.
        'priority' => PHP_INT_MAX,
    ],
    [
        'hookname' => \local_core\Hook\Email\PreSend::class,
        'callback' => [\local_core\Watcher\IcalEmailCancellationWatcher::class, 'onEmailPreSend'],
        // INT_MIN means this will absolutely be the last watcher fired for this event. Very important this one is last.
        'priority' => PHP_INT_MIN,
    ],
    [
        'hookname' => \local_core\Hook\AdminTreeInitHook::class,
        'callback' => [\local_core\Watcher\IcalEmailCancellationWatcher::class, 'onAdminSettings'],
    ],
    [
        'hookname' => \local_core\Hook\PreUpgrade::class,
        'callback' => [\local_core\Watcher\UpgradeWatcher::class, 'onPreUpgrade'],
        // INT_MAX means this will absolutely be the first watcher fired for this event. Very important this one is first
        // so that the core watcher function can start the transaction before any other watchers have made changes to the
        // database.
        'priority' => PHP_INT_MAX,
    ],
    [
        'hookname' => \local_core\Hook\PostUpgrade::class,
        'callback' => [\local_core\Watcher\UpgradeWatcher::class, 'onPostUpgrade'],
        // INT_MIN means this will absolutely be the last watcher fired for this event. Very important this one is last
        // to enable database changes made by any other watcher functions in other plugins to be rolled back as part of
        // the transaction if this was a dry run upgrade.
        'priority' => PHP_INT_MIN,
    ],
    [
        'hookname' => \core\hook\admin_setting_changed::class,
        'callback' => [\local_core\Hook\ReportBuilder\PostgresWorkMem::class, 'settingUpdated'],
    ],
];