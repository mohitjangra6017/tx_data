<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Thornett <simon.thornett@kineo.com>
 */

namespace tool_mfa\watcher;

use local_core\Hook\AfterRequireLogin;
use tool_mfa\manager;

class AfterRequireLoginWatcher
{
    public static function onLoginRequired(AfterRequireLogin $hook)
    {
        global $SESSION;

        if (PHPUNIT_TEST) {
            $SESSION->mfa_login_hook_test = true;
        }

        if (empty($SESSION->tool_mfa_authenticated)) {
            manager::require_auth(
                $hook->courseOrId, $hook->autoLoginGuest, $hook->cm, $hook->setWantsUrlToMe, $hook->preventRedirect
            );
        }
    }
}