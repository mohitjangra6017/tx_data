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
use \mobile_findlearning\formatter\catalog_item_formatter as item_formatter;
use \mobile_findlearning\item_mobile as mobile_item;

class catalog_item implements type_resolver {

    /**
     * Resolve program fields
     *
     * @param string $field
     * @param stdClass $object - the dataobject used to create a mobile item
     * @param array $args
     * @param execution_context $ec
     * @return mixed
     */
    public static function resolve(string $field, $object, array $args, execution_context $ec) {
        global $CFG;

        try {
            $item = mobile_item::create($object);
        } catch (\throwable $e) {
            throw new \coding_exception('Only mobile_item catalog objects are accepted: ' . gettype($object));
        }

        $format = $args['format'] ?? null;
        $context = self::get_item_context($object);
        $data = (object) $item->get_template_data();

        // Any field customisations we need happen here in this switch.
        switch ($field) {
            case 'id':
                return $object->id;
            case 'itemid':
                return $object->objectid;
            case 'item_type':
                return $data->objecttype;
            case 'image_enabled':
                return isset($data->image_enabled) ?? false;
            case 'summary':
                // This one needs a bit of special handling.
                $data->summary = self::find_dataholder_contents($object->data, 'summary_rich');
                break;
            case 'summary_format':
                // Note: The catalog dataholder has pre-formatted this, even if it was saved as json
                // it has been formatted into HTML by this point.
                return 'HTML';
            case 'image_url':
            case 'image_alt':
                $enabled = isset($data->image_enabled) ?? false;
                if ($enabled) {
                    $image = $data->image;
                    $data->image_url = $image->url;
                    $data->image_alt = $image->alt;
                } else {
                    return null;
                }
                break;
            case 'description_enabled':
                return isset($data->description_enabled) ?? false;
            case 'description':
                $enabled = isset($data->description_enabled) ?? false;
                if (!$enabled || empty($data->description)) {
                    return null;
                }
                break;
        }

        $formatter = new item_formatter($data, $context);
        $formatted = $formatter->format($field, $format);
        if (in_array($field, ['image'])) {
            $formatted = str_replace($CFG->wwwroot . '/pluginfile.php', $CFG->wwwroot . '/totara/mobile/pluginfile.php', $formatted);
        }

        return $formatted;
    }

    /**
     * Extract the summary from the list of additional dataholders.
     */
    private static function find_dataholder_contents($dataholders, $key = 'summary_rich'): string {
        $data = '';
        foreach ($dataholders as $dataholder) {
            if (isset($dataholder[$key])) {
                $data = $dataholder[$key];
                break;
            }
        }

        return $data;
    }

    /**
     * @param stdClass $object
     * @return \context
     */
    private static function get_item_context($object) {
        switch ($object->objecttype) {
            case 'course':
            case 'playlist':
            case 'engage_article':
                return \context::instance_by_id($object->contextid, MUST_EXIST);
            case 'program':
            case 'certification':
            default:
                throw new \coding_exception('Unexpected mobile_item type, mobile catalog does not support: ' . $object->objecttype);
        }
    }
}
