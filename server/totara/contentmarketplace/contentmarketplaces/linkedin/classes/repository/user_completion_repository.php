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
namespace contentmarketplace_linkedin\repository;

use contentmarketplace_linkedin\dto\xapi\progress;
use contentmarketplace_linkedin\entity\user_completion;
use core\orm\entity\entity;
use core\orm\entity\repository;
use coding_exception;

/**
 * A repository for table "ttr_linkedin_user_completion".
 *
 * @method user_completion one(bool $strict = false)
 */
class user_completion_repository extends repository {
    /**
     * @param user_completion $entity
     * @return user_completion
     */
    public function save_entity(entity $entity): entity {
        $completion = $entity->completion;

        // This is a mini validation that allow us to keep the integrity of data.
        if ($completion && $entity->progress < progress::PROGRESS_MAXIMUM) {
            throw new coding_exception(
                "Cannot save the user completion that identify as completed but also in progress"
            );
        } else if (!$completion && $entity->progress === progress::PROGRESS_MAXIMUM) {
            // This would need to be tweaked when there are more than just progressed status.
            throw new coding_exception(
                "Cannot save the user completion that identify as not completed but also not in progressed"
            );
        }

        return parent::save_entity($entity);
    }

    /**
     * @param int    $user_id
     * @param string $urn
     *
     * @return user_completion|null
     */
    public function find_for_user_with_urn(int $user_id, string $urn): ?user_completion {
        $repository = user_completion::repository();
        $repository->where("user_id", $user_id);
        $repository->where("learning_object_urn", $urn);

        return $repository->one();
    }

    /**
     * Checks if there are record existing for user against the learning object urn.
     * When $completion is provided, we are searching for the completion status record too.
     * Otherwise, we are ignoring the completion status record.
     *
     * @param string $user_id
     * @param string $urn
     * @param bool|null $completion
     * @return bool
     */
    public function exists_for_user(string $user_id, string $urn, ?bool $completion = null): bool {
        $repository = user_completion::repository();
        $repository->where("user_id", $user_id);
        $repository->where("learning_object_urn", $urn);

        if (null !== $completion) {
            $repository->where("completion", $completion ? 1 : 0);
        }

        return $repository->exists();
    }
}