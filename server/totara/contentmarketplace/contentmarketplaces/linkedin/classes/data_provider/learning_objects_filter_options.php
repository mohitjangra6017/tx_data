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

use contentmarketplace_linkedin\constants;
use contentmarketplace_linkedin\dto\timespan;
use contentmarketplace_linkedin\tree\timespan_leaf;
use totara_tui\tree\branch;
use totara_tui\tree\leaf;

class learning_objects_filter_options {

    /**
     * @var string
     */
    protected $language;

    /**
     * learning_objects_filter_options constructor.
     * @param string $language
     */
    public function __construct(string $language) {
        $this->language = $language;
    }

    /**
     * Get a tree structure of subject filter options.
     *
     * @return branch
     */
    protected function get_subjects(): branch {
        $root_branch = new branch(
            'subjects',
            get_string('catalog_filter_subjects', 'contentmarketplace_linkedin', $this->language)
        );

        // We just want the top level of classifications (library), so we can then build the tree based upon it.
        $libraries = (new classifications())
            ->with_children()
            ->add_filters([
                'locale_language' => $this->language,
                'classification_types' => [constants::CLASSIFICATION_TYPE_LIBRARY],
            ])
            ->sort_by(classifications::SORT_BY_ALPHABETICAL)
            ->get();

        foreach ($libraries as $library) {
            $library_branch = new branch($library->id, $library->name);
            $root_branch->add_branches($library_branch);

            foreach ($library->children as $subject) {
                $library_branch->add_leaves(new leaf($subject->id, $subject->name));
            }
        }

        return $root_branch;
    }

    /**
     * Get a tree structure of asset type options.
     *
     * @return branch
     */
    protected function get_asset_types(): branch {
        return (new branch(
            'asset_types',
            get_string('catalog_filter_asset_type', 'contentmarketplace_linkedin', $this->language),
        ))->add_leaves(
            new leaf(
                constants::ASSET_TYPE_COURSE,
                get_string('asset_type_course_plural', 'contentmarketplace_linkedin', $this->language),
            ),
            new leaf(
                constants::ASSET_TYPE_VIDEO,
                get_string('asset_type_video_plural', 'contentmarketplace_linkedin', $this->language),
            )
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
            get_string('catalog_filter_time_to_complete', 'contentmarketplace_linkedin', $this->language),
        ))->add_leaves(
            new timespan_leaf(
                null,
                timespan::minutes(10),
                get_string('catalog_filter_timespan_under_10_minutes', 'contentmarketplace_linkedin', $this->language)
            ),
            new timespan_leaf(
                timespan::minutes(10),
                timespan::minutes(30),
                get_string('catalog_filter_timespan_10_to_30_minutes', 'contentmarketplace_linkedin', $this->language)
            ),
            new timespan_leaf(
                timespan::minutes(30),
                timespan::minutes(60),
                get_string('catalog_filter_timespan_30_to_60_minutes', 'contentmarketplace_linkedin', $this->language)
            ),
            new timespan_leaf(
                timespan::hours(1),
                timespan::hours(2),
                get_string('catalog_filter_timespan_1_to_2_hours', 'contentmarketplace_linkedin', $this->language)
            ),
            new timespan_leaf(
                timespan::hours(2),
                timespan::hours(3),
                get_string('catalog_filter_timespan_2_to_3_hours', 'contentmarketplace_linkedin', $this->language)
            ),
            new timespan_leaf(
                timespan::hours(3),
                null,
                get_string('catalog_filter_timespan_over_3_hours', 'contentmarketplace_linkedin', $this->language)
            ),
        );
    }

    /**
     * Get the filter option available for filtering learning objects.
     *
     * @return branch[][]
     */
    public function get(): array {
        return [
            'subjects' => [$this->get_subjects()],
            'asset_type' => [$this->get_asset_types()],
            'time_to_complete' => [$this->get_time_to_complete()],
        ];
    }

}
