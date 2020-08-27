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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Tatsuhiro Kirihara <tatsuhiro.kirihara@totaralearning.com>
 * @package mod_perform
 */

namespace mod_perform\notification\brokers;

use mod_perform\models\activity\notification as notification_model;
use mod_perform\notification\broker;
use mod_perform\notification\dealer;
use mod_perform\notification\clock;

/**
 * instance_created handler
 */
class instance_created implements broker {
    public function get_default_triggers(): array {
        return [];
    }

    public function execute(dealer $dealer, notification_model $notification): void {
        // just post it
        $dealer->post();
    }
}
