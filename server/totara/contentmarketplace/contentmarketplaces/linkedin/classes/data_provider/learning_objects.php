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
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

namespace contentmarketplace_linkedin\data_provider;

use contentmarketplace_linkedin\entity\learning_object as learning_object_entity;
use contentmarketplace_linkedin\model\learning_object as learning_object_model;
use core\collection;
use core\orm\entity\repository;

/**
 * Class learning_objects.
 *
 * @package contentmarketplace_linkedin\data_provider
 *
 * @method collection|learning_object_model[] get
 */
class learning_objects extends paginated_provider {

    public const SORT_BY_ALPHABETICAL = 'ALPHABETICAL';
    public const SORT_BY_LATEST = 'LATEST';

    /**
     * @inheritDoc
     */
    protected function get_default_sort_by(): ?string {
        return self::SORT_BY_LATEST;
    }

    /**
     * @inheritDoc
     */
    protected function build_query(): repository {
        return learning_object_entity::repository();
    }

    /**
     * @return collection|learning_object_model[]
     */
    protected function process_fetched_items(): collection {
        return $this->items
            ->map_to(learning_object_model::class);
    }

    /**
     * @param repository $repository
     * @param bool $is_retired
     */
    protected function filter_query_by_is_retired(repository $repository, bool $is_retired): void {
        $repository->where('retired_at', $is_retired ? '!=' : '=', null);
    }

    /**
     * @param repository $repository
     * @param string $language
     */
    protected function filter_query_by_language(repository $repository, string $language): void {
        $repository->where('locale_language', $language);
    }

    /**
     * @param repository $repository
     */
    protected function sort_query_by_alphabetical(repository $repository): void {
        $repository->order_by('title');
    }

    /**
     * @param repository $repository
     */
    protected function sort_query_by_latest(repository $repository): void {
        $repository->order_by('last_updated_at', 'DESC');
    }

}
