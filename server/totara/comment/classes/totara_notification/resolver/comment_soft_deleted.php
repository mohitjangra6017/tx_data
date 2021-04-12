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
 * @author Johannes Cilliers <johannes.cilliers@totaralearning.com>
 * @package totara_comment
 */
namespace totara_comment\totara_notification\resolver;

use coding_exception;
use moodle_recordset;
use totara_comment\comment;
use totara_comment\resolver_factory;
use totara_comment\totara_notification\recipient\comment_author;
use totara_comment\totara_notification\recipient\owner;
use totara_core\extended_context;
use totara_notification\resolver\abstraction\scheduled_event_resolver;
use totara_notification\resolver\notifiable_event_resolver;
use totara_notification\schedule\schedule_after_event;
use totara_notification\schedule\schedule_on_event;

class comment_soft_deleted extends notifiable_event_resolver implements scheduled_event_resolver {
    /**
     * @return int
     */
    public function get_fixed_event_time(): int {
        $comment_id = $this->event_data['comment_id'];
        $comment = comment::from_id($comment_id);

        $time_deleted = $comment->get_time_soft_deleted();
        if (empty($time_deleted)) {
            throw new coding_exception("Cannot resolve the event time of a soft deleted comment");
        }

        return $time_deleted;
    }

    /**
     * @inheritDoc
     */
    public static function get_notification_title(): string {
        return get_string('notification_comment_soft_deleted', 'totara_comment');
    }

    /**
     * @inheritDoc
     */
    public static function get_notification_available_recipients(): array {
        return [
            comment_author::class,
            owner::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function get_notification_available_schedules(): array {
        return [
            schedule_on_event::class,
            schedule_after_event::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function get_notification_default_delivery_channels(): array {
        return ['email', 'popup'];
    }

    /**
     * @inheritDoc
     */
    public static function get_notification_available_placeholder_options(): array {
        return [];
    }

    /**
     * @param int $min_time
     * @param int $max_time
     *
     * @return moodle_recordset
     */
    public static function get_scheduled_events(int $min_time, int $max_time): moodle_recordset {
        global $DB;

        $sql = '
            SELECT c.id AS comment_id FROM "ttr_totara_comment" c
            WHERE c.timedeleted IS NOT NULL AND c.timedeleted >= :min_time 
            AND c.timedeleted < :max_time
        ';

        return $DB->get_recordset_sql($sql, ['min_time' => $min_time, 'max_time' => $max_time]);
    }

    public function get_extended_context(): extended_context {
        $comment_id = $this->event_data['comment_id'];
        $comment = comment::from_id($comment_id);

        $resolver = resolver_factory::create_resolver($comment->get_component());
        $context_id = $resolver->get_context_id($comment->get_instanceid(), $comment->get_area());

        return extended_context::make_with_id($context_id);
    }
}