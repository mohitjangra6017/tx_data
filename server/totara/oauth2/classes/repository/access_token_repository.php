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
 * @package totara_oauth2
 */
namespace totara_oauth2\repository;

use core\orm\entity\repository;
use totara_oauth2\entity\access_token;

/**
 * Repository layer for table "ttr_oauth2_access_token"
 * @method access_token|null one(bool $strict = false)
 */
class access_token_repository extends repository {
    /**
     * Returns null when access token is not found, otherwise an entity of access_token.
     * Provide $time_now if we would want to find the access token that is not yet expired.
     * Otherwise, null to fetch the access token that is either expired or not.
     *
     * @param string $token
     * @param int|null $time_now
     *
     * @return access_token|null
     */
    public function find_by_token(string $token, ?int $time_now = null): ?access_token {
        $repository = access_token::repository();
        $repository->where("access_token", $token);

        if (null !== $time_now) {
            $repository->where("expires", ">=", $time_now);
        }

        return $repository->one();
    }
}