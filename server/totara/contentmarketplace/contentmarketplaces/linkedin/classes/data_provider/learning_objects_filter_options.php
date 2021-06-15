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

use coding_exception;
use contentmarketplace_linkedin\constants;
use contentmarketplace_linkedin\dto\timespan;
use contentmarketplace_linkedin\model\classification;
use contentmarketplace_linkedin\tree\timespan_leaf;
use totara_tui\tree\branch;
use totara_tui\tree\leaf;

class learning_objects_filter_options {

    /**
     * Get a tree structure of subject filter options.
     *
     * @param string $language
     * @return branch
     */
    private function get_subjects(string $language): branch {
        $provider = new classifications();
        $provider->add_filters([
            'locale_language' => $language,
            'classification_types' => [
                constants::CLASSIFICATION_TYPE_LIBRARY,
                constants::CLASSIFICATION_TYPE_SUBJECT
            ]
        ]);

        $items = $provider->get();

        // Populate the two separate lists that can help to build a snowman.
        $collection_map = [];

        /** @var classification $item */
        foreach ($items as $item) {
            if (!$item->has_parent_classification()) {
                if (!isset($collection_map[$item->id])) {
                    $collection_map[$item->id] = [
                        'parent' => $item,
                        'children' => []
                    ];

                    continue;
                }

                if (isset($collection_map[$item->id]['parent'])) {
                    throw new coding_exception("The parent had already been set, cannot be reset");
                }

                $collection_map[$item->id]['parent'] = $item;
                continue;
            }

            // A child item.
            $parent_id = $item->parent_classification_id;
            if (!isset($collection_map[$parent_id])) {
                $collection_map[$parent_id] = [
                    'parent' => null,
                    'children' => []
                ];
            }

            $collection_map[$parent_id]['children'][] = $item;
        }

        $subject_branch = new branch(
            'subjects',
            get_string('catalog_filter_subjects', 'contentmarketplace_linkedin', $language)
        );

        $subject_branch->add_branches(
            ...array_map(
                function (array $map): branch {
                    /** @var classification $parent_classification */
                    $parent_classification = $map['parent'];
                    $parent_branch = new branch(
                        $parent_classification->id,
                        $parent_classification->name
                    );

                    $parent_branch->add_leaves(
                        ...array_map(
                            function (classification $child_classification): leaf {
                                return new leaf(
                                    $child_classification->id,
                                    $child_classification->name
                                );
                            },
                            $map['children']
                        )
                    );
                    return $parent_branch;
                },
                $collection_map
            )
        );

        return $subject_branch;
    }

    /**
     * Get a tree structure of asset type options.
     *
     * @return branch
     */
    protected function get_asset_types(): branch {
        return (new branch(
            'asset_types',
            get_string('catalog_filter_asset_type', 'contentmarketplace_linkedin'),
        ))->add_leaves(
            new leaf(
                constants::ASSET_TYPE_COURSE,
                get_string('asset_type_course_plural', 'contentmarketplace_linkedin'),
            ),
            new leaf(
                constants::ASSET_TYPE_VIDEO,
                get_string('asset_type_video_plural', 'contentmarketplace_linkedin'),
            ),
            new leaf(
                constants::ASSET_TYPE_LEARNING_PATH,
                get_string('asset_type_learning_path_plural', 'contentmarketplace_linkedin'),
            ),
        );
    }

    /**
     * Get a tree structure of time to complete filter options.
     *
     * @return branch
     */
    protected function get_time_to_complete(): branch {
        return (new branch(
            'time_to_complete',
            get_string('catalog_filter_time_to_complete', 'contentmarketplace_linkedin'),
        ))->add_leaves(
            new timespan_leaf(
                null,
                timespan::minutes(10),
                get_string('catalog_filter_timespan_under_10_minutes', 'contentmarketplace_linkedin')
            ),
            new timespan_leaf(
                timespan::minutes(10),
                timespan::minutes(30),
                get_string('catalog_filter_timespan_10_to_30_minutes', 'contentmarketplace_linkedin')
            ),
            new timespan_leaf(
                timespan::minutes(30),
                timespan::minutes(60),
                get_string('catalog_filter_timespan_30_to_60_minutes', 'contentmarketplace_linkedin')
            ),
            new timespan_leaf(
                timespan::hours(1),
                timespan::hours(2),
                get_string('catalog_filter_timespan_1_to_2_hours', 'contentmarketplace_linkedin')
            ),
            new timespan_leaf(
                timespan::hours(2),
                timespan::hours(3),
                get_string('catalog_filter_timespan_2_to_3_hours', 'contentmarketplace_linkedin')
            ),
            new timespan_leaf(
                timespan::hours(3),
                null,
                get_string('catalog_filter_timespan_over_3_hours', 'contentmarketplace_linkedin')
            ),
        );
    }

    /**
     * Get the filter option available for filtering learning objects.
     *
     * @param string $language
     * @return branch[][]
     */
    public function get(string $language): array {
        return [
            'subjects' => [$this->get_subjects($language)],
            'asset_type' => [$this->get_asset_types()],
            'time_to_complete' => [$this->get_time_to_complete()],
        ];
    }

}
