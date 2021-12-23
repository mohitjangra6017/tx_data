<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\Controller;

use context;
use context_system;
use JsonSerializable;
use local_credly\Endpoint;
use stdClass;
use totara_mvc\admin_controller;
use totara_mvc\tui_view;
use totara_mvc\viewable;

class BadgeAdmin extends admin_controller
{
    protected $admin_external_page_name = 'credly_badges';

    /**
     * Override get_default_context() either returning a system or a specific context.
     *
     * @return context
     */
    protected function setup_context(): context
    {
        return context_system::instance();
    }

    /**
     * This is the default action and it can be overridden by the children.
     * If no action is passed to the render method this default action is called.
     * In this case it has to be defined in child classes.
     *
     * @return viewable|string|array|stdClass|JsonSerializable if it cannot be cast to a string the result will be json encoded
     */
    public function action()
    {
        if (!(new Endpoint())->isEnabled()) {
            print_error('err:credly_not_enabled', 'local_credly');
        }
        $view = tui_view::create('local_credly/pages/CredlyBadges');
        return $view;
    }
}