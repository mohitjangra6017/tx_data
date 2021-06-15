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
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\data_provider;

use contentmarketplace_linkedin\entity\classification as classification_entity;
use contentmarketplace_linkedin\entity\classification_relationship;
use contentmarketplace_linkedin\model\classification as classification_model;
use contentmarketplace_linkedin\repository\classification_repository;
use core\collection;
use core\orm\entity\repository;
use core\orm\query\builder;
use core\orm\query\order;
use totara_contentmarketplace\data_provider\provider;

class classifications extends provider {
    /**
     * @return repository
     */
    protected function build_query(): repository {
        $builder = builder::table(classification_entity::TABLE, 'c');
        $builder->left_join(
            [classification_relationship::TABLE, 'cr'],
            'id',
            'child_classification_id'
        );

        $builder->select(['c.*', 'cr.parent_classification_id']);
        $builder->results_as_arrays(true);

        // Return the repository with a custom builder.
        return new classification_repository(classification_entity::class, $builder);
    }

    /**
     * @param classification_repository $repository
     * @param string $locale_language
     *
     * @return void
     */
    protected function filter_query_by_locale_language(
        classification_repository $repository,
        string $locale_language
    ): void {
        $repository->where('c.locale_language', $locale_language);
    }

    /**
     * @param classification_repository $repository
     * @param array $types
     *
     * @return void
     */
    protected function filter_query_by_classification_types(classification_repository $repository, array $types): void {
        $repository->where_in('c.type', $types);
    }

    /**
     * @param classification_repository $repository
     * @return void
     */
    protected function sort_query_by_name(classification_repository $repository): void {
        $repository->order_by('name', order::DIRECTION_ASC);
    }

    /**
     * @return collection
     */
    protected function process_fetched_items(): collection {
        return $this->items->map_to(
            function (array $record_map): classification_model {
                // Pop the parent classification id field, to make sure that our entity can
                // be instantiated safely.
                $parent_classification_id = $record_map['parent_classification_id'];
                unset($record_map['parent_classification_id']);

                $model = new classification_model(
                    new classification_entity($record_map),
                    $parent_classification_id
                );

                if (null === $parent_classification_id) {
                    // Only flag the value to say fetched when parent id is empty.
                    // This is to save us a few trials to load the parent id that had already been loaded,
                    // but not existing.
                    $model->set_fetched_parent_classification_id(true);
                }

                return $model;
            }
        );
    }
}