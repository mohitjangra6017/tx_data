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
 * @package totara_comment
 */

namespace totara_comment\totara_notification\recipient;

use totara_comment\comment;
use totara_comment\resolver_factory;
use totara_notification\recipient\recipient;

/**
 * Class owner
 *
 * The recipient referred to in this class is the user who owns the component that
 * identifies as the immediate parent of the comment.
 *
 * Example, if a user comments on a discussion, this recipient will identify as the
 * user that owns the discussion. If a user comments on an article this recipient will
 * identify as the user that owns the article.
 *
 * @package totara_comment\totara_notification\recipient
 */
final class owner implements recipient {

    /**
     * @inheritDoc
     */
    public static function get_name(): string {
        return get_string('owner', 'totara_comment');
    }

    /**
     * @inheritDoc
     */
    public static function get_user_ids(array $data): array {
        $comment = comment::from_id($data['comment_id']);
        $resolver = resolver_factory::create_resolver($comment->get_component());
        $owner_id = $resolver->get_owner_id_from_instance(
            $comment->get_area(),
            $comment->get_instanceid()
        );

        return !empty($owner_id) ? [$owner_id] : [];
    }

}