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
use moodle_recordset;
use null_progress_trace;
use progress_trace;
use totara_notification\factory\notifiable_event_resolver_factory;
use totara_notification\loader\notification_preference_loader;
use totara_notification\local\event_resolver_schedule;
use totara_notification\local\notification_queue_helper;
use totara_notification\resolver\abstraction\scheduled_event_resolver;
use totara_notification\resolver\resolver_helper;
use totara_notification\schedule\time_window;

class scheduled_event_manager {
    /**
     * @var progress_trace
     */
    private $trace;

    /**
     * scheduled_event_manager constructor.
     * @param progress_trace|null $trace
     */
    public function __construct(?progress_trace $trace = null) {
        $this->trace = $trace ?? new null_progress_trace();
    }

    /**
     * @param int|null $time_now        The epoch time of now
     * @param int|null $last_cron_time  The epoch time of last cron time, which it can be either 1 day before now or
     *                                  couple hours before now.
     *
     * @return void
     */
    public function execute(?int $time_now = null, ?int $last_cron_time = null) {
        $time_now = $time_now ?? time();
        $last_cron_time = $last_cron_time ?? $time_now;

        $this->trace->output("Current time is '{$time_now}'");
        $this->trace->output("Last cron time is '{$last_cron_time}'");

        // Cannot allow last cron time to be greater than the current time now.
        $current_window = new time_window($last_cron_time, $time_now);
        $current_window->validate();

        $resolver_classes = notifiable_event_resolver_factory::get_scheduled_resolver_classes();
        foreach ($resolver_classes as $resolver_cls) {
            $resolver_schedule = event_resolver_schedule::instance($resolver_cls);
            $min_schedule_offset = $resolver_schedule->get_minimum_offset();
            $max_schedule_offset = $resolver_schedule->get_maximum_offset();

            if (null === $min_schedule_offset && null === $max_schedule_offset) {
                // We are checking if the resolver has associated notifiable event in order to prevent the
                // further calculation if min and max are both empty. Meaning that there is no such scheduled
                // notification preference created yet.

                // This is a debugging for intentionally for the admin/devs. Therefore no localised string.
                $this->trace->output("The resolver class '{$resolver_cls}' does not have any scheduled preference.");
                $this->trace->output(
                    "Furthermore the resolver does not implement interface for on event schedule, hence it is skipped."
                );

                continue;
            }

            // Max time is the time now minus the minimum schedule time.
            $max_time = ($time_now - $min_schedule_offset);

            // Min time is the last cron time minus the maximum schedule time.
            $min_time = ($last_cron_time - $max_schedule_offset);

            // Now we have the windows of min time and max time.
            $min_max_window = new time_window($min_time, $max_time);
            $min_max_window->validate();

            // -----$min-----------$last_cron------------$time_now-------------------$max-------->
            $this->do_execute($min_max_window, $current_window, $resolver_schedule);
        }
    }

    /**
     * @param time_window             $min_max_window      The calculated min and max time window.
     * @param time_window             $current_window      The current time window between the last cron time and the
     *                                                     time now.
     * @param event_resolver_schedule $resolver_schedule   The scheduled event resolver wrapper that contains the
     *                                                     resolver class name and the cached result
     *                                                     of associated event related to the resolver class.
     * @return void
     */
    protected function do_execute(
        time_window $min_max_window,
        time_window $current_window,
        event_resolver_schedule $resolver_schedule
    ): void {
        $resolver_class_name = $resolver_schedule->get_resolver_class_name();

        if (!resolver_helper::is_valid_scheduled_event_resolver($resolver_class_name)) {
            $interface_name = scheduled_event_resolver::class;
            throw new coding_exception(
                "The resolver class '{$resolver_class_name}' does not implement the interface '{$interface_name}'"
            );
        }

        /**
         * @see scheduled_event_resolver::get_scheduled_events()
         * @var moodle_recordset $events
         */
        $events = call_user_func_array(
            [$resolver_class_name, 'get_scheduled_events'],
            [$min_max_window->get_min_time(), $min_max_window->get_max_time()]
        );

        foreach ($events as $event_data) {
            // Event data might be array or stdClass. We always use it as an array.
            $event_data = (array)$event_data;

            /** @var scheduled_event_resolver $resolver */
            $resolver = resolver_helper::instantiate_resolver_from_class(
                $resolver_class_name,
                $event_data
            );

            $extended_context = $resolver->get_extended_context();
            $fixed_event_time = $resolver->get_fixed_event_time();

            if (0 >= $fixed_event_time) {
                throw new coding_exception("Invalid event time resolved by the resolver");
            }

            $preferences = notification_preference_loader::get_notification_preferences(
                $extended_context,
                $resolver_class_name
            );

            $this->trace->output("Queuing notifications for event resolver '{$resolver_class_name}'");

            foreach ($preferences as $preference) {
                if ($resolver_schedule->uses_on_event_queue() && $preference->is_on_event()) {
                    // Skip those preference that are set for on event, when the resolver had
                    // already had notifiable event interface.
                    continue;
                }

                // Skip disabled preferences
                if (!$preference->get_enabled()) {
                    continue;
                }

                // Checking each preference regarding to the time sending.
                if ($preference->is_in_time_window($fixed_event_time, $current_window)) {
                    // Fixed event time is within the time frame - therefore we can queue it.
                    notification_queue_helper::create_queue_from_preference(
                        $preference,
                        $event_data,
                        $fixed_event_time
                    );
                }
            }
        }

        $events->close();
    }
}