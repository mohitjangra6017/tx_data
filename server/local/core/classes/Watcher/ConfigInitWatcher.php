<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Watcher;

use core\session\manager;
use local_core\Hook\AfterConfigInit;

class ConfigInitWatcher
{
    public static function onConfigInit(AfterConfigInit $hook)
    {
        global $CFG;
        if (file_exists($CFG->srcroot . '/vendor/autoload.php')) {
            require_once $CFG->srcroot . '/vendor/autoload.php';
        }

        self::maintenanceModeBypass();
    }

    /**
     * Allow access to the site if Maintenance Mode (MM) is enabled, our user has the capability to access the site during MM, and we are logging in as another user.
     * This can be enabled by simply adding `$CFG->allowmaintenancebypass = true;` to the config.php file.
     */
    private static function maintenanceModeBypass(): void
    {
        global $CFG;

        if (
            $CFG->maintenance_enabled
            && manager::is_loggedinas()
            && has_capability('moodle/site:maintenanceaccess', \context_system::instance(), manager::get_realuser())
        ) {
            $CFG->maintenance_enabled = 0;
        }
    }
}