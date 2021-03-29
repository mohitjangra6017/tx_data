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
namespace totara_notification\webapi\resolver\mutation;

use coding_exception;
use core\webapi\execution_context;
use core\webapi\middleware\clean_content_format;
use core\webapi\middleware\clean_editor_content;
use core\webapi\middleware\require_login;
use core\webapi\mutation_resolver;
use core\webapi\resolver\has_middleware;
use totara_notification\builder\notification_preference_builder;
use totara_notification\event\update_custom_notification_preference_event;
use totara_notification\event\update_overridden_notification_preference_event;
use totara_notification\local\helper;
use totara_notification\local\schedule_helper;
use totara_notification\model\notification_preference;
use totara_notification\webapi\middleware\validate_delivery_channel_components;

class update_notification_preference implements mutation_resolver, has_middleware {
    /**
     * @param array             $args
     * @param execution_context $ec
     * @return notification_preference
     */
    public static function resolve(array $args, execution_context $ec): notification_preference {
        global $USER;

        if (empty($args['id'])) {
            throw new coding_exception(get_string('error_preference_id_missing', 'totara_notification'));
        }

        $preference_id = $args['id'];
        $notification_preference = notification_preference::from_id($preference_id);
        $extended_context = $notification_preference->get_extended_context();
        $context = $extended_context->get_context();

        if (!notification_preference::can_manage($notification_preference->get_extended_context())) {
            throw new coding_exception(get_string('error_manage_notification', 'totara_notification'));
        }

        if (CONTEXT_SYSTEM != $context->contextlevel && !$ec->has_relevant_context()) {
            $ec->set_relevant_context($context);
        }

        $builder = notification_preference_builder::from_exist_model($notification_preference);
        $is_overridding = $notification_preference->is_an_overridden_record();

        // Records what fields had been overridding by the user actor in event log.
        $overridding_fields = [];

        if (array_key_exists('body', $args)) {
            // Treating empty string as null, so that our builder can reset the
            // value of $body.
            $body = ('' === $args['body']) ? null : $args['body'];
            $builder->set_body($body);
            if ($is_overridding && !empty($body)) {
                $overridding_fields[] = 'body';
            }
        }

        if (array_key_exists('body_format', $args)) {
            $builder->set_body_format($args['body_format']);
            if ($is_overridding) {
                $overridding_fields[] = 'body_format';
            }
        }

        if (array_key_exists('subject', $args)) {
            // Treating empty string as null, so that our builder can
            // reset the value of $subject.
            $subject = ('' === $args['subject']) ? null : $args['subject'];
            $builder->set_subject($subject);

            if ($is_overridding && !empty($subject)) {
                $overridding_fields[] = 'subject';
            }
        }

        if (array_key_exists('subject_format', $args)) {
            $builder->set_subject_format($args['subject_format']);
            if ($is_overridding) {
                $overridding_fields[] = 'subject_format';
            }
        }

        if (array_key_exists('title', $args)) {
            // Business logics check:
            // + If the notification preference is an overridden then it title should not be updated.
            // + If the notification preference is a custom one and does not override any other preference
            //   then the title should not be reset to null.
            // + If the notification preference is the built in at top level then the title should not be updated.
            if ($notification_preference->has_parent() || !$notification_preference->is_custom_notification()) {
                throw new coding_exception("The title of overridden notification preference cannot be updated");
            }

            // Treating empty string as null.
            $title = ('' === $args['title']) ? null : $args['title'];
            if (null === $title) {
                // At this point, we would know that the notification preference does not have any parent.
                // And also it is a custom notification. Hence we do not allow the title to be reset to null.
                throw new coding_exception(
                    "Cannot reset the title of notification of custom notification that does not have parent"
                );
            }

            $builder->set_title($title);
        }

        if (array_key_exists('schedule_type', $args) && array_key_exists('schedule_offset', $args)) {
            // We must translate the value based on the provided schedule type
            $offset = null;
            if (null !== $args['schedule_type']) {
                $offset = schedule_helper::convert_schedule_offset_for_storage(
                    $args['schedule_type'],
                    $args['schedule_offset']
                );
            }
            $builder->set_schedule_offset($offset);

            if ($is_overridding && !empty($offset)) {
                $overridding_fields[] = 'schedule_offset';
            }
        }

        if (array_key_exists('recipient', $args)) {
            $recipient = ('' === $args['recipient']) ? null : $args['recipient'];

            if (!is_null($recipient) && !helper::is_valid_recipient_class($recipient)) {
                throw new coding_exception("{$recipient} is not predefined recipient class");
            }

            $builder->set_recipient($recipient);
            if ($is_overridding && !empty($recipient)) {
                $overridding_fields[] = 'recipient';
            }
        }

        if (array_key_exists('locked_delivery_channels', $args)) {
            $builder->set_locked_delivery_channels($args['locked_delivery_channels']);
            if ($is_overridding && !empty($args['locked_delivery_channels'])) {
                $overridding_fields[] = 'locked_delivery_channels';
            }
        }

        if (array_key_exists('enabled', $args)) {
            $builder->set_enabled($args['enabled']);
            if ($is_overridding && null !== $args['enabled']) {
                $overridding_fields[] = 'enabled';
            }
        }

        $notification_preference = $builder->save();

        if ($is_overridding) {
            $event = update_overridden_notification_preference_event::from_preference(
                $notification_preference,
                $USER->id,
                $overridding_fields
            );
        } else {
            $event = update_custom_notification_preference_event::from_preference(
                $notification_preference,
                $USER->id
            );
        }

        $event->trigger();
        return $notification_preference;
    }

    /**
     * @return array
     */
    public static function get_middleware(): array {
        return [
            new require_login(),
            new clean_content_format('body_format'),
            new clean_content_format('subject_format'),
            new clean_editor_content('body', 'body_format', false),
            new clean_editor_content('subject', 'subject_format', false),
            new validate_delivery_channel_components('locked_delivery_channels', false),
        ];
    }
}