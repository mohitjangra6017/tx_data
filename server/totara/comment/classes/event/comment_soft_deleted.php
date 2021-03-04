<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
namespace totara_comment\event;

use core\event\base;
use totara_comment\comment;
use totara_comment\entity\comment as entity;
use totara_comment\totara_notification\recipient\comment_author;
use totara_notification\event\notifiable_event;
use totara_notification\placeholder\placeholder_option;
use totara_notification\schedule\schedule_after_event;
use totara_notification\schedule\schedule_on_event;

/**
 * For comment soft deleted.
 */
final class comment_soft_deleted extends base implements notifiable_event {
    /**
     * @return void
     */
    protected function init(): void {
        $this->data['objecttable'] = entity::TABLE;
        $this->data['crud'] = 'd';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * @param comment $comment
     * @param int $context_id
     * @param int|null $actor_id
     *
     * @return comment_soft_deleted
     */
    public static function from_comment(comment $comment, int $context_id, ?int $actor_id = null): comment_soft_deleted {
        global $USER;

        if (!$comment->exists()) {
            throw new \coding_exception("Cannot create an event from a comment that had already been deleted");
        } else if ($comment->is_reply()) {
            throw new \coding_exception("Cannot create an event from a comment that is a reply");
        }

        if (null === $actor_id || 0 === $actor_id) {
            $actor_id = $USER->id;
        }

        $data = [
            'objectid' => $comment->get_id(),
            'userid' => $actor_id,
            'contextid' => $context_id,
            'relateduserid' => $comment->get_userid(),
            'other' => [
                'component' => $comment->get_component(),
                'area' => $comment->get_area(),
                'instanceid' => $comment->get_instanceid()
            ],
        ];

        $context = \context::instance_by_id($context_id);

        if (CONTEXT_COURSE == $context->contextlevel) {
            $data['courseid'] = $context->instanceid;
        }

        /** @var comment_soft_deleted $event */
        $event = static::create($data);
        return $event;
    }

    /**
     * @inheritDoc
     */
    public static function get_notification_title(): string {
        return get_string('notification_comment_soft_deleted', 'totara_comment');
    }

    /**
     * @inheritDoc
     */
    public static function get_notification_available_recipients(): array {
        return [comment_author::class];
    }

    /**
     * @inheritDoc
     */
    public static function get_notification_available_schedules(): array {
        return [
            schedule_on_event::class,
            schedule_after_event::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function get_notification_default_delivery_channels(): array {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function get_notification_event_data(): array {
        return [
            'comment_id' => $this->objectid
        ];
    }

    /**
     * @inheritDoc
     */
    public static function get_notification_available_placeholder_options(): array {
        return [];
    }
}