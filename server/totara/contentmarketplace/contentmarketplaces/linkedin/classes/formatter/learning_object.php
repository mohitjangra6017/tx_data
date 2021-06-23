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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

namespace contentmarketplace_linkedin\formatter;

use contentmarketplace_linkedin\model\learning_object as learning_object_model;
use core\orm\formatter\entity_model_formatter;
use core\webapi\formatter\field\date_field_formatter;
use core\webapi\formatter\field\string_field_formatter;

class learning_object extends entity_model_formatter {
    /**
     * @param string $field
     * @return mixed|string
     */
    protected function get_field(string $field) {
        if ('subject' == $field) {
            /** @var learning_object_model $learning_object */
            $learning_object = $this->object;
            $first_subject = $learning_object->get_first_subject();

            return (null === $first_subject) ? '' : $first_subject->name;
        }

        return parent::get_field($field);
    }

    /**
     * @param string $field
     * @return bool
     */
    protected function has_field(string $field): bool {
        if ('subject' === $field) {
            return true;
        }

        return parent::has_field($field);
    }

    /**
     * @return array
     */
    protected function get_map(): array {
        return [
            'id' => null,
            'name' => string_field_formatter::class,
            'description' => string_field_formatter::class,
            'description_include_html' => string_field_formatter::class,
            'short_description' => string_field_formatter::class,
            'last_updated_at' => date_field_formatter::class,
            'published_at' => date_field_formatter::class,
            'subject' => string_field_formatter::class,
            'level' => null,
            'time_to_complete' => timespan_field_formatter::class,
            'asset_type' => null,
            'language' => null,
            'image_url' => null,
            'courses' => null,
        ];
    }

}
