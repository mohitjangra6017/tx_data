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
 * @author  Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\sync_action;

use coding_exception;
use contentmarketplace_linkedin\api\v2\api;
use contentmarketplace_linkedin\api\v2\service\learning_asset\query\criteria;
use contentmarketplace_linkedin\api\v2\service\learning_asset\response\collection;
use contentmarketplace_linkedin\api\v2\service\learning_asset\service;
use contentmarketplace_linkedin\config;
use contentmarketplace_linkedin\constants;
use contentmarketplace_linkedin\model\learning_object;
use totara_contentmarketplace\sync\external_sync;
use totara_contentmarketplace\sync\sync_action;
use totara_core\http\client;

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
     * A flag to say that this action is instantiated to only sync those records
     * that had been modified since the specific time, from linkedin learning.
     *
     * Set this to FALSE to perform full syncing. Otherwise true to optimize the sync
     * and only pull the newly updated records.
     *
     * By default, it is TRUE.
     *
     * @var bool
     */
    private $sync_with_last_time_modified;

    /**
     * The collection of asset types.
     * We are querying by each of the asset type, because from linkedin they have a limited
     * response when there are two or more asset types included in the query.
     * See https://docs.microsoft.com/en-us/linkedin/learning/reference/learningassets#limited-response-scenarios
     *
     * By default we are going to query the COURSE, VIDEO and LEARNING_PATH.
     *
     * @var array
     */
    private $asset_types;

    /**
     * sync_learning_asset constructor.
     * @param bool     $initial_run
     * @param int|null $time_run
     */
    public function __construct(bool $initial_run = false, ?int $time_run = null) {
        parent::__construct($initial_run);
        $this->client = null;
        $this->time_run = $time_run ?? time();
        $this->sync_with_last_time_modified = true;

        $this->asset_types = [
            constants::ASSET_TYPE_COURSE,
            constants::ASSET_TYPE_VIDEO,
            constants::ASSET_TYPE_LEARNING_PATH
        ];
    }

    /**
     * @param bool $value
     * @return void
     */
    public function set_sync_with_last_time_modified(bool $value): void {
        $this->sync_with_last_time_modified = $value;
    }

    /**
     * @param string[] $asset_types
     * @return void
     */
    public function set_asset_types(string ...$asset_types): void {
        if (empty($asset_types)) {
            throw new coding_exception("Cannot set the asset types as empty");
        }

        $this->asset_types = [];

        foreach ($asset_types as $asset_type) {
            constants::validate_asset_type($asset_type);

            $this->asset_types[] = $asset_type;
        }
    }

    /**
     * @inheritDoc
     */
    public function invoke(): void {
        if (null === $this->client) {
            throw new coding_exception("Cannot run sync when client is not set");

        }

        foreach ($this->asset_types as $asset_type) {
            $criteria = new criteria();
            $criteria->set_asset_types([$asset_type]);

            if ($this->initial_run) {
                if (config::completed_initial_sync_learning_asset()) {
                    return;
                }
            } else {
                if (!config::completed_initial_sync_learning_asset()) {
                    // The initial run is not yet executed, hence we skip the update.
                    return;
                }

                // Regularly sync.
                if ($this->sync_with_last_time_modified) {
                    $last_modified_at = config::last_time_sync_learning_asset();
                    $criteria->set_last_modified_after($last_modified_at);
                }
            }

            $this->trace->output("Sync for type: {$asset_type}");
            $this->do_sync($criteria);
        }

        if ($this->initial_run) {
            config::save_completed_initial_sync_learning_asset(true);
        }

        config::save_last_time_sync_learning_asset($this->time_run);
    }

    /**
     * @param criteria $criteria
     * @return void
     */
    protected function do_sync(criteria $criteria): void {
        // Prevents changes via memory references.
        $criteria = clone $criteria;
        $criteria->set_count(100);

        $api = api::create($this->client);
        $service = new service($criteria);

        while (true) {
            /** @var collection $collection */
            $collection = $api->execute($service);
            $pagination = $collection->get_paging();

            $this->trace->output(
                "Start syncing records with the cursor: Start {$pagination->get_start()} - end {$pagination->get_count()}"
            );

            if ($this->initial_run) {
                learning_object::create_bulk_from_result($collection);
            } else {
                learning_object::update_bulk_from_result($collection);
            }

            if (!$pagination->has_next()) {
                $this->trace->output(
                    "Finish syncing with the total of records: {$pagination->get_total()}"
                );
                break;
            }

            $next_href = $pagination->get_next_link();

            $criteria->clear();
            $criteria->set_parameters_from_paging_url($next_href);
        }

        $this->trace->finished();
    }

    /**
     * @param client $client
     * @return void
     */
    public function set_api_client(client $client): void {
        $this->client = $client;
    }
}