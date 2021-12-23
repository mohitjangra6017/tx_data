<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace theme_kineo\Watcher;


use local_core\Hook\AdminTreeInitHook;

class AdminTreeInitWatcher
{
    public static function pruneAdminTree(AdminTreeInitHook $hook)
    {
        $admin = $hook->getAdminRoot();
        $admin->prune('themesettingkineo');
    }
}