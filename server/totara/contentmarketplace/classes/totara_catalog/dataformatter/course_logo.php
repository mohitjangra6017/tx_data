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

use context;
use stdClass;
use totara_catalog\dataformatter\formatter;
use totara_contentmarketplace\plugininfo\contentmarketplace;

/**
 * Formatter for content marketplace logo.
 */
class course_logo extends formatter {
    /**
     * course_logo constructor.
     * @param string $marketplace_component_field
     */
    public function __construct(string $marketplace_component_field) {
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
        if (empty($data['marketplace_component'])) {
            return null;
        }

        $marketplace = (contentmarketplace::plugin($data['marketplace_component']))->contentmarketplace();
        return (object) [
            'url' => $marketplace->get_logo_url()->out(false),
            'alt' => $marketplace->get_logo_alt_text(),
        ];
    }

}