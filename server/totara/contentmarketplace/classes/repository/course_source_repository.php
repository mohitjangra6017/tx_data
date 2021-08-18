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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_contentmarketplace
 */

namespace totara_contentmarketplace\repository;

use core\orm\entity\repository;
use core\orm\lazy_collection;
use totara_contentmarketplace\entity\course_source;

class course_source_repository extends repository {
    /**
     * @param int         $course_id
     * @param int|null    $learning_object_id
     * @param string|null $marketplace_component
     *
     * @return void
     */
    public function delete_by_course_id(int $course_id, ?int $learning_object_id = null, ?string $marketplace_component = null): void {
        $repository = course_source::repository()->where('course_id', $course_id);

        if (!empty($learning_object_id) && !empty($marketplace_component)) {
            $repository->where('learning_object_id', $learning_object_id)
                ->where('marketplace_component', $marketplace_component);
        }

        $repository->delete();
    }

    /**
     * @param int    $learning_object_id
     * @param string $marketplace_component
     *
     * @return lazy_collection
     */
    public function fetch_by_id_and_component(int $learning_object_id, string $marketplace_component): lazy_collection {
        $repository = course_source::repository();
        $repository->where("learning_object_id", $learning_object_id);
        $repository->where("marketplace_component", $marketplace_component);

        return $repository->get_lazy();
    }
}