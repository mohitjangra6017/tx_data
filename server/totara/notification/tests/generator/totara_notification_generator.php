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

use totara_notification\event\notifiable_event;
use totara_notification\factory\built_in_notification_factory;
use totara_notification\notification\built_in_notification;
use totara_notification\factory\notifiable_event_factory;
use totara_notification\local\helper;
use totara_notification\task\process_notification_queue_task;

class totara_notification_generator extends component_generator_base {
    /**
     * @return string
     */
    private static function plugin_location(): string {
        global $CFG;
        return "{$CFG->dirroot}/totara/notification";
    }

    /**
     * @return string
     */
    private static function fixtures_location(): string {
        $location = static::plugin_location();
        return "{$location}/tests/fixtures";
    }

    /**
     * @param string      $component
     * @param string|null $notification_class_name
     *
     * @return void
     */
    public function add_mock_built_in_notification_for_component(?string $notification_class_name = null,
                                                                 string $component = 'totara_notification'): void {
        if (empty($notification_class_name)) {
            $this->include_mock_built_in_notification();
            $notification_class_name = totara_notification_mock_built_in_notification::class;
        }

        $reflection_class = new ReflectionClass(built_in_notification_factory::class);

        /** @see  built_in_notification_factory::get_map() */
        $method = $reflection_class->getMethod('get_map');
        $method->setAccessible(true);

        // We will have to get map from the current private method to make sure that our map is
        // initialized nicely.
        $map = $method->invoke(null);
        $method->setAccessible(false);

        if (!isset($map[$component])) {
            $map[$component] = [];
        }

        if (!class_exists($notification_class_name)) {
            throw new coding_exception("The added notification class does not exist in the system");
        }

        if (!is_subclass_of($notification_class_name, built_in_notification::class)) {
            throw new coding_exception(
                "Only able to add a child of " . built_in_notification::class
            );
        }
        $map[$component][] = $notification_class_name;

        /** @see built_in_notification_factory::$built_in_notification_classes */
        $property = $reflection_class->getProperty('built_in_notification_classes');
        $property->setAccessible(true);
        $property->setValue($map);

        $property->setAccessible(false);
    }

    /**
     * @param string|null $event_name
     * @param string $component
     *
     * @return void
     */
    public function add_mock_notifiable_event_for_component(?string $event_name = null,
                                                            string $component = 'totara_notification'): void {
        if (empty($event_name)) {
            $this->include_mock_notifiable_event();
            $event_name = totara_notification_mock_notifiable_event::class;
        }

        $reflection_class = new ReflectionClass(notifiable_event_factory::class);

        /** @see notifiable_event_factory::get_map() */
        $method = $reflection_class->getMethod('get_map');
        $method->setAccessible(true);

        $map = $method->invoke(null);
        $method->setAccessible(false);

        if (!helper::is_valid_notifiable_event($event_name)) {
            throw new coding_exception(
                "Expecting the event class to implement interface " . notifiable_event::class
            );
        }

        if (!isset($map[$component])) {
            $map[$component] = [];
        }

        $map[$component][] = $event_name;

        /** @see notifiable_event_factory::$event_classes */
        $property = $reflection_class->getProperty('event_classes');
        $property->setAccessible(true);

        $property->setValue($map);
        $property->setAccessible(false);
    }

    /**
     * @param string $you_are_saying
     * @return lang_string
     */
    public function give_my_mock_lang_string(string $you_are_saying): lang_string {
        if (!class_exists('totara_notification_mock_lang_string')) {
            $fixture_director = self::fixtures_location();
            require_once("{$fixture_director}/totara_notification_mock_lang_string.php");
        }

        return new totara_notification_mock_lang_string($you_are_saying);
    }

    /**
     * @return void
     */
    public function include_mock_notifiable_event(): void {
        $fixture_directory = self::fixtures_location();
        require_once("{$fixture_directory}/totara_notification_mock_notifiable_event.php");
    }

    /**
     * @return totara_notification_test_progress_trace
     */
    public function get_test_progress_trace(): totara_notification_test_progress_trace {
        if (!class_exists('totara_notification_test_progress_trace')) {
            $fixture_path = self::fixtures_location();
            require_once("{$fixture_path}/totara_notification_test_progress_trace.php");
        }

        return new totara_notification_test_progress_trace();
    }

    /**
     * @return void
     */
    public function include_mock_built_in_notification(): void {
        $fixture_directory = self::fixtures_location();
        require_once("{$fixture_directory}/totara_notification_mock_built_in_notification.php");
    }

    /**
     * @return void
     */
    public function include_mock_notifiable_event_resolver(): void {
        $fixture_directory = self::fixtures_location();
        require_once("{$fixture_directory}/totara_notification_mock_notifiable_event_resolver.php");
    }

    /**
     * @param lang_string $body
     * @return void
     */
    public function add_body_to_mock_built_in_notification(lang_string $body): void {
        $this->include_mock_built_in_notification();
        totara_notification_mock_built_in_notification::set_default_body($body);
    }

    /**
     * @param string $body
     * @return void
     */
    public function add_string_body_to_mock_built_in_notification(string $body): void {
        $lang_string = $this->give_my_mock_lang_string($body);
        $this->add_body_to_mock_built_in_notification($lang_string);
    }

    /**
     * @param lang_string $subject
     * @return void
     */
    public function add_subject_to_mock_built_in_notification(lang_string $subject): void {
        $this->include_mock_built_in_notification();
        totara_notification_mock_built_in_notification::set_default_subject($subject);
    }

    /**
     * @param string $subject
     * @return void
     */
    public function add_string_subject_to_mock_built_in_notification(string $subject): void {
        $lang_string = $this->give_my_mock_lang_string($subject);
        $this->add_subject_to_mock_built_in_notification($lang_string);
    }

    /**
     * @param array $recipient_ids
     * @return void
     */
    public function add_mock_recipient_ids_to_resolver(array $recipient_ids): void {
        $callback = function () use ($recipient_ids) {
            return $recipient_ids;
        };

        $this->include_mock_notifiable_event_resolver();
        totara_notification_mock_notifiable_event_resolver::set_recipient_ids_resolver($callback);
    }

    /**
     * @param process_notification_queue_task $task
     * @param int $due_time
     *
     * @return void
     */
    public function set_due_time_of_process_notification_task(process_notification_queue_task $task,
                                                              int $due_time): void {
        $reflection_class = new ReflectionClass($task);

        /** @see process_notification_queue_task::$due_time */
        $property = $reflection_class->getProperty('due_time');
        $property->setAccessible(true);

        $property->setValue($task, $due_time);
        $property->setAccessible(false);
    }
}