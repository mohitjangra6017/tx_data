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
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\testing;

use core\testing\component_generator;

/**
 * @method static generator instance()
 */
class generator extends component_generator {
    /**
     * Set the configuration item for client_id.
     *
     * @param string $client_id
     * @return void
     */
    public function set_config_client_id(string $client_id): void {
        set_config('client_id', $client_id, 'contentmarketplace_linkedin');
    }

    /**
     * Set the configuration item for client_secret.
     *
     * @param string $client_secret
     * @return void
     */
    public function set_config_client_secret(string $client_secret): void {
        set_config('client_secret', $client_secret, 'contentmarketplace_linkedin');
    }

    /**
     * Set up the environment with either given client's id and secret or
     * the system will mock these two attributes itself.
     *
     * @param string|null $client_id
     * @param string|null $client_secret
     *
     * @return void
     */
    public function set_up_configuration(?string $client_id = null, ?string $client_secret = null): void {
        if (empty($client_id)) {
            $client_id = uniqid('clientid');
        }

        if (empty($client_secret)) {
            $client_secret = uniqid('clientsecret');
        }

        $this->set_config_client_id($client_id);
        $this->set_config_client_secret($client_secret);
    }
}