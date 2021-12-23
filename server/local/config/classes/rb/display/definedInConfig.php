<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon.Thornett
 */

namespace local_config\rb\display;

use totara_reportbuilder\rb\display\base;

class definedInConfig extends base {

    public static function display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report) {
        global $CFG;

        $extraFields = self::get_extrafields_row($row, $column);

        // Save ourselves fetching these strings potentially hundreds of times.
        static $yesString = null;
        static $noString = null;

        if ($yesString === null) {
            $yesString = get_string('yes');
        }

        if ($noString === null) {
            $noString = get_string('no');
        }

        $isCore = $extraFields->plugin == 'core';
        $configPHPSetting = $CFG->config_php_settings[$extraFields->name] ?? null;
        $coreDefined = $isCore && $configPHPSetting;

        $forcedPluginSettings = $CFG->forced_plugin_settings[$extraFields->plugin][$extraFields->name] ?? null;
        $pluginDefined = !$isCore && $forcedPluginSettings;

        if ($coreDefined || $pluginDefined) {
            return $yesString;
        }

        return $noString;
    }

    public static function is_graphable(\rb_column $column, \rb_column_option $option, \reportbuilder $report) {
        return false;
    }
}
