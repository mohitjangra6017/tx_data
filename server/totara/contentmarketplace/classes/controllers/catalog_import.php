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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_contentmarketplace
 */

namespace totara_contentmarketplace\controllers;

use coding_exception;
use contentmarketplace_linkedin\interactor\catalog_import_interactor;
use context;
use context_coursecat;
use context_system;
use core_component;
use moodle_exception;
use moodle_url;
use totara_contentmarketplace\local;
use totara_contentmarketplace\plugininfo\contentmarketplace;
use totara_contentmarketplace\views\override_catalog_import_nav_breadcrumbs;
use totara_mvc\controller;
use totara_contentmarketplace\explorer as explorer_model;
use totara_mvc\tui_view;
use totara_mvc\view;

/**
 * Common logic across all explorer controller for sub plugins.
 */
class catalog_import extends controller {
    /**
    * @var string
    */
    protected $layout = 'noblocks';

    /**
    * @var contentmarketplace
    */
    protected $plugin;

    /**
    * @var explorer_model
    */
    protected $explorer;

    /**
    * explorer constructor.
    */
    public function __construct() {
        parent::__construct();

        $this->plugin = contentmarketplace::plugin($this->get_marketplace());
        $this->explorer = new explorer_model($this->get_marketplace(), $this->get_layout(), $this->get_category_id());
        $this->url = new moodle_url('/totara/contentmarketplace/explorer.php', ['marketplace' => $this->get_marketplace()]);
    }

    /**
     * @return context_system|context_coursecat
    */
    protected function setup_context(): context {
        $category_id = $this->get_category_id();
        return is_null($category_id) ? context_system::instance() : context_coursecat::instance($category_id);
    }

    /**
    * Checks and call require_login if parameter is set, can be overridden if special set up is needed
    *
    * @return void
    */
    protected function authorize(): void {
        parent::authorize();

        local::require_contentmarketplace();
        $this->check_plugin_enabled();

        /** @var context_coursecat|context_system $context */
        $context = $this->get_context();
        $interactor = new catalog_import_interactor();

        if (CONTEXT_COURSECAT === $context->contextlevel) {
            // If we are going to add courses via the course category
            // level context page, then we can check if the user has the
            // capability enabled for such context in order to view the page.
            $interactor->require_add_course_to_category($context);
        } else {
            $interactor->require_view_catalog_import_page();
        }
    }

    /**
     * @return mixed
     */
    public function action() {
        $class = $this->get_current_controller_class();

        return (new $class())->action();
    }

    /**
     * @return string
     */
    private function get_current_controller_class(): string {
        $classes = core_component::get_namespace_classes(
            'controllers',
            self::class,
            $this->plugin->component
        );

        if (empty($classes)) {
            throw new coding_exception("{$this->plugin->component} has to define explorer controller.");
        }

        if (count($classes) > 1) {
            throw new coding_exception("Only one catalog_import controller should be returned.");
        }


        $class = reset($classes);

        if (!is_subclass_of($class, self::class)) {
            throw new coding_exception("{$class} is not sub class of explorer");
        }

        return $class;
    }

    /**
     * Returns tui view for explorer controllers
     *
     * @param string $component
     * @param array $props
     * @return tui_view
     */
    public function create_tui_view(string $component, array $props = []): tui_view {
        return tui_view::create($component, $props);
    }

    /**
     * Returns view for all explorer controllers
     *
     * @param string $template
     * @param array $data
     * @return view
     */
    public function create_view(string $template, array $data = []): view {
        return view::create($template, $data);
    }

    /**
     * @return int|null
     */
    public function get_category_id(): ?int {
        return $this->get_optional_param('category', null, PARAM_INT);
    }

    /**
     * @return string
     */
    public function get_mode(): string {
        return $this->get_optional_param('mode', explorer_model::MODE_EXPLORE, PARAM_ALPHAEXT);
    }

    /**
     * @return string
     */
    public function get_marketplace(): string {
        return $this->get_required_param('marketplace', PARAM_ALPHA);
    }

    /**
     * @return void
     */
    private function check_plugin_enabled(): void {
        if (!isset($this->plugin)) {
            $this->plugin = contentmarketplace::plugin($this->get_marketplace());
        }

        if (!$this->plugin->is_enabled()) {
            throw new moodle_exception('error:disabledmarketplace', 'totara_contentmarketplace', '', $this->plugin->displayname);
        }
    }

    /**
     * @return bool
     */
    public function can_manage_marketplace_plugins(): bool {
        return has_capability('totara/contentmarketplace:config', $this->get_context());
    }

    /**
     * @return explorer_model
     */
    public function get_explorer(): explorer_model {
        return $this->explorer;
    }

    /**
     * @return contentmarketplace
     */
    public function get_plugin(): contentmarketplace {
        return $this->plugin;
    }
}