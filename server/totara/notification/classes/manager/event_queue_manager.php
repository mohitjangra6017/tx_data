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
namespace totara_notification\manager;

use coding_exception;
use core\orm\query\builder;
use null_progress_trace;
use progress_trace;
use totara_notification\entity\notifiable_event_queue;
use totara_notification\loader\notification_preference_loader;
use totara_notification\local\helper;
use totara_notification\local\notification_queue_helper;
use totara_notification\resolver\resolver_helper;

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

                $preferences = notification_preference_loader::get_notification_preferences(
                    $queue->get_extended_context(),
                    $queue->resolver_class_name
                );

                foreach ($preferences as $preference) {
                    if (!$preference->is_on_event()) {
                        // Skip those notification preference that are not set for on event.
                        continue;
                    }

                    notification_queue_helper::create_queue_from_preference(
                        $preference,
                        $queue->get_decoded_event_data(),
                        $queue->time_created
                    );
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
        if (!resolver_helper::is_valid_event_resolver($queue->resolver_class_name)) {
            $this->trace->output("The resolver class name is not a notifiable event resolver: '{$queue->resolver_class_name}'");
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