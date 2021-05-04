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
namespace contentmarketplace_linkedin\action;

use coding_exception;
use contentmarketplace_linkedin\api\v2\api;
use contentmarketplace_linkedin\api\v2\service\learning_asset\constant;
use contentmarketplace_linkedin\api\v2\service\learning_asset\query\criteria;
use contentmarketplace_linkedin\api\v2\service\learning_asset\service;
use contentmarketplace_linkedin\config;
use contentmarketplace_linkedin\model\learning_object;
use totara_contentmarketplace\sync_action;
use totara_contentmarketplace\external_sync;
use totara_core\http\clients\curl_client;
use contentmarketplace_linkedin\api\response\result;
use totara_core\http\client;
use contentmarketplace_linkedin\entity\learning_object as learning_object_entity;

class lil_sync_action extends sync_action implements external_sync {
    /**
     * @var client
     */
    private $client = null;

    /**
     * @inheritDoc
     */
    public function invoke(): void {
        if (learning_object_entity::repository()->count() > 0) {
            throw new coding_exception('Data already exists, can\'t complete initial sync');
        }

        $criteria = new criteria();
        $criteria->set_start(0);
        $criteria->set_asset_types([
            constant::ASSET_TYPE_COURSE,
            constant::ASSET_TYPE_VIDEO,
            constant::ASSET_TYPE_LEARNING_PATH
        ]);
        $service = new service($criteria);

        /** @var result $result */
        $results = (api::create($this->get_api_client()))->execute($service);
        learning_object::create_bulk_from_result($results);

        config::set_last_time_run(time());
    }

    /**
     * @param client $client
     */
    public function set_api_client(client $client): void {
        $this->client = $client;
    }

    /**
     * @return client
     */
    public function get_api_client(): client {
        return $this->client ?? new curl_client();
    }

    /**
     * @inheritDoc
     */
    public function initial_run(): bool {
        return config::get_last_time_run();
    }
}