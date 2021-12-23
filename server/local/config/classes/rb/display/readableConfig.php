<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon.Thornett
 */

namespace local_config\rb\display;

use admin_category;
use admin_setting;
use admin_setting_configcheckbox;
use admin_setting_heading;
use admin_settingpage;
use lang_string;
use part_of_admin_tree;
use stdClass;
use totara_reportbuilder\rb\display\base;

class readableConfig extends base {

    protected static array $cachedSettings = [];

    public static function display($value, $format, stdClass $row, \rb_column $column, \reportbuilder $report) {
        global $CFG;
        require_once($CFG->dirroot . '/lib/adminlib.php');

        $extraFields = self::get_extrafields_row($row, $column);

        if (empty(static::$cachedSettings)) {
            $adminRoot = admin_get_root(true);
            self::filterNodes($adminRoot->get_children());
        }

        if (!isset(static::$cachedSettings[$extraFields->plugin][$extraFields->name])) {
            return $value;
        }

        $setting = static::$cachedSettings[$extraFields->plugin][$extraFields->name];

        if ($extraFields->text == "1") {
            return $setting->visibleName;
        }

        if ($extraFields->default == "1") {
            return is_array($setting->defaultSettingString) ? implode($setting->defaultSettingString) : $setting->defaultSettingString;
        }

        if ($extraFields->log == "1") {
            return isset($setting->choices) && $value !== null ? $setting->choices[$value] : $value;
        }

        return is_array($setting->existingSettingString) ? implode($setting->existingSettingString) : $setting->existingSettingString;
    }

    public static function is_graphable(\rb_column $column, \rb_column_option $option, \reportbuilder $report) {
        return false;
    }

    /**
     * @param part_of_admin_tree[] $nodes
     */
    private static function filterNodes(array $nodes)
    {
        // Save ourselves fetching these strings potentially hundreds of times.
        static $strings = [];
        if (empty($strings)) {
            $strings = [
                'checkboxyes' => get_string('checkboxyes', 'admin'),
                'checkboxno' => get_string('checkboxno', 'admin'),
                'coresystem' => get_string('coresystem', 'local_config'),
            ];
        }

        foreach ($nodes as $childNode) {

            if ($childNode instanceof admin_category && !empty($childNode->get_children())) {
                self::filterNodes($childNode->get_children());
                continue;
            }

            if (!$childNode instanceof admin_settingpage || empty($childNode->settings)) {
                continue;
            }

            $processed = new stdClass();
            $processed->name = $childNode->name;
            $processed->visibleName = self::getVisibleName($childNode->visiblename);

            foreach ($childNode->settings as $setting) {

                if (!$setting instanceof admin_setting || $setting instanceof admin_setting_heading) {
                    continue;
                }

                $processedSetting = new stdClass();
                $processedSetting->name = $setting->name;
                $processedSetting->pluginDisplayName = $processed->visibleName;
                $processedSetting->visibleName = self::getVisibleName($setting->visiblename);
                $processedSetting->defaultSetting = $setting->get_defaultsetting();
                $processedSetting->existingSetting = $setting->get_setting();

                if ($setting instanceof admin_setting_configcheckbox) {

                    $processedSetting->choices[1] = $strings['checkboxyes'];
                    $processedSetting->choices[0] = $strings['checkboxno'];

                    $processedSetting->defaultSettingString =
                        $processedSetting->defaultSetting
                            ? $strings['checkboxyes']
                            : $strings['checkboxno'];

                    $processedSetting->existingSettingString =
                        $processedSetting->existingSetting
                            ? $strings['checkboxyes']
                            : $strings['checkboxno'];

                } else if (
                    is_array($processedSetting->defaultSetting) ||
                    (isset($setting->choices) && is_array($setting->choices))
                ) {

                    if (empty($setting->choices) || empty($processedSetting->existingSetting)) {
                        $processedSetting->defaultSettingString = null;
                        $processedSetting->existingSettingString = null;
                        continue;
                    }
                    if (!empty($setting->choices)) {
                        $processedSetting->choices = $setting->choices;
                    }

                    $processedSetting->defaultSettingString = self::translateToString($setting->defaultsetting, $setting->choices);
                    $processedSetting->existingSettingString = self::translateToString($processedSetting->existingSetting, $setting->choices);
                } else {
                    $processedSetting->existingSettingString = $processedSetting->existingSetting;
                    $processedSetting->defaultSettingString = $processedSetting->defaultSetting;
                }

                static::$cachedSettings[$setting->plugin ?: 'core'][$setting->name] = $processedSetting;
            }
        }
    }

    /**
     * @param string|lang_string $visibleName
     * @return string
     */
    private static function getVisibleName($visibleName): string
    {
        if ($visibleName instanceof lang_string) {
            return $visibleName->out();
        }

        return $visibleName;
    }

    private static function translateToString($settings, array $choices): array
    {
        $settings = !is_array($settings) ? [$settings => $settings] : $settings;

        $translate = [];

        foreach ($settings as $settingKey => $settingVal) {

            if (isset($choices[$settingKey])) {
                $translate[$settingKey] = self::getVisibleName($choices[$settingKey]);
            } else if (isset($choices[$settingVal])) {
                $translate[$settingKey] = self::getVisibleName($choices[$settingVal]);
            } else if (isset($choices['room' . '_' . $settingKey])) {
                $translate[$settingKey] = self::getVisibleName($choices['room_' . $settingKey]);
            } else {
                $translate[$settingKey] = $settingVal;
            }
        }

        return $translate;
    }

}
