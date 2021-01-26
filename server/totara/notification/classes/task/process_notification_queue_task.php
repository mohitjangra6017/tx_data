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
 * @author Alvin Smith <alvin.smith@totaralearning.com>
 * @package totara_notification
 */

namespace totara_notification\task;

use core\task\scheduled_task;
use totara_notification\entity\notification_queue;


class process_notification_queue_task extends scheduled_task {
    public function get_name() {
        return get_string('process_notification_queue_task', 'totara_notification');
    }
    /**
     * @return void
     */
    public function execute() {
        $repository = notification_queue::repository();
        $all_queues = $repository->get_lazy();

        /** @var notification_queue $queue */
        foreach ($all_queues as $queue) {
            $this->send($queue);
        }

        $all_queues->close();
    }

    public function send(notification_queue $queue): bool {
        try {
            $event = $queue->get_decoded_event_data();
        } catch (\coding_exception $e) {
            return false;
        }

        $message = new \stdClass();
        // TODO
        $message->component = get_class($queue);
        $message->name = $queue->notification_name;
        $message->useridfrom = $event->user_id;
        $message->useridto = $event->relateduserid;
        $message->userto = $event->relateduserid;
        $message->userfrom = $event->user_id;
        $message->subject = $event->subject;
        $message->fullmessage = $event->fullmessage;
        $message->smallmessage = $event->smallmessage;
        $message->fullmessagehtml = $event->fullmessagehtml;
        $message->notification = $event->notification;;
        $message->contexturl = '';
        $message->contexturlname = $event->contexturlname;

        global $CFG;
        require_once($CFG->dirroot . '/message/lib.php');

        $processors = get_message_processors();
        $processors['email']->send_message($message);
        $processors['popup']->send_message($message);

        return true;
    }

}