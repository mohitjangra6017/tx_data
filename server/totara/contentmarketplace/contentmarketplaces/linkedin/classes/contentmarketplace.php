<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Simon Coggins <simon.coggins@totaralearning.com>
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

namespace contentmarketplace_linkedin;

use moodle_url;
use totara_contentmarketplace\local\contentmarketplace\contentmarketplace as contentmarketplace_parent;
use totara_contentmarketplace\plugininfo\contentmarketplace as contentmarketplace_plugininfo;
use totara_tui\output\component;

final class contentmarketplace extends contentmarketplace_parent {

    public $name = 'linkedin';

    /**
     * Returns the URL for the plugin.
     *
     * @return string
     */
    public function url() {
        return 'https://www.linkedin.com/learning';
    }

    /**
     * Returns the path to a page used to create the course(es), relative to the site root.
     *
     * @return string
     */
    public function course_create_page() {
        // For the LinkedIn marketplace, we rely on GraphQL mutations rather than a dedicated page for creating the actual courses.
        return '';
    }

    /**
     * Get the HTML to display for the description of this marketplace.
     *
     * @return string
     */
    public function get_description_html(): string {
        global $OUTPUT;

        $is_plugin_enabled = contentmarketplace_plugininfo::plugin($this->name)->is_enabled();
        $browse_url = new moodle_url('/totara/contentmarketplace/explorer.php?marketplace=linkedin');

        return $OUTPUT->render_from_template('contentmarketplace_linkedin/plugin_description', [
            'is_plugin_enabled' => $is_plugin_enabled,
            'browse_url' => $browse_url,
        ]);
    }

    /**
     * @param null|string $tab
     * @return string|moodle_url
     */
    public function settings_url($tab = null) {
        $plugin_info = contentmarketplace_plugininfo::plugin($this->name);
        $section_name = $plugin_info->get_settings_section_name();

        return new moodle_url("/admin/settings.php", ['section' => $section_name]);
    }

    /**
     * Return the Vue component for rendering a special modal that requests a beta access code before the plugin can be enabled.
     * @deprecated since Totara 15.0
     *
     * @param string $label
     * @return string HTML snippet with the setup code.
     */
    public function get_setup_html($label): string {
        // \contentmarketplace_linkedin\contentmarketplace::get_setup_html() is deprecated and should not be used
        // This is only necessary for the LinkedIn Learning integration beta, and will be removed in Totara 16.0
        global $OUTPUT;

        if (!contentmarketplace_plugininfo::plugin($this->name)->has_never_been_enabled()) {
            // Plugin has been configured in the past, so don't need to show the beta setup link.
            return '';
        }

        return $OUTPUT->render(new component('contentmarketplace_linkedin/components/EnableBeta'));
    }

}