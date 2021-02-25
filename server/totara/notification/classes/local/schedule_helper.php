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
 * @author  Cody Finegan <cody.finegan@totaralearning.com>
 * @package totara_notification
 */
namespace totara_notification\local;

use coding_exception;
use core_component;
use totara_notification\schedule\notification_schedule;
use totara_notification\schedule\schedule_after_event;
use totara_notification\schedule\schedule_before_event;
use totara_notification\schedule\schedule_on_event;

class schedule_helper {

    private static $schedule_types;

    /**
     * Return the results of get_notification_available_schedules for the specific event
     *
     * @param string $event_class_name
     * @return array
     */
    public static function get_available_schedules_for_event(string $event_class_name): array {
        if (!helper::is_valid_notifiable_event($event_class_name)) {
            throw new coding_exception("Event class name is an invalid notifiable event");
        }

        $valid_schedules = call_user_func([$event_class_name, 'get_notification_available_schedules']);
        $identifiers = [];
        foreach ($valid_schedules as $valid_schedule) {
            $identifiers[] = call_user_func([$valid_schedule, 'identifier']);
        }
        return $identifiers;
    }

    /**
     * Returns the specific class used for the schedule.
     * Because currently only before, on & after exist, the value of get_scheduled_offset will
     * drive this particular method. At some point this may be changed to support complicated options.
     *
     * @param int $offset
     * @return string
     */
    public static function get_schedule_class_from_offset(int $offset): string {
        if ($offset < 0) {
            return schedule_before_event::class;
        }
        if ($offset > 0) {
            return schedule_after_event::class;
        }

        return schedule_on_event::class;
    }

    /**
     * Find the matching schedule class based on the schedule type. This is used to map the GraphQL
     * schedule_type field back to the correct class, without passing PHP classes throughout GraphQL.
     *
     * @param string $type
     * @return string
     */
    public static function get_schedule_class_from_type(string $type): string {
        if (null === self::$schedule_types) {
            $schedule_type_classes = core_component::get_component_classes_in_namespace('totara_notification', 'schedule');
            $schedule_types = [];
            foreach ($schedule_type_classes as $schedule_type => $tree) {
                if (in_array(notification_schedule::class, class_implements($schedule_type))) {
                    $identifier = call_user_func([$schedule_type, 'identifier']);
                    $schedule_types[$identifier] = $schedule_type;
                }
            }
            self::$schedule_types = $schedule_types;
        }

        if (isset(self::$schedule_types[$type])) {
            return self::$schedule_types[$type];
        }

        throw new \coding_exception("Unknown schedule type of '${type}' provided");
    }

    /**
     * @param int $days_offset
     * @return string
     */
    public static function get_human_readable_schedule_label(int $days_offset): string {
        $schedule_class_name = self::get_schedule_class_from_offset($days_offset);
        return call_user_func([$schedule_class_name, 'get_label'], $days_offset);
    }

    /**
     * Helper to return the selected identifiers for the schedule
     *
     * @param int $days_offset
     * @return string
     */
    public static function get_schedule_identifier(int $days_offset): string {
        $schedule_class_name = self::get_schedule_class_from_offset($days_offset);
        return call_user_func([$schedule_class_name, 'identifier'], $days_offset);
    }

    /**
     * Helper that forces a raw offset to a FE-friendly value.
     *
     * @param int $raw_offset
     * @return int
     */
    public static function get_schedule_offset(int $raw_offset): int {
        return $raw_offset < 0 ? $raw_offset * -1 : $raw_offset;
    }

    /**
     * Proxy to the scheduled event calculate function
     *
     * @param int $event_timestamp
     * @param int $days_offset
     * @return int
     */
    public static function calculate_schedule_timestamp(int $event_timestamp, int $days_offset): int {
        $schedule_class_name = self::get_schedule_class_from_offset($days_offset);
        return call_user_func([$schedule_class_name, 'calculate_timestamp'], $event_timestamp, $days_offset);
    }

    /**
     * Based on the provided schedule type, convert the schedule_offset into a form
     * that can be stored in the database.
     *
     * @param string $schedule_type
     * @param int $schedule_offset
     * @return int
     */
    public static function convert_schedule_offset_for_storage(string $schedule_type, int $schedule_offset): int {
        // Based on the provided type, find the actual class for it.
        $class = self::get_schedule_class_from_type($schedule_type);
        return call_user_func([$class, 'default_value'], $schedule_offset);
    }
}