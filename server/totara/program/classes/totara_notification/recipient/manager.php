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
 * @author Matthias Bonk <matthias.bonk@totaralearning.com>
 * @package totara_program
 */

namespace totara_program\totara_notification\recipient;

use totara_job\job_assignment;
use totara_notification\recipient\recipient;

/**
 * Class manager
 *
 * The recipient referred to in this class is the manager of a program's subject.
 *
 * @package totara_program\totara_notification\recipient
 */
class manager implements recipient {

    public static function get_name(): string {
        return get_string('notification_manager_recipient', 'totara_program');
    }

    public static function get_user_ids(array $data): array {
        return job_assignment::get_all_manager_userids($data['user_id']);
    }
}