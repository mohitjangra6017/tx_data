<?php
/**
 * This file is part of Totara Core
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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package mod_contentmarketplace
 */
namespace mod_contentmarketplace\controllers;

use context;
use core_container\factory;
use mod_contentmarketplace\event\course_module_viewed;
use mod_contentmarketplace\interactor\content_marketplace_interactor;
use mod_contentmarketplace\model\content_marketplace;
use mod_contentmarketplace\entity\content_marketplace as content_marketplace_entity;
use moodle_url;
use totara_mvc\controller;
use totara_mvc\tui_view;

class view extends controller {
    /**
     * @var content_marketplace
     */
    private $model;

    /**
     * @var string
     */
    protected $layout = 'legacynolayout';

    /**
     * view constructor.
     * @param int|null $cm_id
     */
    public function __construct(?int $cm_id = null) {
        $cm_id = $cm_id ?? $this->get_required_param('id', PARAM_INT);

        $this->model = content_marketplace::from_course_module_id($cm_id);

        $this->url = new moodle_url(
            '/mod/contentmarketplace/view.php',
            ['id' => $cm_id]
        );

        parent::__construct();
    }

    /**
     * @return context
     */
    protected function setup_context(): context {
        return $this->model->get_context();
    }

    protected function authorize(): void {
        parent::authorize();
        // Immediately reset the page layout, as the require_login() call re-enables the course blocks which we don't want here.
        $this->get_page()->set_pagelayout($this->layout);
    }

    /**
     * @return tui_view
     */
    public function action(): tui_view {
        global $USER;

        $interactor = new content_marketplace_interactor($this->model, $USER->id);
        $interactor->require_view();

        $subplugin = $this->model->activity_module_marketplace_component;

        $view = new tui_view(
            "{$subplugin}/pages/ActivityView",
            ['cm-id' => $this->model->get_cm_id(),]
        );

        $view->set_title(format_string($this->model->name));

        // Triggering a course module viewed event before the view is returned.
        $event = course_module_viewed::from_model($this->model, $USER->id);
        $event->add_record_snapshot('course', factory::from_id($this->model->course_id)->to_record());
        $event->add_record_snapshot(content_marketplace_entity::TABLE, $this->model->get_entity_record());
        $event->trigger();

        return $view;
    }
}