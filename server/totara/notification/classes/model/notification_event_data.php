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

use totara_core\extended_context;

/**
 * Just a simple data transfer object.
 * No much of logic in this class.
 */
class notification_event_data {
    /**
     * Note that this can be converted into extended context object.
     * @var extended_context
     */
    private $extended_context;

    /**
     * @var array
     */
    private $event_data;

    /**
     * event constructor.
     * @param extended_context $extended_context
     * @param array            $event_data
     */
    public function __construct(extended_context $extended_context, array $event_data) {
        $this->extended_context = $extended_context;
        $this->event_data = $event_data;
    }

    /**
     * @return extended_context
     */
    public function get_extended_context(): extended_context {
        return $this->extended_context;
    }

    /**
     * @return array
     */
    public function get_event_data(): array {
        return $this->event_data;
    }
}