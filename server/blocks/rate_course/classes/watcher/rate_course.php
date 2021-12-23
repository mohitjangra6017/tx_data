<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace block_rate_course\watcher;

use coding_exception;
use container_workspace\watcher\redirect_watcher;
use container_perform\watcher\course;
use core_container\hook\base_redirect;

class rate_course {
    /**
     * @param base_redirect $hook
     * @throws coding_exception
     */
    public static function redirect_non_course(base_redirect $hook) {
        $type = \container_course\course::get_type();
        $container = $hook->get_container();

        if ($container->is_typeof($type)) {
            return;
        }

        redirect_watcher::redirect_to_workspace($hook);
        course::redirect_with_error($hook);
    }
}