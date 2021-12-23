<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Thornett <simon.thornett@kineo.com>
 */

namespace local_core\Watcher;

use admin_root;
use admin_setting_heading;
use admin_settingpage;
use local_core\Hook\AdminTreeInitHook;

class AdminSettingsWatcher
{
    private static $defaultDisabledAdminPages = [
        'cacheconfig',
        'cachestore_apcu_settings',
        'cachestore_memcached_settings',
        'cachestore_mongodb_settings',
        'cachestore_redis_settings',
        'cachetestperformance',
        'cleanup',
        'debugging',
        'environment',
        'experimentalsettings',
        'externalservices',
        'http',
        'httpsecurity',
        'local_demo',
        'machine_learning_environment',
        'manageantiviruses',
        'managelocalplugins',
        'managemfa',
        'managetools',
        'messageinbound_handlers',
        'messageinbound_mailsettings',
        'ml_setting_recommender',
        'outgoingmailconfig',
        'performance',
        'phpinfo',
        'reportperformance',
        'sessionhandling',
        'scheduledtasks',
        'stats',
        'supportcontact',
        'systempaths',
        'systempaths',
        'testclient',
        'tool_filetypes',
        'toolbehat',
        'tooldbtransfer',
        'toolgeneratorcourse',
        'toolgeneratortestplan',
        'toolphpunit',
        'tooltemplatelibrary',
        'toolxmld',
        'totara_tui_settings',
        'totararegistration',
        'webserviceprotocols',
        'local_core',
        'local_backup'
    ];

    /**
     * @param AdminTreeInitHook $hook
     */
    public static function onAdminTreeInit(AdminTreeInitHook $hook)
    {
        global $CFG, $USER;

        $admin = $hook->getAdminRoot();
        self::injectDisclaimerSetting($hook, $admin);

        // Defining the hosting admin turns on this functionality in its entirety.
        if (empty($CFG->hostingadminid)) {
            return;
        }

        // Only the hosting admin is not affected by these admin changes.
        if ($CFG->hostingadminid == $USER->id) {
            return;
        }

        // First remove any pages that are allowed from the default list.
        $pages = static::$defaultDisabledAdminPages;
        if (isset($CFG->allowedadminpages) && is_array($CFG->allowedadminpages)) {
            $pages = array_intersect($pages, $CFG->allowedadminpages);
        }

        // Then add in any pages that are specifically blocked.
        if (isset($CFG->blockedadminpages) && is_array($CFG->blockedadminpages)) {
            $pages = array_merge($pages, $CFG->blockedadminpages);
        }

        // Now go through the admin menu and remove all the pages that are blocked.
        foreach ($pages as $page) {
            $admin->prune($page);
        }
    }

    /**
     * Add disclaimer heading below the main heading
     * @param AdminTreeInitHook $hook
     * @param admin_root $admin
     */
    private static function injectDisclaimerSetting(AdminTreeInitHook $hook, admin_root $admin): void
    {
        $currentPage = $admin->locate('additionalhtml');

        if (!$currentPage instanceof admin_settingpage) {
            return;
        }

        $hook->addNewSettingAfter(
            $currentPage,
            new admin_setting_heading(
                'additionalhtml_disclaimer',
                get_string('additionalhtml_disclaimer_heading', 'local_core'),
                get_string('additionalhtml_disclaimer_desc', 'local_core')
            ),
            'additionalhtml_heading'
        );
    }
}