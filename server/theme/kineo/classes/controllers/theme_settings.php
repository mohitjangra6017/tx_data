<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Johannes Cilliers <johannes.cilliers@totaralearning.com>
 * @package totara_tui
 */

namespace theme_kineo\controllers;

use core\theme\settings as core_theme_settings;
use theme_kineo\SettingsResolver;
use totara_mvc\admin_controller;
use totara_mvc\tui_view;
use totara_tenant\entity\tenant;

class theme_settings extends admin_controller {

    /**
     * @var string
     */
    protected $theme;

    /**
     * The tenant id, if one was provided.
     * @var int
     */
    protected $tenantid;

    /**
     * @inheritDoc
     */
    protected $layout = 'standard';

    /**
     * @inheritDoc
     */
    protected function setup_context(): \context {
        return \context_system::instance();
    }

    /**
     * @inheritDoc
     */
    protected function init_page_object() {
        global $SITE;

        parent::init_page_object();

        if (defined('PHPUNIT_TEST') && PHPUNIT_TEST) {
            // Don't set page options while under test as it will prevent
            // creating the controller multiple times in tests.
            return;
        }

        $page = $this->get_page();
        // Must set course before theme.
        // If we don't set it here, set_course() will throw an exception in
        // $page->initialise_theme_and_output().
        // We already set the context in the parent method, so calling this
        // won't change the context.
        $page->set_course($SITE);
        $page->force_theme('kineo');

    }

    /**
     * @inheritDoc
     */
    public function process(string $action = '') {
        global $CFG, $USER;

        // Get the theme name from parameter.
        $this->theme = $this->get_required_param('theme_name', PARAM_COMPONENT);
        $this->tenantid = $this->get_optional_param('tenant_id', null, PARAM_INT);

        // Set external admin page name.
        $this->admin_external_page_name = "{$this->theme}_editor";

        require_login(null, false);

        if (!is_null($this->tenantid)) {
            $tenant = tenant::repository()->find($this->tenantid);
            if (empty($tenant)) {
                throw new \moodle_exception('errorinvalidtenant', 'totara_tenant');
            }
            $context = \context_tenant::instance($this->tenantid);
        } else {
            $context = \context_system::instance();
        }
        if ($context->is_user_access_prevented()) {
            throw new \moodle_exception('accessdenied', 'admin', '', null, $context->id . ' prevented access');
        }

        $url = new \moodle_url('/totara/tui/classes/controllers/theme_settings.php', ['theme_name' => $this->theme]);

        if (!empty($USER->tenantid) && $USER->tenantid != $this->tenantid) {
            redirect(new \moodle_url($url, ['tenant_id' => $USER->tenantid]));
        }

        if (!empty($this->tenantid) && empty($USER->tenantid)) {
            $tenant = \core\record\tenant::fetch($this->tenantid);
            $categorycontext = \context_coursecat::instance($tenant->categoryid);
            require_capability('totara/tui:themesettings', $categorycontext);
            $this->get_page()->set_pagelayout('admin');
            $this->get_page()->set_title(get_site()->shortname);
            $this->get_page()->set_heading(get_site()->fullname);
        } else {
            require_once($CFG->libdir.'/adminlib.php');
            admin_externalpage_setup(
                'kineo_editor',
                '', // not used
                ['theme_name' => $this->theme],
                '',
                []
            );
        }

        $this->get_page()->set_url($url);
        $this->set_url($url);

        parent::process($action);
    }

    /**
     * @inheritDoc
     */
    public function action(): tui_view {
        global $CFG;

        $core_theme_settings = new core_theme_settings(\theme_config::load($this->theme), $this->tenant_id ?? 0);

        $props = [
            'theme' => $this->theme,
            'themeName' => get_string_manager()->string_exists('pluginname', 'theme_' . $this->theme)
                ? get_string('pluginname', 'theme_' . $this->theme)
                : null,
            'customizableTenantSettings' => $core_theme_settings->get_customizable_tenant_theme_settings(),
        ];

        // If tenant is selected then get selected tenant details.
        $tenant_id = $this->get_optional_param('tenant_id', null,PARAM_INT);
        if (!empty($tenant_id) && $CFG->tenantsenabled) {
            /** @var tenant $tenant */
            $tenant = tenant::repository()->find($tenant_id);
            if (empty($tenant)) {
                throw new \moodle_exception('errorinvalidtenant', 'totara_tenant');
            }

            // First confirm that the user has access to the specific tenant appearance settings.
            $categorycontext = \context_coursecat::instance($tenant->categoryid);
            require_capability('totara/tui:themesettings', $categorycontext);

            // Set up props for the component.
            $props['selectedTenantId'] = $tenant->id;
            $props['selectedTenantName'] = $tenant->name;
        } else {
            // User need capability to access site appearance settings.
            require_capability('totara/core:appearance', \context_system::instance());
        }

        $props['themeSettings'] = SettingsResolver::getInstance()->getThemeSettings(!empty($props['selectedTenantId']));

        $tui_view = tui_view::create('theme_kineo/pages/ThemeSettings', $props);
        $tui_view->set_title(get_string('theme_settings', 'totara_tui'));

        return $tui_view;
    }
}