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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
namespace totara_notification\repository;

use context_system;
use core\orm\entity\repository;
use totara_notification\entity\notification_preference;

/**
 * Repository for table "ttr_notification_preference"
 */
class notification_preference_repository extends repository {
    /**
     * Return null if there is no record under context_id and ancestor_id. Otherwise entity record.
     *
     * @param int $context_id
     * @param int $ancestor_id
     *
     * @return notification_preference|null
     */
    public function find_by_context_id_and_ancestor_id(int $context_id, int $ancestor_id): ?notification_preference {
        $this->builder->where('context_id', $context_id);
        $this->builder->where('ancestor_id', $ancestor_id);

        /** @var notification_preference|null $entity */
        $entity = $this->builder->one();
        return $entity;
    }

    /**
     * @param string $notification_class_name
     * @return notification_preference|null
     */
    public function find_in_system_context(string $notification_class_name): ?notification_preference {
        $context = context_system::instance();
        return $this->find_built_in($notification_class_name, $context->id);
    }

    /**
     * @param string $notification_class_name
     * @param int    $context_id
     * @return notification_preference|null
     */
    public function find_built_in(string $notification_class_name, int $context_id): ?notification_preference {
        $this->builder->where('context_id', $context_id);
        $this->builder->where('notification_class_name', ltrim($notification_class_name, '\\'));

        /** @var notification_preference|null $entity */
        $entity = $this->builder->one();
        return $entity;
    }
}