<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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

use coding_exception;
use core\orm\entity\entity;
use totara_notification\repository\notifiable_event_queue_repository;

/**
 * Entity class represent for table "ttr_notifiable_event_queue"
 *
 * @property int    $id
 * @property string $event_name
 * @property string $event_data     A json string, please use {@see notifiable_event_queue::get_decoded_event_data()} for a
 *                                  decoded version of this attribute. Note that the result returned will be an array.
 * @property int    $context_id
 * @property int    $time_created
 *
 * @method static notifiable_event_queue_repository repository()
 */
class notifiable_event_queue extends entity {
    /**
     * @var string
     */
    public const TABLE = 'notifiable_event_queue';

    /**
     * @var string
     */
    public const CREATED_TIMESTAMP = 'time_created';

    /**
     * @return array
     */
    public function get_decoded_event_data(): array {
        $json_data = $this->get_attribute('event_data');
        $result = json_decode($json_data, true);

        if (JSON_ERROR_NONE != json_last_error()) {
            throw new coding_exception(
                "Cannot decode the json data due to: " . json_last_error_msg()
            );
        }

        return $result;
    }

    /**
     * @param array $decoded_data
     * @return void
     */
    public function set_decoded_event_data(array $decoded_data): void {
        $json_data = json_encode($decoded_data, JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT);
        $this->set_attribute('event_data', $json_data);
    }

    /**
     * @return string
     */
    public static function repository_class_name(): string {
        return notifiable_event_queue_repository::class;
    }
}