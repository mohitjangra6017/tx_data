<?php
/*
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
 * @author David Curry <david.curry@totaralearning.com>
 * @package mobile_findlearning
 */

namespace mobile_findlearning;

use totara_catalog\output\catalog as core_catalog;
use mobile_findlearning\provider_handler;
use mobile_findlearning\catalog_retrieval;
use mobile_findlearning\item_mobile as mobile_item;
use mobile_findlearning\config;

defined('MOODLE_INTERNAL') || die();

/**
 * Note: The original idea of overriding the catalog output class has ended up with
 *       custom static functions, however I'm leaving it like this so we can track it
 *       back to the original and incase we ever want to revisit overriding parts of the output
 */
class catalog extends core_catalog {

    /**
     * A function to get the all the mobile catalog items.
     * Unfiltered except for the item_type, limit to course, playlist, resource.
     *
     * @param int $limitfrom - The number to start checking records from, loosely a pagenum.
     * @return object - The page object containing the items we want.
     */
    public static function load_catalog_page_objects($limitfrom = 0) {
        $itemsper = 10; // Hardcoded for now but could easily be an admin setting
        $maxcount = -1;
        $orderbykey = 'alpha'; // also accepts 'featured'... guessing that's a setting

        $catalog = new catalog_retrieval();

        $page = $catalog->get_page_of_objects($itemsper, $limitfrom, $maxcount, $orderbykey);
        $objects = $page->objects;

        $providerhandler = provider_handler::instance();

        $requireddataholders = [];
        foreach ($objects as $object) {
            if (empty($requireddataholders[$object->objecttype])) {
                $provider = $providerhandler->get_provider($object->objecttype);
                $requireddataholders[$object->objecttype] = mobile_item::get_required_dataholders($provider);
            }
        }

        // load all the required data.
        $page->objects = $providerhandler->get_data_for_objects($objects, $requireddataholders);
        return $page;
    }

    /**
     * Filter items
     *
     * @param string $search - an optional search term for the fts filter
     *
     */
    public static function load_filtered_page_objects($search = '') {
        $itemsper = 10;
        $limitfrom = 0;
        $maxcount = -1;
        $orderbykey = 'alpha'; // also accepts 'featured'... guessing that's a setting

        $catalog = new catalog_retrieval();

        $page = $catalog->get_page_of_objects($itemsper, $limitfrom, $maxcount, $orderbykey);
        $objects = $page->objects;

        // Experimental
        /* TL-31555 will look into overriding filters
         * $filterhandler = filter_handler::instance();
         * foreach ($filterhandler->get_active_filters() as $filter) {
         *     $optionalparams = $filter->selector->get_optional_params();
         *
         *     $paramdata = [];
         *     foreach ($optionalparams as $optionalparam) {
         *         if (isset($filterparams[$optionalparam->key])) {
         *             $paramdata[$optionalparam->key] = $filterparams[$optionalparam->key];
         *         }
         *     }
         *
         *     $filter->selector->set_current_data($paramdata);
         *     $standarddata = $filter->selector->get_data();
         *     $filter->datafilter->set_current_data($standarddata);
         * }
         *
         *
         * $strs = array_map(function($filter) {
         *     $res = new \stdClass();
         *     switch ($filter->template_name) {
         *         case "totara_core/select_multi":
         *             $res->label = $filter->template_data['title'];
         *
         *             $selected = array_reduce($filter->template_data['options'], function($value, $option) {
         *                 if ($option->active && $value === null) {
         *                     return $option->name;
         *                 } else if ($option->active) {
         *                     return $value . ', ' . $option->name;
         *                 } else {
         *                     return $value;
         *                 }
         *             }, null);
         *
         *             if ($selected === null) {
         *                 $res = null;
         *             } else {
         *                 $res->content = $selected;
         *             }
         *
         *             break;
         *         case "totara_core/select_tree":
         *             $res->label = $filter->template_data['title'];
         *             if (is_string($filter->template_data['active_name'])) {
         *                 $res->content = $filter->template_data['active_name'];
         *             } else if (get_class($filter->template_data['active_name']) == 'lang_string') {
         *                 $res->content = $filter->template_data['active_name']->out();
         *             }
         *             break;
         *         case "totara_core/select_search_text":
         *             if ($filter->template_data['current_val']) {
         *                 $res->label = $filter->template_data['title'];
         *                 $res->content = $filter->template_data['current_val'];
         *             } else {
         *                 $res = null;
         *             }
         *             break;
         *         default:
         *             $res = "unknown template name:". $filter['template_name'];
         *             break;
         *     };
         *     return $res;
         * }, $selectors);
         *
         * $data->sr_content = array_values(array_filter($strs));
         */

        $providerhandler = provider_handler::instance();

        $requireddataholders = [];
        foreach ($objects as $object) {
            if (empty($requireddataholders[$object->objecttype])) {
                $provider = $providerhandler->get_provider($object->objecttype);
                $requireddataholders[$object->objecttype] = mobile_item::get_required_dataholders($provider);
            }
        }

        // load all the required data.
        $objects = $providerhandler->get_data_for_objects($objects, $requireddataholders);
        return $objects;
    }

    /**
     * Get a list of sorting options.
     *
     * @return \stdClass[]
     */
    private static function get_order_by_options() {
        $options = [];

        // If there is an active full text search then relevance becomes the first order by option.
        if (filter_handler::instance()->get_full_text_search_filter()->datafilter->is_active()) {
            $score = new \stdClass();
            $score->key = 'score';
            $score->name = get_string('sort_score', 'totara_catalog');
            $options['score'] = $score;
        }

        // Ordering by featured learning is only possible if some featured learning has been specified.
        if (config::instance()->get_value('featured_learning_enabled')) {
            $featured = new \stdClass();
            $featured->key = 'featured';
            $featured->name = get_string('sort_featured', 'totara_catalog');
            $options['featured'] = $featured;
        }

        $alpha = new \stdClass();
        $alpha->key = 'text';
        $alpha->name = get_string('sort_text', 'totara_catalog');
        $options['text'] = $alpha;

        $latest = new \stdClass();
        $latest->key = 'time';
        $latest->name = get_string('sort_time', 'totara_catalog');
        $options['time'] = $latest;

        reset($options);
        $firstkey = key($options);
        $options[$firstkey]->default = true;

        return $options;
    }
}
