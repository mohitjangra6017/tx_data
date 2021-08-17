<?php
/**
 * This file is part of Totara Core
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
 * @package totara_oauth2
 */
namespace totara_oauth2;

use OAuth2\GrantType\ClientCredentials;
use OAuth2\Server as oauth2_server;
use totara_oauth2\facade\request_interface;
use totara_oauth2\facade\response_interface;
use totara_oauth2\local\response;
use totara_oauth2\local\storage;
use totara_oauth2\local\request;

/**
 * A wrapper for OAuth2 server library.
 */
class server {
    /**
     * @var storage
     */
    private $storage;

    /**
     * @param storage $storage
     */
    private function __construct(storage $storage) {
        $this->storage = $storage;
    }

    /**
     * Instantiate a new OAuth2 server instance.
     *
     * @param int|null $time_now
     * @return server
     */
    public static function boot(?int $time_now = null): server {
        $storage = new storage($time_now);
        return new self($storage);
    }

    /**
     * @return oauth2_server
     */
    private function create_oauth2_server(): oauth2_server {
        $oauth2_server = new oauth2_server($this->storage);
        $oauth2_server->addGrantType(new ClientCredentials($this->storage));

        return $oauth2_server;
    }

    /**
     * @param request_interface|null  $request_interface
     * @param response_interface|null $response_interface
     *
     * @return response_interface
     */
    public function handle_token_request(
        ?request_interface $request_interface = null,
        ?response_interface $response_interface = null
    ): response_interface {
        $request_interface = $request_interface ?? request::create_from_global();
        $response_interface = $response_interface ?? new response();

        $oauth2_server = $this->create_oauth2_server();
        $oauth2_server->handleTokenRequest(
            $request_interface,
            $response_interface
        );

        return $response_interface;
    }

    /**
     * @param request_interface|null  $request_interface
     * @param response_interface|null $response_interface
     *
     * @return response_interface
     */
    public function verify_resource_request(
        ?request_interface $request_interface = null,
        ?response_interface $response_interface = null
    ): response_interface {
        $request_interface = $request_interface ?? request::create_from_global();
        $response_interface = $response_interface ?? new response();

        $this->is_request_verified($request_interface, $response_interface);
        return $response_interface;
    }

    /**
     * Verify the request from the client. Note that $response_interface does not have to be passed
     * into the function if we do not want to perform injection inversion. It is in place to allow us
     * getting the response from the oauth2 server.
     *
     * @param request_interface|null $request_interface
     * @param response_interface|null $response_interface
     *
     * @return bool
     */
    public function is_request_verified(
        ?request_interface $request_interface = null,
        ?response_interface $response_interface = null
    ): bool {
        $request_interface = $request_interface ?? request::create_from_global();
        $oauth2_server = $this->create_oauth2_server();

        $token = $oauth2_server->verifyResourceRequest($request_interface, $response_interface);
        return !empty($token);
    }
}