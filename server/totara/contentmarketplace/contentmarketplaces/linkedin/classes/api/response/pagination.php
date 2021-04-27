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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\api\response;

use coding_exception;
use core\json\schema\field\field_alpha;
use core\json\schema\field\field_collection;
use core\json\schema\field\field_int;
use core\json\schema\field\field_text;
use core\json\schema\object_container;
use core\json\schema\collection as collection_schema;

class pagination {
    /**
     * The json data.
     *
     * @var array
     */
    protected $json_data;

    /**
     * pagination constructor.
     * @param array $json_data
     */
    protected function __construct(array $json_data) {
        $this->json_data = $json_data;
    }

    /**
     * @return object_container
     */
    public static function get_json_schema(): object_container {
        return object_container::create(
            new field_int('total'),
            new field_int('count'),
            new field_int('start'),
            new field_collection(
                'links',
                collection_schema::create_from_fields_of_obj_container(
                    new field_alpha('rel'),
                    new field_text('href'),
                    new field_text('type')
                ),
            ),
        );
    }

    /**
     * @param array $json_data
     * @return pagination
     */
    public static function create(array $json_data): pagination {
        $schema = static::get_json_schema();
        $error = $schema->validate($json_data);

        if (!empty($error)) {
            throw new coding_exception(
                "Failed to validate json data: {$error}"
            );
        }

        $json_data = $schema->clean($json_data);
        return new static($json_data);
    }

    /**
     * @return int
     */
    public function get_total(): int {
        return $this->json_data['total'];
    }

    /**
     * @return int
     */
    public function get_count(): int {
        return $this->json_data['count'];
    }

    /**
     * @return int
     */
    public function get_start(): int {
        return $this->json_data['start'];
    }

    /**
     * @param string $rel
     * @return string|null
     */
    protected function find_link_from_rel(string $rel): ?string {
        $links = $this->json_data['links'];
        foreach ($links as $link) {
            if ($link['rel'] === $rel) {
                return $link['href'];
            }
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function get_previous_link(): ?string {
        return $this->find_link_from_rel('prev');
    }

    /**
     * @return string|null
     */
    public function get_next_link(): ?string {
        return $this->find_link_from_rel('next');
    }
}