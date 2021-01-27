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
 * @package totara_notifiaction
 */
namespace totara_notification\manager;

use coding_exception;
use core\entity\notification;
use core\orm\query\builder;
use core_user;
use lang_string;
use null_progress_trace;
use phpunit_util;
use progress_trace;
use stdClass;
use totara_notification\entity\notification_queue;
use totara_notification\local\helper;

class notification_queue_manager {
    /**
     * @var progress_trace
     */
    private $trace;

    /**
     * notification_queue_manager constructor.
     * @param progress_trace|null $trace
     */
    public function __construct(?progress_trace $trace = null) {
        $this->trace = $trace ?? new null_progress_trace();
    }

    /**
     * Process any notification queues that are due. If $current_time is set to either zero
     * or NULL then the function will process the current {@see time()} of the system.
     *
     * @param int|null $current_time
     * @return void
     */
    public function dispatch_queues(?int $current_time = null): void {
        $repository = notification_queue::repository();
        $notification_queues = $repository->get_due_notification_queues($current_time);

        try {
            // At the moment, we will rollback the whole process when one item
            // failure to process, howerver TL-29469 will be the ticket to help
            // us exploring a better way to handle the un-expected errors.
            $transaction = builder::get_db()->start_delegated_transaction();

            /** @var notification_queue $notification_queue */
            foreach ($notification_queues as $notification_queue) {
                if (!$this->is_dispatchable($notification_queue)) {
                    $this->trace->output(
                        "Cannot dispatch the queue at id '{$notification_queue->id}'"
                    );

                    continue;
                }

                $this->dispatch($notification_queue);
                $notification_queue->delete();
            }

            $transaction->allow_commit();
        } finally {
            $notification_queues->close();
        }
    }

    /**
     * @param notification_queue $queue
     * @return bool
     */
    private function is_dispatchable(notification_queue $queue): bool {
        $notification_name = $queue->notification_name;
        if (!class_exists($notification_name)) {
            $this->trace->output(
                "The built-in notification does not exist in the system '{$notification_name}'"
            );

            return false;
        }

        // Checking whether the event data is a valid json string.
        try {
            $queue->get_decoded_event_data();
        } catch (coding_exception $e) {
            $this->trace->output($e->getMessage());
            return false;
        }

        // All's well, ends well.
        return true;
    }

    /**
     * Note that this function will not start any transaction, please make sure
     * that the transaction has started before hand.
     *
     * Please do not delete the queue after dispatch.
     * By the time we got to this function, all the data and sort of validation
     * should had been happened beforehand, hence we can 100% rely on the availability
     * of the data that notification queue can provide us.
     *
     * @param notification_queue $queue
     * @return void
     */
    private function dispatch(notification_queue $queue): void {
        global $CFG;

        require_once("{$CFG->dirroot}/message/lib.php");
        $notification_name = $queue->notification_name;

        /**
         * We are invoking {@see built_in_notification::get_event_class_name()} to help us find out
         * the notifiable event name.
         * @var string $notifiable_event_name
         */
        $notifiable_event_name = call_user_func([$notification_name, 'get_event_class_name']);
        $event_data = $queue->get_decoded_event_data();

        $resolver = helper::get_resolver_from_notifiable_event(
            $notifiable_event_name,
            $queue->context_id,
            $event_data
        );

        /**
         * We are invoking {@see built_in_notification::get_recipient_name()} to help us
         * finding out the recipient name of specific event that we want to send to.
         * @var string $recipient_name
         */
        $recipient_name = call_user_func([$notification_name, 'get_recipient_name']);
        $recipient_ids = $resolver->get_recipient_ids($recipient_name);

        if (empty($recipient_ids)) {
            debugging(
                "No recipients were returned for notification '{$notification_name}'",
                DEBUG_DEVELOPER
            );

            return;
        }

        /**
         * We are invoking {@see built_in_notification::get_default_body()} and
         * {@see built_in_notification::get_default_subject()} to help
         * us finding out the notification's content that we are sending out to the recipient
         * @var lang_string $body_notification
         * @var lang_string $subject_notification
         */
        $body_notification = call_user_func([$notification_name, 'get_default_body']);
        $subject_notification = call_user_func([$notification_name, 'get_default_subject']);

        $processors = get_message_processors(true, (defined('PHPUNIT_TEST') && PHPUNIT_TEST));

        foreach ($recipient_ids as $target_user_id) {
            $message = new stdClass();

            // Note: we are hardcoded to no_reply_user for now, however, it should be up
            // to the resolver to decide who is the sender.
            $message->userfrom = core_user::get_noreply_user();
            $message->userto = core_user::get_user($target_user_id);

            $message->useridfrom = $message->userfrom->id;
            $message->useridto = $target_user_id;

            // Note: subject and fullmessage are hardcoded to notification for now, however it should
            // be up to the notification preferences, then fallback to the default built-in notification
            // if none provided from preferences, instead.
            $message->subject = $subject_notification->out();
            $message->fullmessage = $body_notification->out();

            // Note: we are hardcoded to FORMAT_MOODLE for now, however, it should be up
            // to the notification preferences.
            $message->fullmessagehtml = format_string($message->fullmessage, FORMAT_MOODLE);
            $message->fullmessageformat = FORMAT_MOODLE;

            // Static data - which can be tweaked later on.
            $message->notification = 1;
            $message->contexturl = '';
            $message->contexturlname = '';

            if (defined('PHPUNIT_TEST') && PHPUNIT_TEST && class_exists('phpunit_util')) {
                // For  unit tests purpose only.

                if (phpunit_util::is_redirecting_messages()) {
                    phpunit_util::message_sent($message);
                    return;
                }
            }

            // Save the notification first before sending out the message.
            // Note: TL-29518 will encapsulate these logics in API.
            $notification = new notification();
            $notification->subject = $message->subject;
            $notification->useridfrom = $message->userfrom->id;
            $notification->useridto = $message->userto->id;
            $notification->fullmessage = $message->fullmessage;
            $notification->fullmessagehtml = $message->fullmessagehtml;
            $notification->fullmessageformat = $message->fullmessageformat;
            $notification->smallmessage = $message->fullmessage;

            // Note: TL-29325 will convert these properties into proper notification's properties.
            $notification->component = 'totara_notification';
            $notification->eventtype = 'notification';

            $notification->save();
            $message->savedmessageid = $notification->id;

            $processors['popup']->object->send_message($message);
            $processors['email']->object->send_message($message);
        }
    }
}