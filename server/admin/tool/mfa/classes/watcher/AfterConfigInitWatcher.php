<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Thornett <simon.thornett@kineo.com>
 */

namespace tool_mfa\watcher;

use local_core\Hook\AfterConfigInit;
use tool_mfa\manager;

class AfterConfigInitWatcher
{
    public static function onConfigInit(AfterConfigInit $hook)
    {
        global $CFG, $SESSION;

        // Store in $CFG, $SESSION not present at this point.
        if (PHPUNIT_TEST) {
            $CFG->mfa_config_hook_test = true;
        }

        if (isloggedin() && !isguestuser()) {
            if (empty($SESSION->tool_mfa_authenticated)) {
                manager::require_auth();
            }
        }

    }
}