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
use totara_comment\totara_notification\recipient\comment_author;
use totara_comment\totara_notification\recipient\owner;
use totara_notification\recipient\recipient;
use totara_notification\resolver\notifiable_event_resolver;

class comment_soft_deleted extends notifiable_event_resolver {
    /**
     * @param string $recipient_class
     * @return int[]
     */
    public function get_recipient_ids(string $recipient_class): array {
        $valid_recipient_classes = [
            owner::class,
            comment_author::class,
        ];

        if (!in_array($recipient_class, $valid_recipient_classes)) {
            throw new coding_exception("Invalid recipient class");
        }

        /** @var recipient $recipient_class */
        return $recipient_class::get_user_ids($this->event_data);
    }
}