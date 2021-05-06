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
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\sync_action;

use coding_exception;
use contentmarketplace_linkedin\api\v2\api;
use contentmarketplace_linkedin\api\v2\service\learning_asset\constant;
use contentmarketplace_linkedin\api\v2\service\learning_asset\query\criteria;
use contentmarketplace_linkedin\api\v2\service\learning_asset\service;
use contentmarketplace_linkedin\model\learning_object;
use totara_contentmarketplace\sync\sync_action;
use totara_contentmarketplace\sync\external_sync;
use totara_core\http\client;
use contentmarketplace_linkedin\config;

/**
 * Class learning_asset
 * @package contentmarketplace_linkedin\action
 */
class sync_learning_asset extends sync_action implements external_sync {
    /**
     * @var client
     */
    private $client;

    /**
     * @var int
     */
    private $time_run;

    /**
     * sync_learning_asset constructor.
     * @param bool $initial_run
     */
    public function __construct(bool $initial_run = false, ?int $time_run = null) {
        parent::__construct($initial_run);
        $this->client = null;
        $this->time_run = $time_run ?? time();
    }

    /**
     * @inheritDoc
     */
    public function invoke(): void {
        if (null === $this->client) {
            throw new coding_exception("Cannot run sync when client is not set");

        }

        if ($this->initial_run) {
            if (config::completed_initial_sync_learning_asset()) {
                return;
            }

            $this->do_initial_sync();
        }

        // This is where regular update will run.
    }

    /**
     * @return criteria
     */
    private function get_criteria(): criteria {
        $criteria = new criteria();
        $criteria->set_start(0);
        $criteria->set_asset_types([
            constant::ASSET_TYPE_COURSE,
            constant::ASSET_TYPE_VIDEO,
            constant::ASSET_TYPE_LEARNING_PATH
        ]);

        return $criteria;
    }

    /**
     * @return void
     */
    private function do_initial_sync(): void {
        $criteria = $this->get_criteria();
        $service = new service($criteria);

        $api = api::create($this->client);
        $collection = $api->execute($service);

        learning_object::create_bulk_from_result($collection);

        config::save_completed_initial_sync_learning_asset(true);
        config::save_last_time_sync_learning_asset($this->time_run);
    }

    /**
     * @param client $client
     * @return void
     */
    public function set_api_client(client $client): void {
        $this->client = $client;
    }
}