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

namespace totara_contentmarketplace;

use core_component;
use core_plugin_manager;
use totara_contentmarketplace\plugininfo\contentmarketplace;

class learning_object_factory {
    /**
     * @var array
     */
    private $sync_action_classes;

    /**
     * learning_object_factory constructor.
     */
    private function __construct() {
        $this->sync_action_classes = [];
    }

    /**
     * Factory method to create a new instance.
     * @return static
     */
    public static function create(): self {
        return new learning_object_factory();
    }

    /**
     * @return void
     */
    public function sync(): void {
        $this->load_sync_action_classes();

        foreach ($this->sync_action_classes as $sync_action_class) {
             /** @var sync_action $action_class */
             $action_class = new $sync_action_class();

             if($action_class->initial_run()) {
                 continue;
             }

             $action_class->invoke();
        }
    }

    /**
     * @return void
     */
    private function load_sync_action_classes(): void {
        /** @var contentmarketplace[] $plugins */
        $plugins = core_plugin_manager::instance()->get_plugins_of_type('contentmarketplace');
        foreach ($plugins as $plugin) {
            if ($plugin->is_enabled()) {
                $this->sync_action_classes = core_component::get_namespace_classes(
                    'action',
                    sync_action::class,
                    $plugin->component
                );
            }
        }
    }


    /**
     * @return array
     */
    public function get_sync_action_classes(): array {
        return $this->sync_action_classes;
    }
}