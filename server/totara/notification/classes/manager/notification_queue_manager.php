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
 * @package totara_notifiaction
 */
namespace totara_notification\manager;

use core\entity\notification;
use core\json_editor\helper\document_helper;
use core\orm\query\builder;
use core_phpunit\internal_util;
use core_user;
use Exception;
use null_progress_trace;
use progress_trace;
use stdClass;
use core\orm\query\exceptions\record_not_found_exception;
use totara_core\extended_context;
use totara_notification\entity\notification_queue;
use totara_notification\loader\delivery_channel_loader;
use totara_notification\model\notification_preference;
use totara_notification\placeholder\template_engine\engine;
use totara_notification\resolver\notifiable_event_resolver;
use totara_notification\resolver\resolver_helper;

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

        /** @var notification_queue $notification_queue */
        foreach ($notification_queues as $notification_queue) {
            try {
                builder::get_db()->transaction(function () use ($notification_queue) {
                    $this->dispatch($notification_queue);
                    $notification_queue->delete();
                });
            } catch (Exception $exception) {
                $this->trace->output(
                    "Cannot send notification queue record with id '{$notification_queue->id}'"
                );
            }
        }

        $notification_queues->close();
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

        try {
            $preference = notification_preference::from_id($queue->notification_preference_id);
        } catch (record_not_found_exception $e) {
            // if there is no record to process then silently exit rather than fail.
            $this->trace->output(
                "The notification preference record with id '{$queue->notification_preference_id}' does not exist"
            );
            return;
        }

        // If the preference is currently disabled, do not dispatch anything
        if (!$preference->get_enabled()) {
            $this->trace->output(
                "The notification preference record with id '{$queue->notification_preference_id}' is disabled"
            );
            return;
        }

        $event_data = $queue->get_decoded_event_data();

        $resolver = resolver_helper::instantiate_resolver_from_class(
            $preference->get_resolver_class_name(),
            $event_data
        );

        $recipient = $preference->get_recipient();
        $recipient_ids = $resolver->get_recipient_ids($recipient);

        $engine = $resolver->get_placeholder_engine();

        // Load the message processors & only show those that have been enabled
        $message_processors = get_message_processors(true, (defined('PHPUNIT_TEST') && PHPUNIT_TEST));
        $message_processors = $this->filter_message_processors_by_delivery_channel($resolver, $message_processors);
        if (empty($message_processors)) {
            // If there are no message processors enabled, there's nothing to send
            return;
        }

        foreach ($recipient_ids as $target_user_id) {
            $this->dispatch_to_target($target_user_id, $preference, $engine, $message_processors);
        }
    }

    /**
     * Dispatch the message to one recipient.
     *
     * @param int $target_user_id
     * @param notification_preference $preference
     * @param engine $engine
     * @param array $message_processors
     */
    private function dispatch_to_target(
        int $target_user_id,
        notification_preference $preference,
        engine $engine,
        array $message_processors
    ): void {
        $user = core_user::get_user($target_user_id);
        cron_setup_user($user);

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

        $message = new stdClass();
        $message->notification = 1;
        $message->userto = $user;
        $message->useridto = $target_user_id;

        $message->fullmessage = $engine->render_for_user(
            format_text_email($body_text, $body_format),
            $target_user_id
        );

        $message->fullmessagehtml = $engine->render_for_user(
            format_text($body_text, $body_format),
            $target_user_id,
        );
        $message->subject = $engine->render_for_user(
            content_to_text($subject_text, $subject_format),
            $target_user_id
        );

        // Set message format to FORMAT_PLAIN as the fullmessage column is only storing processed plain
        // text instead of the raw content
        $message->fullmessageformat = FORMAT_PLAIN;

        // Static data - which can be tweaked later on.
        $message->contexturl = '';
        $message->contexturlname = '';

        // Note: we are hardcoded to no_reply_user for now, however, it should be up
        // to the resolver to decide who is the sender.
        $message->userfrom = core_user::get_noreply_user();
        $message->useridfrom = $message->userfrom->id;

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

        if (defined('PHPUNIT_TEST') && PHPUNIT_TEST && internal_util::is_redirecting_messages()) {
            // For unit test purpose only.
            internal_util::message_sent($message);
            return;
        }

        foreach ($message_processors as $processor) {
            $processor->object->send_message($message);
        }
    }


    /**
     * Load the message processors for the resolver, filtering by delivery channel.
     *
     * @param notifiable_event_resolver $resolver
     * @param array $message_processors
     * @return array
     */
    private function filter_message_processors_by_delivery_channel(
        notifiable_event_resolver $resolver,
        array $message_processors
    ): array {
        $extended_context = extended_context::make_system();
        $event_preference = $resolver->get_notifiable_event_preference($extended_context);
        // User-level overrides should be applied here
        $delivery_channels = $event_preference ? $event_preference->default_delivery_channels :
            delivery_channel_loader::get_for_event_resolver(get_class($resolver));

        // Drop any message processor that isn't marked as enabled.
        foreach ($delivery_channels as $delivery_channel) {
            if (isset($message_processors[$delivery_channel->component]) && !$delivery_channel->is_enabled) {
                unset($message_processors[$delivery_channel->component]);
            }
        }

        return $message_processors;
    }
}