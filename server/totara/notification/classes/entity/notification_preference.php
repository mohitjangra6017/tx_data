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
namespace totara_notification\entity;

use core\orm\entity\entity;
use totara_notification\repository\notification_preference_repository;

/**
 * An entity class that represent for a row of table "ttr_notification_preference"
 *
 * @property int         $id
 * @property int|null    $ancestor_id
 * @property string      $event_class_name
 * @property string      $notification_class_name
 * @property int         $context_id
 * @property string|null $title
 * @property string|null $recipient
 * @property string|null $subject
 * @property string|null $body
 * @property int|null    $body_format
 * @property int         $time_created
 *
 * @method static notification_preference_repository repository()
 */
class notification_preference extends entity {
    /**
     * @var string
     */
    public const TABLE = 'notification_preference';

    /**
     * @var string
     */
    public const CREATED_TIMESTAMP = 'time_created';

    /**
     * @return string
     */
    public static function repository_class_name(): string {
        return notification_preference_repository::class;
    }
}