<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\Watcher;

use core\theme\helper;
use core\theme\settings as theme_settings;
use local_core\Hook\ThemeCssVariables;
use theme_kineo\Settings\Image;
use theme_kineo\SettingsResolver;

class CssVariablesWatcher
{
    public static function onCssOutput(ThemeCssVariables $hook)
    {
        $theme = $hook->getTheme();
        // Only affect the Kineo theme. Otherwise, change nothing, and let it continue with core.
        if ($theme->name !== 'kineo') {
            return;
        }

        $outputSettings = [];
        $settings = SettingsResolver::getInstance()->getThemeSettings()->getSettings();

        foreach ($hook->getCategories() as $category) {
            if ($category['name'] === 'custom') {
                continue;
            }
            foreach ($category['properties'] as $property) {
                if ($property['type'] !== 'value') {
                    continue;
                }
                if (strpos($property['value'], '@') === 0) {
                    $value = substr($property['value'], 1);
                    $outputSettings[] = "--{$property['name']}: var(--{$value});";
                } else {
                    $outputSettings[] = "--{$property['name']}: {$property['value']};";
                }
            }
        }

        foreach ($hook->getThemeFiles() as $themeFile) {

            $url = $themeFile->get_current_url();
            $setting = SettingsResolver::getInstance()->getThemeSettings()->getSetting($themeFile->get_ui_key());
            if ($setting === null || !$setting->getOptions()[Image::IN_CSS_KEY]) {
                continue;
            }
            if ($url) {
                $outputSettings[] = "--{$themeFile->get_ui_key()}: '{$url->out()}';";
            } else {
                $outputSettings[] = "--{$themeFile->get_ui_key()}: false;";
            }
        }

        $format = <<<CSS
:root{
%s
}
CSS;

        $hook->setCss(sprintf($format, implode(PHP_EOL, $outputSettings)));
        $hook->setShouldSkipCore(true);
    }
}