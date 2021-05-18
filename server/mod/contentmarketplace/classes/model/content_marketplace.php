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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package mod_contentmarketplace
 */
namespace mod_contentmarketplace\model;

use container_course\module\course_module;
use context_module;
use core\orm\entity\model;
use mod_contentmarketplace\entity\content_marketplace as model_entity;
use totara_contentmarketplace\learning_object\abstraction\metadata\model as learning_object;

/**
 * Model for content marketplace entity.
 *
 * @property-read int    $course
 * @property-read string $name
 * @property-read string $learning_object_marketplace_component
 * @property-read int    $learning_object_id
 * @property-read int    $time_modified
 */
class content_marketplace extends model {
    /**
     * @var context_module|null
     */
    private $context;

    /**
     * @var string[]
     */
    protected $entity_attribute_whitelist = [
        'id',
        'course',
        'name',
        'learning_object_marketplace_component',
        'learning_object_id',
        'time_modified'
    ];

    /**
     * content_marketplace constructor.
     * @param model_entity $entity
     */
    public function __construct(model_entity $entity) {
        parent::__construct($entity);
        $this->context = null;
    }

    /**
     * @param int             $course_id
     * @param learning_object $learning_object
     *
     * @return content_marketplace
     */
    public static function create(int $course_id, learning_object $learning_object): content_marketplace {
        $entity = new model_entity();
        $entity->course = $course_id;
        $entity->learning_object_id = $learning_object->get_id();
        $entity->learning_object_marketplace_component = $learning_object::get_marketplace_component();
        $entity->name = $learning_object->get_name();

        $entity->save();
        return self::load_by_entity($entity);
    }

    /**
     * @return string
     */
    protected static function get_entity_class(): string {
        return model_entity::class;
    }

    /**
     * @param int $cm_id
     * @return content_marketplace
     */
    public static function from_course_module_id(int $cm_id): content_marketplace {
        $course_module = course_module::from_id($cm_id);
        $instance_id = $course_module->get_instance();

        $entity = new model_entity($instance_id);

        $content_marketplace = new static($entity);
        $content_marketplace->context = $course_module->get_context();

        return $content_marketplace;
    }

    /**
     * @return context_module
     */
    public function get_context(): context_module {
        if (null === $this->context) {
            $instance_id = $this->get_id();
            $cm_record = get_coursemodule_from_instance(
                'contentmarketplace',
                $instance_id,
                $this->course,
            );

            $this->context = context_module::instance($cm_record->id);
        }

        return $this->context;
    }

    /**
     * @return int
     */
    public function get_cm_id(): int {
        $context = $this->get_context();
        return $context->instanceid;
    }

    /**
     * @return bool
     */
    public function delete(): bool {
        $this->entity->delete();
        return $this->is_deleted();
    }

    /**
     * @return bool
     */
    public function is_deleted(): bool {
        return $this->entity->deleted();
    }
}