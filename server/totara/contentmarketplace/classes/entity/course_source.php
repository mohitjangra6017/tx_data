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
namespace totara_contentmarketplace\entity;

use core\orm\entity\entity;
use core\orm\entity\relations\belongs_to;
use core\entity\course;
use totara_contentmarketplace\repository\course_source_repository;

/**
 * Entity class represent for table "ttr_totara_contentmarketplace_course_source"
 *
 * @property int    $id
 * @property string $marketplace_component
 * @property int    $course_id
 * @property int    $learning_object_id
 *
 * @property-read course $course
 *
 * @method static course_source_repository repository()
 */
class course_source extends entity {
    /**
     * @var string
     */
    public const TABLE = 'totara_contentmarketplace_course_source';

    /**
     * @return string
     */
    public static function repository_class_name(): string {
        return course_source_repository::class;
    }

    /**
     * @return belongs_to
     */
    public function course(): belongs_to {
        return $this->belongs_to(course::class, "course_id");
    }
}