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

use totara_tui\tree\branch;
use totara_tui\tree\leaf;

class learning_objects_selected_filters extends learning_objects_filter_options {

    /**
     * @var int[][] Associative array of filter type => IDs array
     */
    private $selected_filters;

    /**
     * learning_objects_selected_filter_labels constructor.
     * @param int[][] $selected_filters Associative array of filter type => IDs array
     */
    public function __construct(array $selected_filters) {
        $this->selected_filters = $selected_filters;
    }

    /**
     * Get a flat list of subject labels.
     *
     * @return string[]
     */
    private function get_subject_labels(): array {
        // TODO: Have this do a DB query on the classifications table to get the subjects.
        // For now, we just get it from the placeholder tree.
        return self::get_tree_labels_from_ids($this->get_subjects(), $this->selected_filters['subjects']);
    }

    /**
     * Get a flat list of the filter labels from the selected filter IDs.
     *
     * @return string[]
     */
    public function get(): array {
        return array_merge(
            $this->get_subject_labels(),
            self::get_tree_labels_from_ids($this->get_asset_types(), $this->selected_filters['asset_type']),
            self::get_tree_labels_from_ids($this->get_time_to_complete(), $this->selected_filters['time_to_complete']),
        );
    }

    /**
     * Get the labels from a tree from the given IDs.
     *
     * @param branch $tree
     * @param int[] $ids
     * @return string[]
     */
    private static function get_tree_labels_from_ids(branch $tree, array $ids): array {
        return array_map(static function (leaf $node) {
            return $node->get_label();
        }, array_values($tree->get_nodes_from_ids($ids)));
    }

}
