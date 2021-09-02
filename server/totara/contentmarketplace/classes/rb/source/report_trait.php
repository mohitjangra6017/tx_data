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
 * @package totara_contentmarketplace
 */

namespace totara_contentmarketplace\rb\source;

use core\orm\query\builder;
use rb_column_option;
use rb_filter_option;
use rb_join;
use totara_contentmarketplace\plugininfo\contentmarketplace;

/**
 * Content marketplace report column and filter options.
 * @package totara_contentmarketplace\rb\source
 */
trait report_trait {

    /**
     * @var string[]
     */
    private static $enabled_marketplace_plugins;

    /**
     * @param array $joinlist
     * @param string $join
     * @param string $field
     */
    protected function add_totara_contentmarketplace_tables(array &$joinlist, string $join, string $field): void {
        if (empty($this->get_enabled_marketplace_plugins())) {
            return;
        }

        $this->usedcomponents[] = 'totara_contentmarketplace';

        // Note: When we add support for having multiple activities in a course that are from multiple different marketplaces,
        // we will need to ensure it stays a one-to-one join by group the list of marketplaces and concatenating them.
        $joinlist[] = new rb_join(
            'course_source',
            'left',
            '{totara_contentmarketplace_course_source}',
            "$join.$field = course_source.course_id",
            REPORT_BUILDER_RELATION_ONE_TO_ONE,
            $join
        );
    }

    /**
     * @param array $columnoptions
     * @param string $join
     */
    protected function add_totara_contentmarketplace_columns(array &$columnoptions, string $join): void {
        if (empty($this->get_enabled_marketplace_plugins())) {
            return;
        }

        $columnoptions[] = new rb_column_option(
            'course',
            'provider',
            get_string('course_provider', 'totara_contentmarketplace'),
            "CASE WHEN course_source.marketplace_component IS NULL
                    THEN '0'
                    ELSE course_source.marketplace_component
                END",
            [
                'joins' => [$join, 'course_source'],
                'displayfunc' => 'course_marketplace_provider',
            ]
        );
    }

    /**
     * @param array $filteroptions
     */
    protected function add_totara_contentmarketplace_filters(array &$filteroptions): void {
        if (empty($this->get_enabled_marketplace_plugins())) {
            return;
        }

        $filteroptions[] = new rb_filter_option(
            'course',
            'provider',
            get_string('course_provider', 'totara_contentmarketplace'),
            'multicheck',
            [
                'selectfunc' => 'totara_contentmarketplace_providers',
                'simplemode' => true,
            ]
        );
    }

    /**
     * Get the filter options of marketplace providers that are enabled.
     *
     * @return array<string, string>
     */
    public function rb_filter_totara_contentmarketplace_providers(): array {
        $providers = [
            0 => get_string('provider_internal', 'totara_contentmarketplace')
        ];

        foreach ($this->get_enabled_marketplace_plugins() as $plugin) {
            $providers["contentmarketplace_$plugin"] = get_string('pluginname', "contentmarketplace_$plugin");
        }

        return $providers;
    }

    /**
     * Get and cache the enabled content marketplace plugins.
     *
     * @return string[] Array of the marketplaces, e.g. ['linkedin', 'goone']
     */
    private function get_enabled_marketplace_plugins(): array {
        if (!isset(self::$enabled_marketplace_plugins)) {
            self::$enabled_marketplace_plugins = contentmarketplace::get_enabled_plugins();
        }
        return self::$enabled_marketplace_plugins;
    }

}
