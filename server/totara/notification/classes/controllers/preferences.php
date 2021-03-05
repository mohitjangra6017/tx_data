<?php
/*
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Matthias Bonk <matthias.bonk@totaralearning.com>
 * @package totara_notification
 */

namespace totara_notification\controllers;

use context;
use context_user;
use core\entity\user;
use moodle_url;
use totara_mvc\controller;
use totara_mvc\tui_view;

/*
 * This page lists a user's notification preferences.
 */
class preferences extends controller {

    /**
     * @inheritDoc
     */
    protected function setup_context(): context {
        return context_user::instance($this->get_user_id());
    }

    private function get_user_id(): int {
        return user::logged_in()->id;
    }

    /**
     * @return tui_view
     */
    public function action(): tui_view {
        $this->set_url(new moodle_url('/totara/notification/preferences.php'));

        $props = [
            'current-user-id' => $this->get_user_id(),
            'context-id' => $this->get_context()->id,
        ];

        return tui_view::create('totara_notification/pages/Preferences', $props)
            ->set_title(get_string('preferences_page_title', 'totara_notification'));
    }
}