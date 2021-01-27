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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
namespace totara_notification\manager;

use coding_exception;
use core\orm\query\builder;
use null_progress_trace;
use progress_trace;
use totara_notification\entity\notifiable_event_queue;
use totara_notification\entity\notification_queue;
use totara_notification\factory\built_in_notification_factory;
use totara_notification\local\helper;

class event_queue_manager {
    /**
     * @var progress_trace
     */
    private $trace;

    /**
     * event_queue_manager constructor.
     * @param progress_trace|null $trace
     */
    public function __construct(?progress_trace $trace = null) {
        $this->trace = $trace ?? new null_progress_trace();
    }

    /**
     * @return void
     */
    public function process_queues(): void {
        $repository = notifiable_event_queue::repository();
        $all_queues = $repository->get_lazy();
        try {
            $transaction = builder::get_db()->start_delegated_transaction();

            /** @var notifiable_event_queue $queue */
            foreach ($all_queues as $queue) {
                if (!$this->is_valid_queue($queue)) {
                    $this->trace->output(
                        "Cannot process the event queue at id: '{$queue->id}'"
                    );

                    // Skip this queue, note that we are not deleting it, because it might
                    // be helpful for debugging why it cannot be processed.
                    continue;
                }

                // This is where we are going to do all the look up on the notification either the built in or the custom ones.
                $notification_classes = built_in_notification_factory::get_notification_classes_of_notifiable_event(
                    $queue->event_name
                );

                foreach ($notification_classes as $notification_class) {
                    // Note: this is where we are checking whether the notification's preference
                    // is set to be disabled or not, so that we can queue up the resource. Furthermore,
                    // it is also worth to check any sort of legitimate of the target record/item. -
                    // this is sort of message for the next ver.
                    $notification_queue = new notification_queue();
                    $notification_queue->notification_name = $notification_class;
                    $notification_queue->event_data = $queue->event_data;
                    $notification_queue->context_id = $queue->context_id;

                    // Use now for the time being. Eventually, it will be resolved by the resolver.
                    $notification_queue->scheduled_time = time();
                    $notification_queue->save();
                }

                $queue->delete();
            }

            $transaction->allow_commit();
        } finally {
            $all_queues->close();
        }
    }

    /**
     * @param notifiable_event_queue $queue
     * @return bool
     */
    private function is_valid_queue(notifiable_event_queue $queue): bool {
        if (!helper::is_valid_notifiable_event($queue->event_name)) {
            $this->trace->output("The event class name is not a notifiable event: '{$queue->event_name}'");
            return false;
        }

        try {
            // Check that the event data is a valid json data.
            $queue->get_decoded_event_data();
        } catch (coding_exception $e) {
            $this->trace->output($e->getMessage());
            return false;
        }

        // All's well, ends well
        return true;
    }
}