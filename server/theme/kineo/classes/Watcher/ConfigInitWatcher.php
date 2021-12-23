<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\Watcher;

use local_core\Hook\AfterConfigInit;

class ConfigInitWatcher
{
    public static function onConfigInit(AfterConfigInit $hook)
    {
        global $CFG;
        // Ensure that the Kineo Theme lib is loaded always as soon as possible.
        // This ensures that when core tries to cache the CSS, it has access to our functions.
        // @see theme_config::get_css_cache_key
        require_once $CFG->dirroot . '/theme/kineo/lib.php';
    }
}