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

namespace totara_notification\testing;

use coding_exception;
use context_system;
use core\json_editor\helper\document_helper;
use core\testing\component_generator;
use lang_string;
use ReflectionClass;
use totara_notification\builder\notification_preference_builder;
use totara_notification\entity\notification_preference as entity;
use totara_notification\event\notifiable_event;
use totara_notification\factory\built_in_notification_factory;
use totara_notification\factory\notifiable_event_factory;
use totara_notification\local\helper;
use totara_notification\model\notification_preference;
use totara_notification\notification\built_in_notification;
use totara_notification\task\process_notification_queue_task;
use totara_notification_mock_built_in_notification;
use totara_notification_mock_lang_string;
use totara_notification_mock_notifiable_event;
use totara_notification_mock_notifiable_event_resolver;
use totara_notification_test_progress_trace;

final class generator extends component_generator {
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
        $location = self::plugin_location();
        return "{$location}/tests/fixtures";
    }

    /**
     * @param string      $component
     * @param string|null $notification_class_name
     *
     * @return notification_preference
     */
    public function add_mock_built_in_notification_for_component(
        ?string $notification_class_name = null,
        string $component = 'totara_notification'
    ): notification_preference {
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

        if (!helper::is_valid_built_in_notification($notification_class_name)) {
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

        /**
         * @see built_in_notification::get_event_class_name()
         * @var string $event_name
         */
        $event_name = call_user_func([$notification_class_name, 'get_event_class_name']);

        return $this->create_notification_preference(
            $event_name,
            context_system::instance()->id,
            ['notification_class_name' => $notification_class_name]
        );
    }

    /**
     * The array $data should contain the keys as follow:
     * + notification_class_name: String
     * + ancestor_id: Int
     * + body: String
     * + body_format: Int
     * + subject: String
     * + subject_format: Int
     * + recipient: String
     *
     * @param array    $data
     * @param int|null $context_id
     * @param string   $event_name
     *
     * @return notification_preference
     */
    public function create_notification_preference(string $event_name, ?int $context_id = null,
                                                   array $data = []): notification_preference {
        $context_id = $context_id ?? context_system::instance()->id;
        $builder = new notification_preference_builder($event_name, $context_id);

        if (!empty($data['notification_name'])) {
            // Temporary to fix any tests that still preference to the old code.
            throw new coding_exception("This does not work like that");
        }

        $notification_class_name = $data['notification_class_name'] ?? null;
        if (!empty($notification_class_name)) {
            // Check that if the notification preference does exist.
            $repository = entity::repository();
            $entity = $repository->find_built_in(
                $notification_class_name,
                $context_id
            );

            if (null !== $entity) {
                // The record is already existing in the system, we will just return the current record.
                // If developer want to tweak/update the values of certain fields, they can use the builder's API to do so.
                return notification_preference::from_entity($entity);
            }
        }

        if (empty($data['notification_class_name']) && empty($data['ancestor_id'])) {
            // We are only giving the default value if the notification_class_name or the ancestor is not
            // appearing in the $data parameter.
            $data['body_format'] = $data['body_format'] ?? FORMAT_JSON_EDITOR;
            $data['subject_format'] = $data['subject_format'] ?? FORMAT_JSON_EDITOR;

            $data['body'] = $data['body'] ?? 'This is a body';
            $data['title'] = $data['title'] ?? 'This is title';
            $data['subject'] = $data['subject'] ?? 'This is a subject';
            $data['schedule_offset'] = $data['schedule_offset'] ?? 0;
        }

        if (isset($data['body']) && isset($data['body_format'])) {
            // Note: we can do a one statement if here with lots of "&&" condition. However it would leave us to
            // the point where the condition is a nightmare condition. Therefore i had made it simpler with nested
            // like this and debugging would just be easier - you are welcome :)
            $body_format = $data['body_format'];

            if (FORMAT_JSON_EDITOR == $body_format && !document_helper::looks_like_json($data['body'])) {
                $data['body'] = document_helper::create_json_string_document_from_text($data['body']);
            }
        }

        if (isset($data['subject']) && isset($data['subject_format'])) {
            // Note: we can do a one statement if here with lots of "&&" condition. However it would leave us to
            // the point where the condition is a nightmare condition. Therefore i had made it simpler with nested
            // like this and debugging would just be easier - you are welcome :)
            $subject_format = $data['subject_format'];

            if (FORMAT_JSON_EDITOR == $subject_format && !document_helper::looks_like_json($data['subject'])) {
                $data['subject'] = document_helper::create_json_string_document_from_text($data['subject']);
            }
        }

        $builder->set_notification_class_name($data['notification_class_name'] ?? null);
        $builder->set_ancestor_id($data['ancestor_id'] ?? null);
        $builder->set_body($data['body'] ?? null);
        $builder->set_body_format($data['body_format'] ?? null);
        $builder->set_subject($data['subject'] ?? null);
        $builder->set_subject_format($data['subject_format'] ?? null);
        $builder->set_title($data['title'] ?? null);
        $builder->set_schedule_offset($data['schedule_offset'] ?? null);
        $builder->set_recipient($data['recipient'] ?? null);

        return $builder->save();
    }

    /**
     * A helper function to create an overridden notification at lower context.
     * The parameter array $overridden_data should be similar to the one from
     * {@see generator::create_notification_preference()}.
     *
     * Note that we context's id of overridden must not but the same as the preference that
     * we are trying to override from.
     *
     * The attribute 'title' from $overridden_data will be ignored from this function.
     *
     * @param notification_preference $preference
     * @param int                     $context_id
     * @param array                   $overridden_data
     * @return notification_preference
     */
    public function create_overridden_notification_preference(notification_preference $preference, int $context_id,
                                                              array $overridden_data = []): notification_preference {
        $current_context_id = $preference->get_context_id();
        if ($current_context_id === $context_id) {
            throw new coding_exception("Cannot create an overridden notification preference at the same context level");
        }

        $record_data = [
            'notification_class_name' => $preference->get_notification_class_name(),
            'ancestor_id' => $preference->get_ancestor_id(),
            'body' => $overridden_data['body'] ?? null,
            'subject' => $overridden_data['subject'] ?? null,
            'body_format' => $overridden_data['body_format'] ?? null,
            'subject_format' => $overridden_data['subject_format'] ?? null,
            'recipient' => $overridden_data['recipient'] ?? null,
        ];

        if (!$preference->has_parent()) {
            // The preference that we are trying to override is sitting at top.
            $record_data['ancestor_id'] = $preference->get_id();
        }

        return $this->create_notification_preference(
            $preference->get_event_class_name(),
            $context_id,
            $record_data
        );
    }

    /**
     * @param string|null $event_name
     * @param string      $component
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
     * @return void
     */
    public function include_mock_single_placeholder(): void {
        $fixture_directory = self::fixtures_location();
        require_once("{$fixture_directory}/totara_notification_mock_single_placeholder.php");
    }

    /**
     * @return void
     */
    public function include_mock_invalid_placeholder(): void {
        $fixture_directory = self::fixtures_location();
        require_once("{$fixture_directory}/totara_notification_mock_invalid_placeholder.php");
    }

    /**
     * @return void
     */
    public function include_mock_collection_placeholder(): void {
        $fixture_directory = self::fixtures_location();
        require_once("{$fixture_directory}/totara_notification_mock_collection_placeholder.php");
    }

    /**
     * @return void
     */
    public function include_mock_recipient(): void {
        $fixture_directory = self::fixtures_location();
        require_once("{$fixture_directory}/totara_notification_mock_recipient.php");
    }

    /**
     * @return void
     */
    public function include_mock_owner(): void {
        $fixture_directory = self::fixtures_location();
        require_once("{$fixture_directory}/totara_notification_mock_owner.php");
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
     * Adding the list of recipient ids to the mock notifiable event resolver.
     *
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
     * @param int                             $due_time
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