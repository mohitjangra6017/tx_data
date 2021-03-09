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
 * @package totara_comment
 */
namespace totara_comment\totara_notification\resolver;

use context;
use core_user\totara_notification\placeholder\user;
use lang_string;
use stdClass;
use totara_comment\comment;
use totara_comment\resolver_factory;
use totara_comment\totara_notification\recipient\comment_author;
use totara_comment\totara_notification\recipient\owner;
use totara_core\extended_context;
use totara_notification\model\notification_event_data;
use totara_notification\placeholder\placeholder_option;
use totara_notification\resolver\abstraction\scheduled_event_resolver;
use totara_notification\resolver\notifiable_event_resolver;
use totara_notification\schedule\schedule_after_event;
use totara_notification\schedule\schedule_on_event;
use totara_comment\event\comment_created as comment_created_event;

class comment_created extends notifiable_event_resolver implements scheduled_event_resolver {
    /**
     * Returns the comment's created time.
     * @return int|null
     */
    public function get_fixed_event_time(): ?int {
        $comment_id = $this->event_data['comment_id'];
        $comment = comment::from_id($comment_id);

        return $comment->get_timecreated();
    }

    /**
     * @return string
     */
    public static function get_notification_title(): string {
        return get_string('notification_comment_created', 'totara_comment');
    }

    /**
     * @return array
     */
    public static function get_notification_available_recipients(): array {
        return [
            comment_author::class,
            owner::class,
        ];
    }

    /**
     * @return array
     */
    public static function get_notification_available_schedules(): array {
        return [
            schedule_on_event::class,
            schedule_after_event::class,
        ];
    }

    /**
     * @return array
     */
    public static function get_notification_default_delivery_channels(): array {
        return [];
    }

    /**
     * @return placeholder_option[]
     */
    public static function get_notification_available_placeholder_options(): array {
        return [
            placeholder_option::create(
                'item_owner',
                user::class,
                new lang_string('item_owner_placeholder_group', 'totara_comment'),
                function (array $event_data): user {
                    $comment = comment::from_id($event_data['comment_id']);
                    $resolver = resolver_factory::create_resolver($comment->get_component());

                    $owner_id = $resolver->get_owner_id_from_instance(
                        $comment->get_area(),
                        $comment->get_instanceid()
                    );

                    return user::from_id($owner_id);
                }
            ),

            placeholder_option::create(
                'commenter',
                user::class,
                new lang_string('commenter_placeholder_group', 'totara_comment'),
                function (array $event_data): user {
                    $comment = comment::from_id($event_data['comment_id']);
                    return user::from_id($comment->get_userid());
                }
            ),
        ];
    }

    /**
     * @param int $min_time
     * @param int $max_time
     * @return notification_event_data[]
     */
    public static function get_scheduled_events(int $min_time, int $max_time): array {
        global $DB;
        $sql = '
            SELECT * FROM "ttr_totara_comment" c WHERE
            c.timecreated >= :min_time AND c.timecreated < :max_time
            AND c.timedeleted IS NULL
        ';

        $records = $DB->get_records_sql($sql, ['min_time' => $min_time, 'max_time' => $max_time]);
        return array_map(
            function (stdClass $record): notification_event_data {
                $comment = comment::from_record($record);

                $comment_resolver = resolver_factory::create_resolver($comment->get_component());
                $context_id = $comment_resolver->get_context_id($comment->get_instanceid(), $comment->get_area());

                // Construct the event so that we can get the right event_data to pass down to the
                // scheduled event processor.
                $event = comment_created_event::from_comment($comment, context::instance_by_id($context_id));

                return new notification_event_data(
                    extended_context::make_with_id($context_id),
                    $event->get_notification_event_data()
                );
            },
            $records
        );
    }
}