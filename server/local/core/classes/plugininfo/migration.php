<?php

namespace local_core\plugininfo;

use core\plugininfo\base;

/**
 *
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */
class migration extends base
{
    /**
     * Should there be a way to uninstall the plugin via the administration UI.
     *
     * By default uninstallation is not allowed, plugin developers must enable it explicitly!
     *
     * @return bool
     */
    public function is_uninstall_allowed()
    {
        return true;
    }
}