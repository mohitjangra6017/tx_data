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
namespace totara_oauth2\local;

use OAuth2\Storage\AccessTokenInterface;
use OAuth2\Storage\ClientCredentialsInterface;
use totara_oauth2\entity\access_token;
use totara_oauth2\entity\client_provider;
use totara_oauth2\server;

/**
 * This storage class is intended to be put within local scope, which the dependant plugin
 * should not direct access to this class, but rather than using {@see server}
 */
class storage implements AccessTokenInterface, ClientCredentialsInterface {
    /**
     * The current epoch time of the system.
     * @var int
     */
    private $time_now;

    /**
     * @param int|null $time_now
     */
    public function __construct(?int $time_now = null) {
        $this->time_now = $time_now ?? time();
    }

    /**
     * @param string $oauth_token
     * @return array|null
     */
    public function getAccessToken($oauth_token): ?array {
        $repository = access_token::repository();
        $entity = $repository->find_by_token($oauth_token, $this->time_now);

        if (null === $entity) {
            return null;
        }

        return [
            "expires" => $entity->expires,
            "client_id" => $entity->client_id,
            "scope" => $entity->scope
        ];
    }

    /**
     * @param string      $oauth_token
     * @param mixed       $client_id
     * @param mixed       $user_id
     * @param int         $expires
     * @param null|string $scope
     *
     * @return void
     */
    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null): void {
        $entity = new access_token();
        $entity->access_token = $oauth_token;
        $entity->client_id = $client_id;
        $entity->scope = $scope;
        $entity->expires = $expires;

        $entity->save();
    }

    /**
     * Checking if the client credentials is genuine or not.
     *
     * @param string      $client_id
     * @param null|string $client_secret
     *
     * @return bool
     */
    public function checkClientCredentials($client_id, $client_secret = null): bool {
        if (empty($client_secret)) {
            debugging(
                "Please provide a client_secret rather than leaving it empty",
                DEBUG_DEVELOPER
            );

            return false;
        }

        $repository = client_provider::repository();
        $client_provider = $repository->find_by_client_id($client_id);

        if (null === $client_provider) {
            return false;
        }

        return hash_equals($client_provider->client_secret, $client_secret);
    }

    /**
     * Public client isn't supported yet, hence this function will always return false.
     *
     * @param string $client_id
     * @return bool
     */
    public function isPublicClient($client_id): bool {
        // We do not support public client.
        return false;
    }

    /**
     * @param string $client_id
     * @return array|null
     */
    public function getClientDetails($client_id): ?array {
        $repository = client_provider::repository();
        $client_provider = $repository->find_by_client_id($client_id);

        if (null === $client_provider) {
            return null;
        }

        return [
            // At this point, we do not need redirect uri.
            "redirect_uri" => null,
            "client_id" => $client_provider->client_id,
            "grant_types" => $client_provider->get_grant_types_as_array(),
            "scope" => $client_provider->scope
        ];
    }

    /**
     * @param string $client_id
     * @return string|null
     */
    public function getClientScope($client_id): ?string {
        $repository = client_provider::repository();
        $client_provider = $repository->find_by_client_id($client_id);

        if (null === $client_provider) {
            return null;
        }

        return $client_provider->scope;
    }

    /**
     * @param string $client_id
     * @param string $grant_type
     *
     * @return bool
     */
    public function checkRestrictedGrantType($client_id, $grant_type): bool {
        $repository = client_provider::repository();
        $client_provider = $repository->find_by_client_id($client_id);

        if (null === $client_provider) {
            return false;
        }

        $grant_types = $client_provider->get_grant_types_as_array();
        return in_array($grant_type, $grant_types);
    }
}