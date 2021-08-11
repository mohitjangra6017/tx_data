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
use core\entity\course;
use core\orm\entity\model;
use core_component;
use mod_contentmarketplace\entity\content_marketplace as model_entity;
use moodle_url;
use stdClass;
use totara_contentmarketplace\learning_object\abstraction\metadata\model as learning_object;
use totara_contentmarketplace\learning_object\factory;

/**
 * Model for content marketplace entity.
 *
 * @property-read int             $course_id
 * @property-read course          $course
 * @property-read string          $name
 * @property-read string          $learning_object_marketplace_component
 * @property-read int             $learning_object_id
 * @property-read int             $time_modified
 * @property-read int             $cm_id
 * @property-read int             $completion_condition
 * @property-read learning_object $learning_object
 * @property-read moodle_url      $view_url
 * @property-read string          $activity_module_marketplace_component
 *
 */
class content_marketplace extends model {

    /**
     * @var model_entity
     */
    protected $entity;

    /**
     * @var context_module|null
     */
    private $context;

    /**
     * Will be lazy loaded by the getter method.
     * @var learning_object|null
     */
    private $internal_learning_object;

    /**
     * @var string[]
     */
    protected $entity_attribute_whitelist = [
        'id',
        'name',
        'learning_object_marketplace_component',
        'learning_object_id',
        'time_modified',
        'completion_condition',
    ];

    /**
     * @var string[]
     */
    protected $model_accessor_whitelist = [
        'cm_id',
        'learning_object',
        'view_url',
        'activity_module_marketplace_component',
        'course',
        'course_id',
    ];

    /**
     * content_marketplace constructor.
     * @param model_entity $entity
     */
    public function __construct(model_entity $entity) {
        parent::__construct($entity);
        $this->context = null;
        $this->internal_learning_object = null;
    }

    /**
     * @param int $course_id
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

        $model = self::load_by_entity($entity);
        $model->internal_learning_object = $learning_object;
        return $model;
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
                $this->course_id,
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
     * @return course
     */
    public function get_course(): course {
        return $this->entity->course_entity;
    }

    /**
     * @return int
     */
    public function get_course_id(): int {
        return $this->entity->course;
    }

    /**
     * @return bool
     */
    public function delete(): bool {
        if ($this->is_deleted()) {
            debugging(
                "The record had already been deleted",
                DEBUG_DEVELOPER
            );

            return false;
        }

        $this->entity->delete();
        return $this->is_deleted();
    }

    /**
     * @return bool
     */
    public function is_deleted(): bool {
        return $this->entity->deleted();
    }

    /**
     * @return learning_object
     */
    public function get_learning_object(): learning_object {
        if (null === $this->internal_learning_object) {
            $resolver = factory::get_resolver($this->learning_object_marketplace_component);
            $this->internal_learning_object = $resolver->find($this->learning_object_id, true);
        }

        return $this->internal_learning_object;
    }

    /**
     * @return moodle_url
     */
    public function get_view_url(): moodle_url {
        return new moodle_url(
            '/mod/contentmarketplace/view.php',
            ['id' => $this->get_id()]
        );
    }

    /**
     * @return stdClass
     */
    public function get_entity_record(): stdClass {
        return $this->entity->to_record();
    }

    /**
     * @return string
     */
    public function get_activity_module_marketplace_component(): string {
        [$plugin_type, $plugin_name] = core_component::normalize_component($this->learning_object_marketplace_component);
        if (is_null($plugin_type) || is_null($plugin_name)) {
            throw new coding_exception("{$this->learning_object_marketplace_component} does not exist");
        }

        return 'contentmarketplaceactivity_' . $plugin_name;
    }
}