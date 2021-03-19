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
 * @author  Alastair Munro <alastair.munro@totaralearning.com>
 * @package totara_notification
 */

namespace totara_notification\model;

use coding_exception;
use core\orm\entity\model;
use totara_core\extended_context;
use totara_notification\entity\notifiable_event_preference as entity;

/**
 * Class notifiable_event_preference
 *
 * @property int $id
 * @property string $resolver_class_name
 * @property-read int $context_id
 * @property-read string $component
 * @property-read string $area
 * @property-read int $item_id
 * @property extended_context $extended_context
 * @property bool $enabled
 */
class notifiable_event_preference extends model {

    /**
     * @var string[]
     */
    protected $entity_attribute_whitelist = [
        'id',
        'resolver_class_name',
    ];

    /**
     * @var string[]
     */
    protected $model_accessor_whitelist = [
        'enabled',
        'extended_context',
    ];

    /**
     * @param entity $entity
     * @return notifiable_event_preference
     */
    public static function from_entity(entity $entity): notifiable_event_preference {
        if (!$entity->exists()) {
            throw new coding_exception("Cannot instantiate a notification notifiable event from a non-existing entity");
        }

        return new notifiable_event_preference($entity);
    }

    /**
     * @param int $id
     * @return notifiable_event_preference
     */
    public static function from_id(int $id): notifiable_event_preference {
        $entity = new entity($id);
        return static::from_entity($entity);
    }

    /**
     * @param string $resolver_class_name
     * @param extended_context $extended_context
     * @return notifiable_event_preference
     */
    public static function create(string $resolver_class_name, extended_context $extended_context): notifiable_event_preference {
        $entity = new entity();
        $entity->resolver_class_name = $resolver_class_name;
        $entity->context_id = $extended_context->get_context_id();
        $entity->component = $extended_context->get_component();
        $entity->area = $extended_context->get_area();
        $entity->item_id = $extended_context->get_item_id();
        $entity->save();

        return static::from_entity($entity);
    }

    /**
     * @param bool $enabled
     */
    public function set_enabled(bool $enabled) {
        $this->entity->set_attribute('enabled', $enabled);
    }

    /**
     * @return bool
     */
    public function get_enabled(): bool {
        return (bool) $this->entity->enabled;
    }

    /**
     * @return string
     */
    protected static function get_entity_class(): string {
        return entity::class;
    }

    /**
     * @return extended_context
     */
    public function get_extended_context(): extended_context {
        return extended_context::make_with_id(
            $this->entity->get_attribute('context_id'),
            $this->entity->get_attribute('component'),
            $this->entity->get_attribute('area'),
            $this->entity->get_attribute('item_id')
        );
    }

    /**
     * @param extended_context $extended_context
     * @return void
     */
    public function set_extended_context(extended_context $extended_context): void {
        $this->entity->set_attribute('context_id', $extended_context->get_context_id());
        $this->entity->set_attribute('component', $extended_context->get_component());
        $this->entity->set_attribute('area', $extended_context->get_area());
        $this->entity->set_attribute('item_id', $extended_context->get_item_id());
    }

    /**
     * @return void
     */
    public function refresh(): void {
        $this->entity->refresh();
    }

    /**
     * @return void
     */
    public function delete(): void {
        $this->entity->delete();
    }

    /**
     * @return bool
     */
    public function exists(): bool {
        return $this->entity->exists();
    }

    /**
     * @return void
     */
    public function save(): void {
        $this->entity->save();
    }
}

