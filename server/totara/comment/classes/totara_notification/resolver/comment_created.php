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

use coding_exception;
use totara_comment\comment;
use totara_comment\resolver_factory;
use totara_comment\totara_notification\notification\comment_created_notification;
use totara_notification\resolver\notifiable_event_resolver;

class comment_created extends notifiable_event_resolver {
    /**
     * @param string $recipient_name
     * @return int[]
     */
    public function get_recipient_ids(string $recipient_name): array {
        if (comment_created_notification::get_recipient_name() !== $recipient_name) {
            throw new coding_exception("Invalid recipient name");
        }

        $comment_id = $this->event_data['comment_id'];
        $comment = comment::from_id($comment_id);

        $resolver = resolver_factory::create_resolver($comment->get_component());
        $owner_id = $resolver->get_owner_id_from_instance(
            $comment->get_area(),
            $comment->get_instanceid()
        );

        if (!empty($owner_id)) {
            // Returning the owner's id - if there are any.
            return [$owner_id];
        }

        return [];
    }
}