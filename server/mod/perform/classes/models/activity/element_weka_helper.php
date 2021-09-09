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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Jaron Steenson <jaron.steenson@totaralearning.com>
 * @package mod_perform
 */

namespace mod_perform\models\activity;

use coding_exception;
use totara_tui\json_editor\formatter\formatter as json_editor_formatter;
use mod_perform\entity\activity\element as element_entity;

/**
 * Trait weka_element_description_trait
 *
 * Helper methods for element plugins that use weka editors/content.
 *
 * @package mod_perform\models\activity
 */
class element_weka_helper {

    /**
     * Converts to html and adds the description weka doc to the element data.
     *
     * @param element_entity $element The element to add weka html to
     * @param string $in_doc_field The field the weka doc is in
     * @param string $out_html_field The field to put the output html in
     * @return string The encoded element data
     */
    public static function add_weka_html_to_data(element_entity $element, string $in_doc_field, string $out_html_field): ?string {
        if ($element->data === null) {
            return null;
        }

        $element_data = json_decode($element->data, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($element_data) || !($element_data[$in_doc_field] ?? null)) {
            return json_encode($element_data, JSON_THROW_ON_ERROR);
        }

        $weka_doc = $element_data[$in_doc_field];

        if ($weka_doc) {
            $formatter = new json_editor_formatter();
            $element_data[$out_html_field] = $formatter->to_html($weka_doc);
        } else {
            $element_data[$out_html_field] = null;
        }

        return json_encode($element_data, JSON_THROW_ON_ERROR);
    }

}
