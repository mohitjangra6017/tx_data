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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
namespace totara_notification\task;

use core\orm\query\builder;
use core\task\scheduled_task;
use totara_notification\entity\notifiable_event_queue;
use totara_notification\entity\notification_queue;
use totara_notification\local\helper;

class process_event_queue_task extends scheduled_task {
    /**
     * @return string
     */
    public function get_name(): string {
        return get_string('process_event_queue_task', 'totara_notification');
    }

    /**
     * @return void
     */
    public function execute() {
        $repository = notifiable_event_queue::repository();
        $all_queues = $repository->get_lazy();

        /** @var notifiable_event_queue $queue */
        foreach ($all_queues as $queue) {
            $resolver = helper::get_resolver_from_notifiable_event(
                $queue->event_name,
                $queue->context_id,
                $queue->get_decoded_event_data()
            );

            $transaction = builder::get_db()->start_delegated_transaction();
            // This is where we are going to do all the look up on the notification either the built in or the custom ones

            $entity = new notification_queue();

            // Or use set_decoded_event_data instead
            $entity->event_data = $queue->event_data;
            $entity->context_id = $queue->context_id;
            $entity->time_created = $queue->time_created;
            $entity->notification_name = $this->get_name();

            $entity->save();

            // Removing the event queue, to make sure that we will not process this again.
            $queue->delete();
            $transaction->allow_commit();
        }

        $all_queues->close();
    }
}