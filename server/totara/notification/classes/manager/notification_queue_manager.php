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
use core\json_editor\helper\document_helper;
use core\orm\query\builder;
use core_user;
use null_progress_trace;
use phpunit_util;
use progress_trace;
use stdClass;
use totara_notification\entity\notification_queue;
use totara_notification\entity\notification_preference as preference_entity;
use totara_notification\model\notification_preference;
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
        global $DB;
        if (!$DB->record_exists(preference_entity::TABLE, ['id' => $queue->notification_preference_id])) {
            $this->trace->output(
                "There is no notification preference record exist at id '{$queue->notification_preference_id}'"
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

        $preference = notification_preference::from_id($queue->notification_preference_id);
        $event_data = $queue->get_decoded_event_data();

        $resolver = helper::get_resolver_from_notifiable_event(
            $preference->get_event_class_name(),
            $queue->context_id,
            $event_data
        );

        $recipient = $preference->get_recipient();
        $recipient_ids = $resolver->get_recipient_ids($recipient);

        $engine = $resolver->get_placeholder_engine();

        $body_format = $preference->get_body_format();
        $body_text = $preference->get_body();

        if (FORMAT_JSON_EDITOR == $body_format && !document_helper::looks_like_json($body_text, true)) {
            // This is probably happening because of the language string is comming from the language pack
            // that it is purely a string. Which in this case we will help to convert it as a string into a json document.
            // Note that with this converting into json document, all the placeholder will be treated as text,
            // however even as a text, the the placeholder replacement can actually work just fine.
            $body_text = document_helper::create_json_string_document_from_text($body_text);
        }

        $subject_format = $preference->get_subject_format();
        $subject_text = $preference->get_subject();

        if (FORMAT_JSON_EDITOR == $subject_format && !document_helper::looks_like_json($subject_text, true)) {
            // This is probably happening because of the language string is comming from the language pack
            // that it is purely a string. Which in this case we will help to convert it as a string into a json document.
            // Note that with this converting into json document, all the placeholder will be treated as text,
            // however even as a text, the the placeholder replacement can actually work just fine.
            $subject_text = document_helper::create_json_string_document_from_text($subject_text);
        }

        // Constructing a default message that will be sent for multiple users.
        $default_message  = new stdClass();
        $default_message->notification = 1;
        $default_message->fullmessage = $engine->replace(format_text_email($body_text, $body_format));
        $default_message->fullmessagehtml = $engine->replace(format_text($body_text, $body_format));
        $default_message->subject = $engine->replace(content_to_text($subject_text, $subject_format));

        // Set message format to FORMAT_PLAIN as the fullmessage column is only storing processed plain
        // text instead of the raw content
        $default_message->fullmessageformat = FORMAT_PLAIN;

        // Static data - which can be tweaked later on.
        $default_message->contexturl = '';
        $default_message->contexturlname = '';

        // Note: we are hardcoded to no_reply_user for now, however, it should be up
        // to the resolver to decide who is the sender.
        $default_message->userfrom = core_user::get_noreply_user();
        $default_message->useridfrom = $default_message->userfrom->id;

        $message_processors = get_message_processors(true, (defined('PHPUNIT_TEST') && PHPUNIT_TEST));

        foreach ($recipient_ids as $target_user_id) {
            $message = clone $default_message;
            $message->userto = core_user::get_user($target_user_id);
            $message->useridto = $target_user_id;

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

            if (defined('PHPUNIT_TEST') && PHPUNIT_TEST && class_exists('phpunit_util')) {
                // For  unit tests purpose only.

                if (phpunit_util::is_redirecting_messages()) {
                    phpunit_util::message_sent($message);
                    return;
                }
            }

            $message_processors['popup']->object->send_message($message);

            if (isset($message_processors['email'])) {
                // This is for behat, as in behat enviroment, email is not enabled by default.
                $message_processors['email']->object->send_message($message);
            }
        }
    }
}