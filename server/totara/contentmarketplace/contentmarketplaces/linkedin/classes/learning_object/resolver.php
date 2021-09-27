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
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\learning_object;

use contentmarketplace_linkedin\entity\learning_object as learning_object_entity;
use contentmarketplace_linkedin\entity\user_completion;
use contentmarketplace_linkedin\model\learning_object;
use core\orm\entity\entity as core_entity;
use totara_contentmarketplace\learning_object\abstraction\metadata\model;
use totara_contentmarketplace\learning_object\abstraction\resolver as base;

class resolver extends base {

    /**
     * @return string|learning_object_entity
     */
    public static function get_entity_class(): string {
        return learning_object_entity::class;
    }

    /**
     * @return string
     */
    public static function get_external_id_field(): string {
        return 'urn';
    }

    /**
     * @param core_entity $entity
     * @return model
     */
    protected static function load_model_from_entity(core_entity $entity): model {
        return learning_object::load_by_entity($entity);
    }

    /**
     * Checking whether user had already been progressing on the linkedin learning's course or not.
     * If user had already progressing on linkedin learning's course, and we are keeping track of it,
     * then we should mark the activity as completed, since the condition is on launch.
     *
     * @param int $user_id
     * @param int $learning_object_id
     *
     * @return bool
     */
    public function has_user_completed_on_launch(int $user_id, int $learning_object_id): bool {
        $learning_object = new learning_object_entity($learning_object_id);
        $repository = user_completion::repository();

        $completion = $repository->find_for_user_with_urn($user_id, $learning_object->urn);
        return null !== $completion;
    }

    /**
     * Checking if user had already completed the linkedin learning course or not. If yes,
     * then we are going to mark the activity completion as completed, since this is based
     * on Linkedin Learning condition.
     *
     * @param int $user_id
     * @param int $learning_object_id
     *
     * @return bool
     */
    public function has_user_completed_on_marketplace_condition(int $user_id, int $learning_object_id): bool {
        $learning_object = new learning_object_entity($learning_object_id);
        $repository = user_completion::repository();

        $completion = $repository->find_for_user_with_urn($user_id, $learning_object->urn);
        if (null !== $completion) {
            // Check on the completion status.
            return $completion->completion;
        }

        // Record is not found hence, user had not yet completed the learning object.
        return false;
    }
}