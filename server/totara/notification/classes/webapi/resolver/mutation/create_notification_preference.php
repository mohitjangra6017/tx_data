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
namespace totara_notification\webapi\resolver\mutation;

use coding_exception;
use context;
use core\webapi\execution_context;
use core\webapi\middleware\clean_content_format;
use core\webapi\middleware\clean_editor_content;
use core\webapi\middleware\require_login;
use core\webapi\mutation_resolver;
use core\webapi\resolver\has_middleware;
use totara_notification\builder\notification_preference_builder;
use totara_notification\entity\notification_preference as entity;
use totara_notification\local\helper;
use totara_notification\local\schedule_helper;
use totara_notification\model\notification_preference;

class create_notification_preference implements mutation_resolver, has_middleware {
    /**
     * @param array             $args
     * @param execution_context $ec
     * @return notification_preference
     */
    public static function resolve(array $args, execution_context $ec): notification_preference {
        global $DB;
        $context = context::instance_by_id($args['context_id']);

        // Note: TL-29488 will try to add capability check and advanced feature check to this resolver.

        if (CONTEXT_SYSTEM != $context->contextlevel && !$ec->has_relevant_context()) {
            $ec->set_relevant_context($context);
        }

        $event_name = ltrim($args['event_class_name'], '\\');
        if (!helper::is_valid_notifiable_event($event_name)) {
            throw new coding_exception(
                "The event class name is not a notifiable event"
            );
        }

        $builder = new notification_preference_builder(
            $event_name,
            $context->id
        );

        $title = $args['title'] ?? null;

        if (isset($args['ancestor_id'])) {
            if (CONTEXT_SYSTEM == $context->contextlevel) {
                // Note that this part is also done in the builder as well.
                throw new coding_exception(
                    "Cannot create a notification at context system with the ancestor's id set"
                );
            }

            // Fetch the notification name if it is a built in notification.
            $notification_class_name = $DB->get_field(
                entity::TABLE,
                'notification_class_name',
                ['id' => $args['ancestor_id']],
                MUST_EXIST
            );

            // Found the notification's name. Check that if we have a built in record at this very context or
            // not, and also for the specific event name.
            if (null !== $notification_class_name) {
                if (!empty($title)) {
                    // We do not allow any sort of overridden title for the built in notification.
                    // It should come from the built in notification class.
                    throw new coding_exception(
                        "Cannot overridden the title of any built in notification"
                    );
                }
            }

            // We are checking if the overriding had already been existing in the system or not.
            // that it may exist in this context already.
            $custom_existing = $DB->record_exists(
                entity::TABLE,
                [
                    'ancestor_id' => $args['ancestor_id'],
                    'context_id' => $context->id
                ]
            );

            if ($custom_existing) {
                throw new coding_exception(
                    "Cannot create another overridden notification ".
                    "preference at context '{$context->get_context_name()}'"
                );
            }


            $builder->set_ancestor_id($args['ancestor_id']);
            $builder->set_notification_class_name($notification_class_name);
        }

        $builder->set_title($args['title'] ?? null);
        $builder->set_body($args['body'] ?? null);
        $builder->set_subject($args['subject'] ?? null);
        $builder->set_body_format($args['body_format'] ?? null);
        $builder->set_subject_format($args['subject_format'] ?? null);

        // Schedule works in a pair, but writes to a single value.
        $schedule_type = $args['schedule_type'] ?? null;
        $schedule_offset = $args['schedule_offset'] ?? null;
        $raw_schedule_offset = null;
        if (null !== $schedule_type && null !== $schedule_offset) {
            $raw_schedule_offset = schedule_helper::convert_schedule_offset_for_storage(
                $schedule_type,
                $schedule_offset
            );
        }
        $builder->set_schedule_offset($raw_schedule_offset);

        return $builder->save();
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
            new clean_editor_content('subject', 'subject_format', false)
        ];
    }
}