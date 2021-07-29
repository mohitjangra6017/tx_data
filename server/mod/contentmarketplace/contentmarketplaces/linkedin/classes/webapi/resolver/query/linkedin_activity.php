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
 * @package contentmarketplaceactivity_linkedin
 */
namespace contentmarketplaceactivity_linkedin\webapi\resolver\query;

use contentmarketplace_linkedin\model\learning_object;
use mod_contentmarketplace\webapi\resolver\query\content_marketplace;
use totara_contentmarketplace\learning_object\abstraction\metadata\model;

/**
 * Class linkedin_activity
 */
final class linkedin_activity extends content_marketplace {

    /**
     * @param int $learning_object_id
     * @return model
     */
    protected static function get_learning_object(int $learning_object_id): model {
        return learning_object::load_by_id($learning_object_id);
    }
}