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
namespace totara_notification\builder;

use coding_exception;
use context;
use totara_notification\model\notification_preference;
use totara_notification\entity\notification_preference as entity;

/**
 * Treat the builder like a placeholder instance that should be used per one
 * notification preference only.
 *
 * If you would want to modify (upgrade/create) another notification preference,
 * please bring up a new instance of the builder.
 */
class notification_preference_builder {
    /**
     * @var array
     */
    private $record_data;

    /**
     * notification_preference_builder constructor.
     * @param string $event_class_name
     * @param int    $context_id
     */
    public function __construct(string $event_class_name, int $context_id) {
        $this->record_data = [
            'event_class_name' => $event_class_name,
            'context_id' => $context_id,
        ];
    }

    /**
     * @param int $preference_id
     * @return notification_preference_builder
     */
    public static function from_exist(int $preference_id): notification_preference_builder {
        return static::from_exist_model(
            notification_preference::from_id($preference_id)
        );
    }

    /**
     * @param notification_preference $notification_preference
     * @return notification_preference_builder
     */
    public static function from_exist_model(notification_preference $notification_preference): notification_preference_builder {
        $builder = new static(
            $notification_preference->get_event_class_name(),
            $notification_preference->get_context_id()
        );

        $builder->record_data['id'] = $notification_preference->get_id();
        return $builder;
    }

    /**
     * By setting this value to NULL, you are more likely to reset the notification record to
     * fallback to the ancestor notification preference, if it has any.
     *
     * Otherwise error will be thrown, for those notification preference that does not have ancestor or parents.
     *
     * @param string|null $body
     * @return void
     */
    public function set_body(?string $body): void {
        $this->record_data['body'] = $body;
    }

    /**
     * @param int|null $body_format
     * @return void
     */
    public function set_body_format(?int $body_format): void {
        $this->record_data['body_format'] = $body_format;
    }

    /**
     * By setting this value to NULL, you are more likely to reset the notification record to
     * fallback to the ancestor notification preference, if it has any.
     *
     * Otherwise error will be thrown, for those notification preference that does not have ancestor or parents.
     *
     * @param string|null $subject
     * @return void
     */
    public function set_subject(?string $subject): void {
        $this->record_data['subject'] = $subject;
    }

    /**
     * By setting this value to NULL, you are more likely to reset the notification record to
     * fallback to the ancestor notification preference, if it has any.
     *
     * Otherwise error will be thrown, for those notification preference that does not have ancestor or parents.
     *
     * @param string|null $title
     * @return void
     */
    public function set_title(?string $title): void {
        $this->record_data['title'] = $title;
    }

    /**
     * By setting this value to NULL, you are more likely to reset the notification record to
     * fallback to the ancestor notification preference, if it has any.
     *
     * This must be the raw offset value (for example, a negative value for a before_event).
     *
     * @param int|null $offset
     * @return void
     */
    public function set_schedule_offset(?int $offset): void {
        $this->record_data['schedule_offset'] = $offset;
    }

    /**
     * @param int|null $ancestor_id
     * @return void
     */
    public function set_ancestor_id(?int $ancestor_id): void {
        if (isset($this->record_data['id'])) {
            debugging(
                "Do not set the ancestor's id of notification preference when updating a record",
                DEBUG_DEVELOPER
            );

            return;
        }

        $this->record_data['ancestor_id'] = $ancestor_id;
    }


    /**
     * @param string|null $notification_class_name
     * @return void
     */
    public function set_notification_class_name(?string $notification_class_name): void {
        $this->record_data['notification_class_name'] = $notification_class_name;
    }

    /**
     * Parameter should safe us from modifying data via references.
     * Note that this function is set out to be static, because we would not want this function to
     * interact with any instance's data of the class at all. Treat it like a helper function
     *
     * @param array $record_data
     * @return array
     */
    protected static function prepare_data_for_create_new(array $record_data): array {
        $context = context::instance_by_id($record_data['context_id']);

        if (isset($record_data['ancestor_id'])) {
            // If the ancestor's id is set, we should check whether this notification preference
            // is created within system context or not.
            if (CONTEXT_SYSTEM == $context->contextlevel) {
                throw new coding_exception(
                    "The ancestor's id should not be set when the context is in system"
                );
            }

            $ancestor = notification_preference::from_id($record_data['ancestor_id']);

            // Check if the context path of the ancestor is in the context path of this very notification
            // preference that we are trying to create.
            $ancestor_context_path = $ancestor->get_context()->path;
            $current_context_path = $context->path;

            // Todo: test me maybe ? break me !
            if (0 !== stripos($current_context_path, $ancestor_context_path)) {
                // If the current context path does not contain the ancestor context path at the
                // start of the string then we are overriding a notification preference that reference
                // ancestor at some path that does not go to this very path. DOOM BRINGER 😈!
                throw new coding_exception(
                    "The context path of ancestor does not appear in the context path of the overridden preference"
                );
            }

            if (empty($record_data['notification_class_name'])) {
                // Time to find out the notification's name based on the ancestor.
                $record_data['notification_class_name'] = $ancestor->get_notification_class_name();
            }

            if (isset($record_data['title'])) {
                throw new coding_exception(
                    "For overriding notification preference, the field 'title' must not be overridden."
                );
            }
        }

        return $record_data;
    }

    /**
     * @param array $record_data
     * @return array
     */
    protected static function prepare_data_for_update(array $record_data): array {
        if (isset($record_data['ancestor_id'])) {
            // For updating data, we do not allow to update the ancestor's id.
            unset($record_data['ancestor_id']);
        }

        if (isset($record_data['notification_class_name'])) {
            // For updating data, we do not allow to update the built in notification's class name.
            unset($record_data['notification_class_name']);
        }

        unset($record_data['id']);
        return $record_data;
    }

    /**
     * Either we are upgrading the existing record or create a new record.
     *
     * Note that this function will not do any sort of smart check
     * regard to the existence of the record. Please do the check before
     * this function is called.
     *
     * @return notification_preference
     */
    public function save(): notification_preference {
        $entity = new entity();
        if (!isset($this->record_data['id'])) {
            // Create a new record data.
            $record_data = self::prepare_data_for_create_new($this->record_data);

            if (!isset($record_data['notification_class_name']) && !isset($record_data['ancestor_id'])) {
                // When the notification preference is for the custom, meaning that when the notification_class_name
                // is not provided. Hence the fields below will be required by the business logic.
                $required_fields = ['body', 'body_format', 'subject', 'title', 'schedule_offset'];

                foreach ($required_fields as $required_field) {
                    if (!isset($record_data[$required_field]) || '' === $record_data[$required_field]) {
                        throw new coding_exception("The record data does not have required field '{$required_field}'");
                    }
                }
            }
        } else {
            $record_data = self::prepare_data_for_update($this->record_data);
            $entity->set_id_attribute($this->record_data['id']);

            // We need to instantiate the model to do several checks beforehand.
            $entity->refresh();
            $notification_preference = notification_preference::from_entity($entity);

            if ($notification_preference->is_custom_notification() && !$notification_preference->has_parent()) {
                $text_fields = ['body', 'subject', 'title'];

                foreach ($text_fields as $field) {
                    if (array_key_exists($field, $this->record_data) && empty($record_data[$field])) {
                        throw new coding_exception(
                            "Cannot reset the field '{$field}' for custom notification that does not have parent(s)"
                        );
                    }
                }

                if (array_key_exists('body_format', $this->record_data) && null === $this->record_data['body_format']) {
                    // Special treatment for 'body_format', because the value of the field 'body_format'
                    // can be set to zero, which it will make the validation go wrong easily.
                    throw new coding_exception(
                        "Cannot reset the field 'body_format' for custom " .
                        "notification that does not have parent(s)"
                    );
                }

                if (array_key_exists('schedule_offset', $this->record_data) && null === $this->record_data['schedule_offset']) {
                    // Like body_format, schedule_offset can be a valid 0 therefore we cannot use empty to check.
                    throw new coding_exception(
                        "Cannot reset the field 'schedule_offset' for custom " .
                        "notification that does not have parent(s)"
                    );
                }
            }
        }

        foreach ($record_data as $k => $v) {
            $entity->set_attribute($k, $v);
        }

        $entity->save();
        $entity->refresh();

        return notification_preference::from_entity($entity);
    }
}