<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\Watcher;

use core\hook\tenant_customizable_theme_settings;
use theme_kineo\SettingsResolver;

class TenantCustomisableSettingsWatcher
{
    public static function onTenantThemeSettingsHook(tenant_customizable_theme_settings $hook)
    {
        if ($hook->get_theme_config()->name !== 'kineo') {
            return;
        }

        $settings = SettingsResolver::getInstance()->getThemeSettings(true);

        // Always make sure tenant is valid, as this is here for the tenant customised on/off switch.
        $hookSettings = [
            'tenant' => '*'
        ];

        foreach ($settings->getSettings() as $setting) {
            $hookSettings[$setting->getTab()->getIdentifier()][] = $setting->getIdentifier();
        }

        $hook->set_customizable_settings($hookSettings);
    }
}