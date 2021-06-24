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
namespace mod_contentmarketplace\local;

use mod_contentmarketplace\completion\condition;
use mod_contentmarketplace\entity\content_marketplace as content_marketplace_entity;
use mod_contentmarketplace\model\content_marketplace;
use mod_contentmarketplace\exception\non_exist_learning_object;
use totara_contentmarketplace\learning_object\factory;

/**
 * An internal helper API.
 */
class helper {
    /**
     * helper constructor.
     */
    private function __construct() {
        // Prevent this class from instantiation.
    }

    /**
     * Given the $learning_object_id and the $marketplace_type, this function can
     * create a record in table "ttr_contentmarketplace" that can link to one of
     * the learning object from the content marketplace sub plugin.
     *
     * @param int    $course_id
     * @param int    $learning_object_id
     * @param string $marketplace_component
     *
     * @return content_marketplace
     */
    public static function create_content_marketplace(
        int $course_id,
        int $learning_object_id,
        string $marketplace_component
    ): content_marketplace {
        $resolver = factory::get_resolver($marketplace_component);
        $learning_object = $resolver->find($learning_object_id);

        if (null === $learning_object) {
            throw new non_exist_learning_object($marketplace_component);
        }

        return content_marketplace::create($course_id, $learning_object);
    }

    /**
     * @param int      $content_marketplace_id
     * @param int|null $completion_condition    One of the condition constants from {@see condition}, or null to
     *                                          unset the condition.
     *
     * @return void
     */
    public static function update_content_marketplace(int $content_marketplace_id, ?int $completion_condition): void {
        if (!empty($completion_condition)) {
            condition::validate($completion_condition);
        }

        $entity = new content_marketplace_entity($content_marketplace_id);
        $entity->completion_condition = $completion_condition;

        $entity->save();
    }
}