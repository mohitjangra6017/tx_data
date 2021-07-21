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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_contentmarketplace
 */
namespace totara_contentmarketplace\totara_catalog\dataformatter;

use coding_exception;
use context;
use core_component;
use totara_catalog\dataformatter\formatter;
use stdClass;
use totara_contentmarketplace\plugininfo\contentmarketplace;

/**
 * Formatter for content marketplace logo.
 */
class course_logo extends formatter {
    /**
     * course_logo constructor.
     * @param string $learning_object_id_field
     * @param string $marketplace_component_field
     */
    public function __construct(string $learning_object_id_field, string $marketplace_component_field) {
        $this->add_required_field('learning_object_id', $learning_object_id_field);
        $this->add_required_field('marketplace_component', $marketplace_component_field);
    }

    /**
     * @return array
     */
    public function get_suitable_types(): array {
        return [
            formatter::TYPE_PLACEHOLDER_IMAGE
        ];
    }

    /**
     * @param array    $data
     * @param context $context
     *
     * @return stdClass|null
     */
    public function get_formatted_value(array $data, context $context) {
        $marketplace_component = $data['marketplace_component'];
        $learning_object_id = $data['learning_object_id'];

        if (is_null($learning_object_id) || is_null($marketplace_component) || count(array_keys($data)) != 2) {
            return null;
        }

        [$plugin_type, $plugin_name] = core_component::normalize_component($marketplace_component);

        // If it's not contentmarketplace'subplugin, we just return null.
        if ($plugin_type !== 'contentmarketplace') {
            return null;
        }

        $plugin_info = (contentmarketplace::plugin($plugin_name))->contentmarketplace();

        $image = new stdClass();
        $image->url = $plugin_info->get_mini_logo_url();
        $image->alt = $this->get_image_alt($marketplace_component);

        return $image;
    }

    /**
     * @param string $sub_plugin
     * @return string
     */
    private function get_image_alt(string $sub_plugin): string {
        if (get_string_manager()->string_exists('logo_alt', $sub_plugin)) {
            // Using the string from the actual content marketplace plugin.
            return get_string('logo_alt', $sub_plugin);
        }

        // Fallback to the string provided by totara_contentmarketplace.
        return  get_string('logo_alt', 'totara_contentmarketplace');
    }
}