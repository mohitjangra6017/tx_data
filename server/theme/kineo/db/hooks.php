<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

$watchers = [
    [
        'hookname' => \local_core\Hook\ThemeCssVariables::class,
        'callback' => [\theme_kineo\Watcher\CssVariablesWatcher::class, 'onCssOutput'],
    ],
    [
        'hookname' => \core\hook\tenant_customizable_theme_settings::class,
        'callback' => [\theme_kineo\Watcher\TenantCustomisableSettingsWatcher::class, 'onTenantThemeSettingsHook'],
    ],
    [
        'hookname' => \local_core\Hook\BlockEditForm::class,
        'callback' => [\theme_kineo\Watcher\BlockEditFormWatcher::class, 'addCustomElements'],
    ],
    [
        'hookname' => \local_core\Hook\AdminTreeInitHook::class,
        'callback' => [\theme_kineo\Watcher\AdminTreeInitWatcher::class, 'pruneAdminTree'],
    ],
    [
        'hookname' => \local_core\Hook\AfterConfigInit::class,
        'callback' => [\theme_kineo\Watcher\ConfigInitWatcher::class, 'onConfigInit'],
    ]
];