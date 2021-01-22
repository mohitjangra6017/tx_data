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

use coding_exception;
use totara_comment\event\comment_created;
use totara_notification\notification\built_in_notification;

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
        throw new coding_exception("Todo - implement me");
    }

    /**
     * @return mixed|void
     */
    public static function get_schedule() {
        throw new coding_exception("Todo - implement me");
    }
}