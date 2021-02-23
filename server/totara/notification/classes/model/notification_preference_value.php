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
namespace totara_notification\model;

use coding_exception;
use lang_string;
use totara_notification\local\helper;
use totara_notification\notification\built_in_notification;

/**
 * A data holder class that is used to transfer data down
 * to graphql type resolver.
 */
class notification_preference_value {
    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $schedule_offset;

    /**
     * @var int
     */
    private $body_format;

    /**
     * @var int
     */
    private $subject_format;

    /**
     * notification_preference_value constructor.
     * @param string   $body
     * @param string   $subject
     * @param string   $title
     * @param int      $schedule_offset
     * @param int|null $body_format
     * @param int|null $subject_format
     */
    private function __construct(string $body, string $subject, string $title,
                                 int $schedule_offset, ?int $body_format = null,  ?int $subject_format = null) {
        $this->body = $body;
        $this->subject = $subject;
        $this->title = $title;
        $this->schedule_offset = $schedule_offset;
        $this->body_format = $body_format ?? FORMAT_MOODLE;
        $this->subject_format = $subject_format ?? FORMAT_JSON_EDITOR;
    }

    /**
     * @param string $built_in_class_name
     * @return notification_preference_value
     */
    public static function from_built_in_notification(string $built_in_class_name): notification_preference_value {
        if (!helper::is_valid_built_in_notification($built_in_class_name)) {
            throw new coding_exception("Invalid built-in notification class name '{$built_in_class_name}'");
        }

        /**
         * @see built_in_notification::get_default_body()
         * @see built_in_notification::get_default_subject()
         * @see built_in_notification::get_title()
         * @see built_in_notification::get_default_body_format()
         * @see built_in_notification::get_default_subject_format()
         * @see built_in_notification::get_default_schedule_offset()
         *
         * @var lang_string $body
         * @var lang_string $subject
         * @var string      $title
         * @var int         $body_format
         */
        $body = call_user_func([$built_in_class_name, 'get_default_body']);
        $subject = call_user_func([$built_in_class_name, 'get_default_subject']);
        $title = call_user_func([$built_in_class_name, 'get_title']);
        $body_format = call_user_func([$built_in_class_name, 'get_default_body_format']);
        $schedule_offset = call_user_func([$built_in_class_name, 'get_default_schedule_offset']);
        $subject_format = call_user_func([$built_in_class_name, 'get_default_subject_format']);

        return new static(
            $body,
            $subject,
            $title,
            $schedule_offset,
            $body_format,
            $subject_format
        );
    }

    /**
     * Please note that the $model that you are passing down to this function
     * is the parent model.
     *
     * @param notification_preference $model
     * @return notification_preference_value
     */
    public static function from_parent_notification_preference(notification_preference $model): notification_preference_value {
        return new static(
            $model->get_body(),
            $model->get_subject(),
            $model->get_title(),
            $model->get_schedule_offset(),
            $model->get_body_format(),
            $model->get_subject_format()
        );
    }

    /**
     * @return string
     */
    public function get_body(): string {
        return $this->body;
    }

    /**
     * @return string
     */
    public function get_title(): string {
        return $this->title;
    }

    /**
     * @return string
     */
    public function get_subject(): string {
        return $this->subject;
    }

    /**
     * @return int
     */
    public function get_body_format(): int {
        return $this->body_format;
    }

    /**
     * @return int
     */
    public function get_scheduled_offset(): int {
        return $this->schedule_offset;
    }

    /**
     * @return int
     */
    public function get_subject_format(): int {
        return $this->subject_format;
    }
}