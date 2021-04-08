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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Murali Nair <murali.nair@totaralearning.com>
 * @package hierarchy_goal
 */

namespace hierarchy_goal\entity;

use core\entity\user;
use core\orm\entity\entity;
use core\orm\entity\relations\belongs_to;

/**
 * Represents a personal goal record in the repository.
 *
 * @property-read int $id record id
 * @property int $userid user this goal belongs to
 * @property string $name goal name
 * @property string $description goal description
 * @property int $targetdate expected achievement date
 * @property int $scaleid associated scale
 * @property int $scalevalueid associated scale value
 * @property int $assigntype assignment type
 * @property int $timecreated time created
 * @property int $usercreated time created
 * @property int $timemodified time modified
 * @property int $usermodified time modified
 * @property bool $deleted deletion flag
 * @property int $typeid goal type ID
 * @property bool $visible visibility flag
 * @property-read user $user user this goal belongs to
 * @property-read personal_goal_type $type personal goal type
 * @property-read scale $scale goal scoring scale
 * @property-read scale_value $scale_value goal score
 *
 * @method static personal_goal_repository repository()
 */
class personal_goal extends entity {
    public const TABLE = 'goal_personal';

    /**
     * Establishes the relationship with user entities.
     *
     * @return belongs_to the relationship.
     */
    public function type(): belongs_to {
        return $this->belongs_to(personal_goal_type::class, 'typeid');
    }

    /**
     * Establishes the relationship with user entities.
     *
     * @return belongs_to the relationship.
     */
    public function user(): belongs_to {
        return $this->belongs_to(user::class, 'userid');
    }

    /**
     * Establishes the relationship with scale entities.
     *
     * @return belongs_to the relationship.
     */
    public function scale(): belongs_to {
        return $this->belongs_to(scale::class, 'scaleid');
    }

    /**
     * Establishes the relationship with scale entities.
     *
     * @return belongs_to the relationship.
     */
    public function scale_value(): belongs_to {
        return $this->belongs_to(scale_value::class, 'scalevalueid');
    }
}
