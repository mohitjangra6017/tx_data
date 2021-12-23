<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

function xmldb_local_realtimecohortassignment_upgrade($oldVersion = 0)
{
    global $DB;

    if ($oldVersion < 2019102301) {
        $config = get_config('local_realtimecohortassignment');
        if (isset($config->event_userlogin_first) || isset($config->event_userlogin_every)) {
            $newSetting = \local_realtimecohortassignment\observer::USER_LOGIN_EVENT_NONE;
            if (!empty($config->event_userlogin_every)) {
                $newSetting = \local_realtimecohortassignment\observer::USER_LOGIN_EVENT_EVERY;
            } else if (!empty($config->event_userlogin_first)) {
                $newSetting = \local_realtimecohortassignment\observer::USER_LOGIN_EVENT_FIRST;
            }
            set_config('event_userlogin', $newSetting, 'local_realtimecohortassignment');
            unset_config('event_userlogin_first', 'local_realtimecohortassignment');
            unset_config('event_userlogin_every', 'local_realtimecohortassignment');
        }

        upgrade_plugin_savepoint(true, 2019102301, 'local', 'realtimecohortassignment');
    }

    return true;
}