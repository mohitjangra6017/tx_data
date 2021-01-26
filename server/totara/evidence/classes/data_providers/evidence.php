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
 * @author Marco Song <marco.song@totaralearning.com>
 * @package totara_evidence
 */

namespace totara_evidence\data_providers;

use core\orm\entity\repository;
use totara_evidence\entity\evidence_item as evidence_entity;

class evidence extends provider {

    /**
     * Filter by evidence item name
     *
     * @param $repository
     * @param $value
     */
    protected function filter_by_search($repository, $value): void {
        $repository->where('name', 'ilike', $value);
    }

    /**
     * Filter by a set of evidence IDs
     *
     * @param $repository
     * @param array $evidence_ids
     */
    protected function filter_by_ids($repository, array $evidence_ids): void {
        $repository->where_in('id', $evidence_ids);
    }

    /**
     * Filter by evidence type id
     *
     * @param $repository
     * @param int $type_id
     */
    protected function filter_by_type_id($repository, int $type_id): void {
        if ($type_id <= 0) {
            return;
        }
        $repository->where('typeid', $type_id);
    }

    /**
     * Filter by user id
     *
     * @param $repository
     * @param int $user_id
     */
    protected function filter_by_user_id($repository, int $user_id): void {
        $repository->where('user_id', $user_id);
    }

    /**
     * @inheritDoc
     */
    protected function build_query(): repository {
        return evidence_entity::repository()
            ->with('type')
            ->order_by('name')
            ->order_by('id');
    }
}
