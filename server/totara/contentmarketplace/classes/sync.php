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

use null_progress_trace;
use progress_trace;
use totara_contentmarketplace\sync\external_sync;
use totara_contentmarketplace\sync\sync_action;
use totara_core\http\client;
use core_component;
use core_plugin_manager;
use totara_contentmarketplace\plugininfo\contentmarketplace;

class sync {
    /**
     * @var array
     */
    private $sync_action_classes;

    /**
     * @var client
     */
    private $client;

    /**
     * @var progress_trace
     */
    private $trace;

    /**
     * sync constructor.
     *
     * @param client              $client
     * @param progress_trace|null $trace
     */
    public function __construct(client $client, ?progress_trace $trace = null) {
        if (null === $trace) {
            $trace = new null_progress_trace();
        }

        $this->sync_action_classes = [];
        $this->client = $client;
        $this->trace = $trace;
    }

    /**
     * @param bool $initial_run
     * @return void
     */
    public function execute(bool $initial_run): void {
        $actions = $this->get_sync_actions($initial_run);
        foreach ($actions as $action) {
            $action->set_trace($this->trace);
            $action->invoke();
        }
    }

    /**
     * @param bool $initial_run
     * @return sync_action[]
     */
    private function get_sync_actions(bool $initial_run): array {
        $this->load_sync_action_classes();
        $actions = [];

        foreach ($this->sync_action_classes as $action_class) {
            $action = new $action_class($initial_run);

            if ($action instanceof external_sync) {
                $action->set_api_client($this->client);
            }

            $actions[] = $action;
        }

        return $actions;
    }


    /**
     * @return void
     */
    private function load_sync_action_classes(): void {
        if (!empty($this->sync_action_classes)) {
            return;
        }

        /** @var contentmarketplace[] $plugins */
        $plugins = core_plugin_manager::instance()->get_plugins_of_type('contentmarketplace');
        foreach ($plugins as $plugin) {
            if ($plugin->is_enabled()) {
                $this->sync_action_classes = core_component::get_namespace_classes(
                    'sync_action',
                    sync_action::class,
                    $plugin->component
                );
            }
        }
    }

}