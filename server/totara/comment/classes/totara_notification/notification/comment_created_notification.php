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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_notification
 */
namespace totara_comment\totara_notification\notification;

use lang_string;
use totara_comment\event\comment_created;
use totara_notification\notification\built_in_notification;
use totara_notification\schedule\schedule_on_event;

final class comment_created_notification extends built_in_notification {
    /**
     * @return string
     */
    public static function get_event_class_name(): string {
        return comment_created::class;
    }

    /**
     * @return string
     */
    public static function get_title(): string {
        return get_string('comment_created', 'totara_comment');
    }

    /**
     * @return string
     */
    public static function get_recipient_name(): string {
        return 'owner';
    }

    /**
     * @return lang_string
     */
    public static function get_default_body(): lang_string {
        return new lang_string('notification_comment_created_body', 'totara_comment');
    }

    /**
     * @return lang_string
     */
    public static function get_default_subject(): lang_string {
        return new lang_string('comment_created', 'totara_comment');
    }

    /**
     * @return int
     */
    public static function get_default_schedule_offset(): int {
        return schedule_on_event::default_value();
    }
}