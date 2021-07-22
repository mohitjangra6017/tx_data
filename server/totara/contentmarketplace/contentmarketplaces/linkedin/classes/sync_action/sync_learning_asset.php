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
use contentmarketplace_linkedin\api\v2\service\learning_asset\response\element;
use contentmarketplace_linkedin\api\v2\service\learning_asset\service;
use contentmarketplace_linkedin\config;
use contentmarketplace_linkedin\constants;
use contentmarketplace_linkedin\entity\classification;
use contentmarketplace_linkedin\entity\classification_relationship;
use contentmarketplace_linkedin\entity\learning_object as learning_object_entity;
use contentmarketplace_linkedin\entity\learning_object_classification;
use contentmarketplace_linkedin\model\learning_object;
use core\orm\query\builder;
use progress_trace;
use totara_contentmarketplace\sync\external_sync;
use totara_contentmarketplace\sync\sync_action;
use totara_core\http\client;

/**
 * Class learning_asset
 * @package contentmarketplace_linkedin\action
 */
class sync_learning_asset extends sync_action implements external_sync {
    /**
     * The mappable level for linkedin learning classifications
     *
     * @var array
     */
    private const MAPPABLE_LEVEL = [
        constants::CLASSIFICATION_TYPE_TOPIC => [constants::CLASSIFICATION_TYPE_SUBJECT],
        constants::CLASSIFICATION_TYPE_SUBJECT => [constants::CLASSIFICATION_TYPE_LIBRARY],
        constants::CLASSIFICATION_TYPE_LIBRARY => [],
    ];

    /**
     * @var client
     */
    private $client;

    /**
     * The time after which the assets were changed, it only accepts number of milliseconds
     *
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
     * @param bool $is_initial_run
     * @param int|null $time_run
     * @param progress_trace|null $trace
     */
    public function __construct(
        bool $is_initial_run = false,
        ?int $time_run = null,
        ?progress_trace $trace = null
    ) {
        parent::__construct($is_initial_run, $trace);
        $this->client = null;
        $this->time_run = $time_run ?? round(microtime(true) * 1000);
        $this->sync_with_last_time_modified = true;

        $this->asset_types = [
            constants::ASSET_TYPE_COURSE,
            constants::ASSET_TYPE_VIDEO,
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
     * @return bool
     */
    public function is_skipped(): bool {
        if (!config::client_id() || !config::client_secret()) {
            return true;
        }

        if ($this->is_initial_run && config::completed_initial_sync_learning_asset()) {
            return true;
        }

        if (!$this->is_initial_run && !config::completed_initial_sync_learning_asset()) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function invoke(): void {
        if ($this->is_skipped()) {
            // The sync action should be skipped.
            return;
        }

        if (null === $this->client) {
            throw new coding_exception("Cannot run sync when client is not set");
        }

        $api = api::create($this->client);
        foreach ($this->asset_types as $asset_type) {
            $criteria = new criteria();
            $criteria->set_asset_types([$asset_type]);

            if (!$this->is_initial_run && $this->sync_with_last_time_modified) {
                $last_modified_at = config::last_time_sync_learning_asset();
                $criteria->set_last_modified_after($last_modified_at);
            }

            $this->trace->output("Sync for type: {$asset_type}");
            $criteria->set_count(100);

            $service = new service($criteria);


            while (true) {
                /** @var collection $collection */
                $collection = $api->execute($service);
                $elements = $collection->get_elements();

                if (empty($elements)) {
                    $this->trace->output("No learning assets found for type: {$asset_type}");
                    break;
                }

                foreach ($elements as $element) {
                    $this->do_sync_element($element);
                }

                $pagination = $collection->get_paging();
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
        }

        if ($this->is_initial_run) {
            config::save_completed_initial_sync_learning_asset(true);
        }

        config::save_last_time_sync_learning_asset($this->time_run);
    }

    /**
     * @param element $element
     * @return void
     */
    private function do_sync_element(element $element): void {
        $urn = $element->get_urn();

        if ($this->is_initial_run) {
            $learning_object = learning_object::create_from_element($element);
        } else {
            $repository = learning_object_entity::repository();
            $entity = $repository->find_by_urn($urn);

            if (null === $entity) {
                // The learning asset element is new to our system.
                $learning_object = learning_object::create_from_element($element);
            } else {
                $learning_object = new learning_object($entity);
                $learning_object->update_from_element($element);
            }
        }

        $entity = $learning_object->get_entity();
        $this->populate_classifications($entity, $element);
    }

    /**
     * @param learning_object_entity $entity
     * @param element $element
     */
    private function populate_classifications(learning_object_entity $entity, element $element): void {
        // Progress on classification path and its relationship.
        $db = builder::get_db();
        $classifications = $element->get_classifications();

        foreach ($classifications as $classification_with_path) {
            $classification_with_path_type = $classification_with_path->get_type();
            if (!isset(self::MAPPABLE_LEVEL[$classification_with_path_type])) {
                // Skips for those that cannot be map.
                continue;
            }

            $classification_with_path_urn = $classification_with_path->get_urn();
            $classification_id = $db->get_field(
                classification::TABLE,
                'id',
                ['urn' => $classification_with_path_urn],
                IGNORE_MISSING
            );

            if (empty($classification_id)) {
                // Cannot find the classification's id within our database.
                $this->trace->output(
                    "\tCannot find the classification with urn {$classification_with_path_urn}"
                );

                continue;
            }

            // Create the learning object classification relationship.
            $learning_object_relation_exist = $db->record_exists(
                learning_object_classification::TABLE,
                [
                    'classification_id' => $classification_id,
                    'learning_object_id' => $entity->id
                ]
            );

            if (!$learning_object_relation_exist) {
                $relation = new learning_object_classification();
                $relation->classification_id = $classification_id;
                $relation->learning_object_id = $entity->id;
                $relation->save();
            }

            $path = $classification_with_path->get_path();
            if (empty($path)) {
                // No path set.
                continue;
            }

            foreach ($path as $parent_classification) {
                $map_level = self::MAPPABLE_LEVEL[$classification_with_path_type] ?? [];
                if (!in_array($parent_classification->get_type(), $map_level)) {
                    continue;
                }

                $parent_classification_urn = $parent_classification->get_urn();
                $parent_classification_id = $db->get_field(
                    classification::TABLE,
                    'id',
                    ['urn' => $parent_classification->get_urn()],
                    IGNORE_MISSING
                );

                if (empty($parent_classification_id)) {
                    $this->trace->output(
                        "\tCannot find the parent classification with urn {$parent_classification_urn}"
                    );

                    continue;
                }

                $classification_relationship_existing = $db->record_exists(
                    classification_relationship::TABLE,
                    [
                        'child_id' => $classification_id,
                        'parent_id' => $parent_classification_id
                    ]
                );

                if (!$classification_relationship_existing) {
                    $classification_relationship = new classification_relationship();
                    $classification_relationship->child_id = $classification_id;
                    $classification_relationship->parent_id = $parent_classification_id;

                    $classification_relationship->save();
                }
            }
        }
    }

    /**
     * @param client $client
     * @return void
     */
    public function set_api_client(client $client): void {
        $this->client = $client;
    }
}