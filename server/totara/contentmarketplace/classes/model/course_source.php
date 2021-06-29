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

namespace totara_contentmarketplace\model;

use container_course\course;
use core\orm\entity\model;
use totara_contentmarketplace\entity\course_source as course_source_entity;
use totara_contentmarketplace\learning_object\abstraction\metadata\model as learning_object_model;

/**
 * Model class for course_source.
 *
 * @property-read int     $id
 * @property-read string  $marketplace_component
 * @property-read int     $learning_object_id
 * @property-read int     $course_id
 *
 */
class course_source extends model {
    /**
     * @var course_source_entity
     */
    protected $entity;

    /**
     * @var string[]
     */
    protected $entity_attribute_whitelist = [
        'id',
        'marketplace_component',
        'learning_object_id',
        'course_id'
    ];

    /**
     * @return string
     */
    protected static function get_entity_class(): string {
        return course_source_entity::class;
    }

    /**
     * @param course $course
     * @param learning_object_model $learning_object
     * @return static
     */
    public static function create(course $course, learning_object_model $learning_object): self {
        $entity = new course_source_entity();
        $entity->course_id = $course->id;
        $entity->marketplace_component = $learning_object::get_marketplace_component();
        $entity->learning_object_id = $learning_object->get_id();

        $entity->save();

        return static::load_by_entity($entity);
    }
}