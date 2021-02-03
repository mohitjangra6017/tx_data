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
 * @author Alvin Smith <alvin.smith@totaralearning.com>
 * @package totara_notification
 */
namespace totara_notification\repository;

use core\orm\entity\repository;
use core\orm\lazy_collection;

/**
 * Repository for table "ttr_notification_queue"
 */
class notification_queue_repository extends repository {
    /**
     * Returns a list of notification queues that are less than the $current_time.
     * If $current_time is passed as either NULL or 0 then it would use the
     * {@see time()} from PHP.
     *
     * @param int|null $current_time
     * @return lazy_collection
     */
    public function get_due_notification_queues(?int $current_time = null): lazy_collection {
        if (empty($current_time)) {
            $current_time = time();
        }

        $this->builder->where('scheduled_time', '<=', $current_time);
        return $this->builder->get_lazy();
    }
}