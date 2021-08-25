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

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\ResourceServer;
use Nyholm\Psr7\Stream;
use totara_oauth2\facade\request_interface;
use totara_oauth2\facade\response_interface;
use totara_oauth2\io\response;
use totara_oauth2\io\request;
use coding_exception;
use totara_oauth2\wrapper\league\client_repository;
use totara_oauth2\wrapper\league\scope_repository;
use totara_oauth2\wrapper\league\token_repository;

/**
 * A wrapper for OAuth2 server library.
 */
class server {
    /**
     * @var int
     */
    private $time_now;

    /**
     * @param int $time_now
     */
    private function __construct(int $time_now) {
        $this->time_now = $time_now;
    }

    /**
     * Instantiate a new OAuth2 server instance.
     *
     * @param int|null $time_now
     * @return server
     */
    public static function boot(?int $time_now = null): server {
        $time_now = $time_now ?? time();
        return new self($time_now);
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
        $private_key = config::get_private_key_path();
        $encryption_key = config::get_encryption_key();

        if (empty($private_key) || !file_exists($private_key)) {
            throw new coding_exception("The private key is invalid or not being set");
        } else if (empty($encryption_key)) {
            throw new coding_exception("The encryption key is not being set");
        }

        $client_repository = new client_repository();
        $token_repository = new token_repository($this->time_now);
        $scope_repository = new scope_repository();

        $auth_server = new AuthorizationServer(
            $client_repository,
            $token_repository,
            $scope_repository,
            new CryptKey($private_key, null, false),
            $encryption_key
        );

        $auth_server->enableGrantType(new ClientCredentialsGrant());

        $request_interface = $request_interface ?? request::create_from_global();
        $response_interface = $response_interface ?? new response();

        try {
            /** @var response_interface $response */
            $response = $auth_server->respondToAccessTokenRequest($request_interface, $response_interface);
            return $response;
        } catch (OAuthServerException $e) {
            return $response_interface->withBody(
                Stream::create(json_encode($e->getPayload()))
            );
        }
    }

    /**
     * Verify the request from the client, expecting the bearer token to be existing.
     * This function will only return the boolean result, whether the request is verified or not.
     *
     * It does not return the response object which identify what is wrong.
     *
     * @param request_interface|null $request_interface
     * @return bool
     */
    public function is_request_verified(?request_interface $request_interface = null): bool {
        $public_key = config::get_public_key();
        if (empty($public_key)) {
            throw new coding_exception("The public key is invalid or not being set");
        }

        $key = new CryptKey($public_key, null, false);
        $repository = new token_repository();
        $resource_server = new ResourceServer($repository, $key);

        $request_interface = $request_interface ?? request::create_from_global();
        try {
            $resource_server->validateAuthenticatedRequest($request_interface);
            return true;
        } catch (OAuthServerException $e) {
            // Cannot validate the request
            return false;
        }
    }
}