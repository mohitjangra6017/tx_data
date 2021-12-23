<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Hook;

use totara_core\hook\base;

class AfterConfigInit extends base
{
    public function isAjaxRequest(): bool
    {
        return is_ajax_request($_SERVER);
    }

    public function isCliRequest(): bool
    {
        return defined('CLI_SCRIPT') && CLI_SCRIPT;
    }

    public function isPluginFileRequest(): bool
    {
        return strpos($_SERVER['SCRIPT_FILENAME'], 'pluginfile.php') !== false;
    }
}