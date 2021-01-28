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
namespace totara_notification\webapi\formatter;

use context;
use core\webapi\formatter\formatter;
use stdClass;
use totara_notification\local\helper;
use totara_notification\model\notification_preference as model;

class notification_preference_formatter extends formatter {
    /**
     * notification_preference_formatter constructor.
     * @param model   $notification_preference
     * @param context $context
     */
    public function __construct(model $notification_preference, context $context) {
        $record = new stdClass();
        $record->id = $notification_preference->get_id();
        $record->body = $notification_preference->get_body();
        $record->title = $notification_preference->get_title();
        $record->subject = $notification_preference->get_subject();
        $record->body_format = $notification_preference->get_body_format();
        $record->event_class_name = $notification_preference->get_event_class_name();
        $record->overridden_body = $notification_preference->is_overridden_body();
        $record->overridden_subject = $notification_preference->is_overridden_subject();
        $record->ancestor_id = $notification_preference->get_ancestor_id();
        $record->is_custom = $notification_preference->is_custom_notification();

        parent::__construct($record, $context);
    }

    /**
     * @param string $field
     * @return mixed|null
     */
    protected function get_field(string $field) {
        if ($field === 'event_name') {
            // Convert the event_name into an event_class_name so that
            // we can give back the value of event_class_name and it will
            // try to convert the event_class_name into a human readable event name.
            $field = 'event_class_name';
        }

        return parent::get_field($field);
    }

    /**
     * @param string $field
     * @return bool
     */
    protected function has_field(string $field): bool {
        if ('event_name' === $field) {
            // Yes we do have field 'event_name' for this formatter.
            return true;
        }

        return parent::has_field($field);
    }

    /**
     * @return array
     */
    protected function get_map(): array {
        return [
            'id' => null,
            'body' => null,
            'title' => null,
            'subject' => null,
            'body_format' => null,
            'event_name' => function (string $event_class_name): string {
                return helper::get_human_readable_event_name($event_class_name);
            },
            'overridden_body' => null,
            'overridden_subject' => null,
            'ancestor_id' => null,
            'event_class_name' => null,
            'is_custom' => null,
        ];
    }
}