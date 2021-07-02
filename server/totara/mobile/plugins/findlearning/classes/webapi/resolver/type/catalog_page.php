<?php
/**
 *
 * This file is part of Totara LMS
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

namespace mobile_findlearning\webapi\resolver\type;

use \core\webapi\type_resolver;
use \core\webapi\execution_context;
use \mobile_findlearning\formatter\catalog_page_formatter as page_formatter;
use \mobile_findlearning\item_mobile as mobile_item;

class catalog_page implements type_resolver {

    /**
     * Resolve program fields
     *
     * @param string $field
     * @param stdClass $page
     * @param array $args
     * @param execution_context $ec
     * @return mixed
     */
    public static function resolve(string $field, $page, array $args, execution_context $ec) {
        $format = $args['format'] ?? null;
        $context = \context_system::instance();

        // Any field customisations we need happen here in this switch.
        $data = new \stdClass();
        switch ($field) {
            case 'max_count':
                $data->max_count = $page->maxcount;
                break;
            case 'limit_from':
                $data->limit_from = $page->limitfrom;
                break;
            case 'final_records':
                $data->final_records = $page->endofrecords;
                break;
            case 'items':
                return $page->objects; // Skip the formatter and go straight to item resolver.
        }

        $formatter = new page_formatter($data, $context);
        return $formatter->format($field, $format);
    }
}
