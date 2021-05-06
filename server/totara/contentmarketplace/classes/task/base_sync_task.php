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
 * @package totara_contentmarketplace
 */
namespace totara_contentmarketplace\task;

use core\task\scheduled_task;
use totara_core\http\client;
use totara_core\http\clients\curl_client;

abstract class base_sync_task extends scheduled_task {
    /**
     * @var client
     */
    protected $client;

    /**
     * base_sync_task constructor.
     */
    public function __construct() {
        // Default to curl client, but it can be mock for testing purpose.
        $this->client = new curl_client();
    }

    /**
     * @param client $client
     * @return void
     */
    public function set_client(client $client): void {
        $this->client = $client;
    }
}