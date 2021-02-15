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

use core\webapi\execution_context;
use core\webapi\middleware\require_login;
use core\webapi\mutation_resolver;
use core\webapi\resolver\has_middleware;

/**
 * Mutation to validate the notification preference input data from user.
 */
class validate_notification_preference_input implements mutation_resolver, has_middleware {
    /**
     * Please note that any content that cause the value of cleaned param to
     * be empty string then it is invalid value. Otherwise we can accept those,
     * because we can be sured that those malicious content will be stripped out
     * and leave us the legit content.
     *
     * @param array             $args
     * @param execution_context $ec
     * @return array
     */
    public static function resolve(array $args, execution_context $ec) {
        $title = $args['title'] ?? '';
        $body = $args['body'] ?? '';
        $subject = $args['subject'] ?? '';

        $result = [];

        if (clean_param($title, PARAM_TEXT) === '') {
            $result[] = [
                'field_name' => 'title',
                'error_message' => get_string('invalid_input', 'totara_notification'),
            ];
        }

        if (clean_param($body, PARAM_TEXT) === '') {
            $result[] = [
                'field_name' => 'body',
                'error_message' => get_string('invalid_input', 'totara_notification'),
            ];
        }

        if (clean_param($subject, PARAM_TEXT) === '') {
            $result[] = [
                'field_name' => 'subject',
                'error_message' => get_string('invalid_input', 'totara_notification'),
            ];
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function get_middleware(): array {
        return [
            new require_login(),
        ];
    }
}