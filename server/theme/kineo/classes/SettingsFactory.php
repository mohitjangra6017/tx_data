<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo;

use theme_kineo\Settings\Colour;
use theme_kineo\Settings\Dropdown;
use theme_kineo\Settings\Editor;
use theme_kineo\Settings\Image;
use theme_kineo\Settings\Setting;
use theme_kineo\Settings\Tab;

class SettingsFactory
{
    public static function createSetting(object $settingsObject, Tab $tab): Setting
    {
        switch ($settingsObject->type) {
            case Setting::TYPE_DROPDOWN:
                $class = Dropdown::class;
                break;

            case Setting::TYPE_COLOUR:
                $class = Colour::class;
                break;

            case Setting::TYPE_IMAGE:
                $class = Image::class;
                break;

            case Setting::TYPE_EDITOR:
                $class = Editor::class;
                break;

            default:
                $class = Setting::class;
                break;
        }

        return new $class(
            $settingsObject->identifier,
            $settingsObject->type,
            $settingsObject->default ?? null,
            $tab,
            $settingsObject->heading ?? null,
            $settingsObject->tenant_configurable ?? false,
            $settingsObject->options ?? []
        );
    }
}