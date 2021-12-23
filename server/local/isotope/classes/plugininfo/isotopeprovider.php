<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_isotope\plugininfo;

use admin_root;
use admin_settingpage;
use core\plugininfo\base;
use part_of_admin_tree;

class isotopeprovider extends base
{
    /**
     * {@inheritdoc}
     */
    public function get_settings_section_name()
    {
        return 'isotopeprovider_' . $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function load_settings(part_of_admin_tree $adminroot, $parentnodename, $hassiteconfig)
    {
        /** @var admin_root $ADMIN May be used in settings.php */
        $ADMIN = $adminroot;

        if (!$this->is_installed_and_upgraded()) {
            return;
        }

        if (file_exists($this->full_path('settings.php'))) {
            $settings = new admin_settingpage($this->get_settings_section_name(), $this->displayname);
            include($this->full_path('settings.php'));
            $ADMIN->add($parentnodename, $settings);
        }
    }
}