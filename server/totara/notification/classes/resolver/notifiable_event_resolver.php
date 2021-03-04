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
namespace totara_notification\resolver;

use totara_notification\local\helper;
use totara_notification\notification\built_in_notification;
use totara_notification\placeholder\template_engine\engine;
use totara_notification\placeholder\template_engine\square_bracket\engine as square_bracket_engine;
use totara_notification\recipient\recipient;

/**
 * Given that the {@see built_in_notification} is the default configuration that used to define the message, delivery channels,
 * recipient and so on, then this class resolver will be used by the notifiable event to do all sort of business logic
 * calculation to help construct the message, finding out the actual list of users and the time to send the
 * notifications out.
 *
 * The children of this class must be matching with the class that implement interface notifiable_event.
 * For example, if your notifiable_event class is 'totara_core\event\course_created' then the resolver class
 * that you are introduce should be 'totara_core\totara_notification\resolver\course_created'. The destination of the
 * class itself should follow the same namespace as above.
 */
abstract class notifiable_event_resolver {
    /**
     * @var int
     */
    protected $context_id;

    /**
     * @var array
     */
    protected $event_data;

    /**
     * notifiable_event_resolver constructor.
     * Preventing any complicated construction.
     *
     * @param int $context_id
     * @param array $event_data
     */
    final public function __construct(int $context_id, array $event_data) {
        $this->context_id = $context_id;
        $this->event_data = $event_data;
    }

    /**
     * Returning the list of user's id of whom should the notification should be sent to.
     * Note that the current behavior is for prototyping only, the API will sure be changed,
     * hence no unit tests for this function.
     *
     * @param string $recipient_class
     * @return int[]
     */
    public function get_recipient_ids(string $recipient_class): array {
        if (!helper::is_valid_recipient_class($recipient_class)) {
            throw new coding_exception(
                "Invalid recipient class passed to the notifiable event resolver"
            );
        }

        /** @var recipient $recipient */
        return $recipient::get_user_ids($this->event_data);
    }

    /**
     * Get the placeholder engine instance. It can either be square bracket
     * engine or mustache engine. By default, we are using square bracket
     * engine, however the children can define which engine it is using.
     *
     * @return engine
     */
    public function get_placeholder_engine(): engine {
        $event_name = helper::get_notifiable_event_from_resolver(static::class);
        return square_bracket_engine::create($event_name, $this->event_data);
    }
}