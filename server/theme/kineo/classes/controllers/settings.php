<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\controllers;

use JsonSerializable;
use moodle_url;
use stdClass;
use totara_mvc\viewable;

class settings extends \totara_tui\controllers\settings
{
    /**
     * This changes the core behaviour so we can redirect to our own settings pages,
     * as we need to be able to define different TUI templates.
     *
     * @return viewable|string|array|stdClass|JsonSerializable if it cannot be cast to a string the result will be json encoded
     */
    public function action()
    {
        global $CFG;

        // If multi-tenancy is enabled then we display tenants otherwise go straight to settings.
        // TODO: Keep the theme name here, we might need it for extended themes.
        if (!empty($CFG->tenantsenabled)) {
            $tenants_url = new moodle_url("/theme/kineo/theme_tenants.php", ['theme_name' => $this->theme]);
            redirect($tenants_url->out());
        } else {
            $settings_url = new moodle_url("/theme/kineo/theme_settings.php", ['theme_name' => $this->theme]);
            redirect($settings_url->out());
        }
    }

}