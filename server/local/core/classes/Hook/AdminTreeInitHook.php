<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Hook;


use admin_root;
use admin_setting;
use admin_settingpage;
use InvalidArgumentException;
use totara_core\hook\base;

class AdminTreeInitHook extends base
{
    /** @var admin_root */
    private $adminRoot;

    public function __construct(admin_root $adminRoot)
    {
        $this->adminRoot = $adminRoot;
    }

    /**
     * @return admin_root
     */
    public function getAdminRoot(): admin_root
    {
        return $this->adminRoot;
    }

    /**
     * Adds a new admin setting, inserted directly after the given existing setting.
     * @param admin_settingpage $page
     * @param admin_setting $setting
     * @param string $afterSetting
     */
    public function addNewSettingAfter(admin_settingpage $page, admin_setting $setting, string $afterSetting): void
    {
        $currentSettings = (array)$page->settings;

        if (!isset($currentSettings[$afterSetting])) {
            return;
        }

        $keyPosition = array_search($afterSetting, array_keys($currentSettings));
        $this->addNewSettingAtPosition($page, $setting, $keyPosition + 1);
    }

    /**
     * @param admin_settingpage $page
     * @param admin_setting $setting
     * @param string $beforeSetting
     */
    public function addNewSettingBefore(admin_settingpage $page, admin_setting $setting, string $beforeSetting): void
    {
        $currentSettings = (array)$page->settings;

        if (!isset($currentSettings[$beforeSetting])) {
            return;
        }

        $keyPosition = array_search($beforeSetting, array_keys($currentSettings));
        $this->addNewSettingAtPosition($page, $setting, $keyPosition);
    }

    /**
     * Adds a new setting at the given position in the list of settings and headings.
     * @param admin_settingpage $page
     * @param admin_setting $setting
     * @param int $position
     */
    public function addNewSettingAtPosition(admin_settingpage $page, admin_setting $setting, int $position): void
    {
        $beforeSettings = array_slice((array)$page->settings, 0, $position);
        $afterSettings = array_slice((array)$page->settings, $position);
        $page->settings = array_merge($beforeSettings, [$setting], $afterSettings);
    }
}