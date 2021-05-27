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

use contentmarketplace_linkedin\entity\learning_object as entity;
use contentmarketplace_linkedin\model\learning_object;
use totara_contentmarketplace\learning_object\abstraction\metadata\model;
use totara_contentmarketplace\learning_object\abstraction\resolver as base;

class resolver extends base {
    /**
     * @param int  $id
     * @param bool $strict
     * @return learning_object|null
     */
    public function find(int $id, bool $strict = false): ?model {
        $repository = entity::repository();

        if ($strict) {
            $entity = $repository->find_or_fail($id);
        } else {
            $entity = $repository->find($id);

            if (null === $entity) {
                return null;
            }
        }

        return new learning_object($entity);
    }
}