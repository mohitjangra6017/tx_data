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
 * @author Riana Rossouw <riana.rossouw@totaralearning.com>
 * @package totara_notification
 */

namespace totara_notification\entity;

use core\entity\user;
use core\orm\entity\entity;
use core\orm\entity\relations\belongs_to;
use totara_core\extended_context;
use totara_notification\repository\notifiable_event_user_preference_repository;

/**
 * An entity class that represent for table "ttr_notifiable_event_user_preference"
 *
 * @property int $id
 * @property int $user_id
 * @property string $resolver_class_name
 * @property int $context_id
 * @property string $component
 * @property string $area
 * @property int $item_id
 * @property bool $enabled
 *
 * @property-read user $user
 *
 * @method static notifiable_event_user_preference_repository repository()
 */
class notifiable_event_user_preference extends entity {

    public const TABLE = 'notifiable_event_user_preference';

    /**
     * User
     *
     * @return belongs_to
     */
    public function user(): belongs_to {
        return $this->belongs_to(user::class, 'user_id');
    }

    /**
     * @return string
     */
    public static function repository_class_name(): string {
        return notifiable_event_user_preference_repository::class;
    }

    /**
     * @return extended_context
     */
    public function get_extended_context(): extended_context {
        return extended_context::make_with_id(
            $this->context_id,
            $this->component,
            $this->area,
            $this->item_id
        );
    }

    /**
     * @param extended_context $extended_context
     * @return void
     */
    public function set_extended_context(extended_context $extended_context): void {
        $this->set_attribute('context_id', $extended_context->get_context_id());
        $this->set_attribute('component', $extended_context->get_component());
        $this->set_attribute('area', $extended_context->get_area());
        $this->set_attribute('item_id', $extended_context->get_item_id());
    }

}