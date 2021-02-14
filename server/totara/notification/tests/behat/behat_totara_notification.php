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

/**
 * Behat steps to generate notification related data.
 */
class behat_totara_notification extends behat_base {
    /**
     * Goes to the notifications view page.
     *
     * @Given I navigate to system notifications page
     */
    public function i_navigate_to_system_notifications_page() {
        behat_hooks::set_step_readonly(false);

        // Go directly to URL, we are testing functionality of page, not how to get there.
        $url = new moodle_url("/totara/notification/notifications.php");
        $this->getSession()->visit($this->locate_path($url->out_as_local_url(false)));
        $this->wait_for_pending_js();
    }

}