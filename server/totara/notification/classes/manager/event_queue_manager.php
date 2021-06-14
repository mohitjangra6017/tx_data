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
use Exception;
use core\orm\query\builder;
use null_progress_trace;
use progress_trace;
use totara_notification\entity\notifiable_event_queue;
use totara_notification\loader\notification_preference_loader;
use totara_notification\local\helper;
use totara_notification\local\notification_queue_helper;
use totara_notification\resolver\abstraction\additional_criteria_resolver;
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

        /** @var notifiable_event_queue $queue */
        foreach ($all_queues as $queue) {
            try {
                builder::get_db()->transaction(function () use ($queue) {
                    if (!resolver_helper::is_valid_event_resolver($queue->resolver_class_name)) {
                        throw new coding_exception(
                            "The resolver class name is not a notifiable event resolver: '{$queue->resolver_class_name}'"
                        );
                    }

                    $resolver_class_name = $queue->resolver_class_name;
                    $extended_context = $queue->get_extended_context();
                    $is_additional_criteria_resolver = resolver_helper::is_additional_criteria_resolver($resolver_class_name);

                    $is_enabled_in_all_parents = helper::is_resolver_enabled_for_all_parent_contexts(
                        $resolver_class_name,
                        $extended_context
                    );
                    $current_resolver_enabled = helper::is_resolver_enabled($resolver_class_name, $extended_context);

                    // process only enabled queues
                    if ($current_resolver_enabled && $is_enabled_in_all_parents) {
                        $preferences = notification_preference_loader::get_notification_preferences(
                            $queue->get_extended_context(),
                            $queue->resolver_class_name
                        );

                        foreach ($preferences as $preference) {
                            if (!$preference->is_on_event()) {
                                // Skip those notification preference that are not set for on event.
                                continue;
                            }

                            $event_data = $queue->get_decoded_event_data();

                            if ($is_additional_criteria_resolver) {
                                $raw_additional_criteria = $preference->get_additional_criteria();
                                if (empty($raw_additional_criteria)) {
                                    $additional_criteria = null;
                                } else {
                                    $additional_criteria = @json_decode(
                                        $raw_additional_criteria,
                                        true,
                                        32,
                                        JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE | JSON_BIGINT_AS_STRING
                                    );
                                    if (!is_array($additional_criteria)) {
                                        throw new exception('json decoding failed');
                                    }
                                }

                                /** @var additional_criteria_resolver $resolver_class_name */
                                if (!$resolver_class_name::is_valid_additional_criteria($additional_criteria, $extended_context)) {
                                    continue;
                                }

                                if (!$resolver_class_name::meets_additional_criteria(
                                    $additional_criteria,
                                    $event_data
                                )) {
                                    continue;
                                }
                            }

                            notification_queue_helper::create_queue_from_preference(
                                $preference,
                                $queue->get_decoded_event_data(),
                                $queue->time_created
                            );
                        }
                    }

                    // delete both disabled and processed queues
                    $queue->delete();
                });
            } catch (Exception $exception) {
                $this->trace->output(
                    "Cannot send notification event queue record with id '{$queue->id}'"
                );
            }
        }

        $all_queues->close();
    }
}
