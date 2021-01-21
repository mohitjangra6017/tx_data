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
 * @package totara_notification
 */

namespace totara_comment\totara_notification;

use totara_notification\notification\notification;

final class comment_created_notification extends notification {
    /**
     * @inheritDoc
     */
    public function get_subject(): ?string {
        return $this->subject ?? '';
    }

    /**
     * @inheritDoc
     */
    public function get_body(): ?string {
        return $this->body ?? '';
    }

    /**
     * @inheritDoc
     */
    public function set_subject(string $subject): void {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     */
    public function set_body(string $body): void {
        $this->body = $body;
    }

    /**
     * @inheritDoc
     */
    public function get_notifications_related_notifiable_event(): array {
        return [];
    }
}