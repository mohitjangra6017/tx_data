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
namespace totara_oauth2\testing;

use core\testing\component_generator;
use totara_oauth2\entity\access_token;
use totara_oauth2\entity\client_provider;
use totara_oauth2\grant_type;

class generator extends component_generator {
    /**
     * The array $parameters should have these following attributes:
     * + access_token: String
     * + expires: String
     * + scope: String|array|null
     *
     * @param string|null $client_id
     * @param array $parameters
     *
     * @return access_token
     */
    public function create_access_token(string $client_id = null, array $parameters = []): access_token {
        if (array_key_exists("client_id", $parameters)) {
            debugging(
                "Please do not set the client_id from the parameters, as it will not be used",
                DEBUG_DEVELOPER
            );
        }

        if (empty($client_id)) {
            $client_id = uniqid("client_");
        }

        $entity = new access_token();
        $entity->client_id = $client_id;
        $entity->access_token = $parameters["access_token"] ?? uniqid("token_");
        $entity->expires = $parameters["expires"] ?? time() + DAYSECS;

        if (array_key_exists("scope", $parameters)) {
            $scope = $parameters["scope"];
            if (is_array($scope)) {
                $scope = implode(" ", $scope);
            }

            $entity->scope = $scope;
        }

        $entity->save();
        $entity->refresh();

        return $entity;
    }

    /**
     * @param client_provider $client_provider
     * @param int|null $expires
     * @return access_token
     */
    public function create_access_token_from_client_provider(
        client_provider $client_provider,
        ?int $expires = null
    ): access_token {
        return $this->create_access_token(
            $client_provider->client_id,
            [
                "scope" => $client_provider->scope,
                "expires" => $expires
            ]
        );
    }

    /**
     * The array $parameters should have these following attributes:
     * + client_id: string
     * + client_secret: string
     * + name: string
     * + description: string|null
     * + description_format: int|null
     * + scope: string|array|null
     * + grant_types: string|array|null
     *
     * @param string|null $client_id
     * @param array       $parameters
     *
     * @return client_provider
     */
    public function create_client_provider(?string $client_id = null, array $parameters = []): client_provider {
        if (empty($client_id)) {
            $client_id = uniqid("client_");
        }

        // Default to client_credentials for grant type.
        if (!array_key_exists("grant_types", $parameters)) {
            $parameters["grant_types"] = grant_type::get_client_credentials();
        }

        $entity = new client_provider();
        $entity->client_id = $client_id;
        $entity->client_secret = $parameters["client_secret"] ?? uniqid("secret_");
        $entity->description = $parameters["description"] ?? null;
        $entity->description_format = $parameters["description_format"] ?? null;

        if (array_key_exists("scope", $parameters)) {
            $scope = $parameters["scope"];
            if (is_array($scope)) {
                $scope = implode(" ", $scope);
            }

            $entity->scope = $scope;
        }

        // Process on the grant types for the client provider.
        $grant_types = $parameters["grant_types"];
        if (is_array($grant_types)) {
            $grant_types = implode(" ", $grant_types);
        }

        $entity->grant_types = $grant_types;
        $entity->save();
        $entity->refresh();

        return $entity;
    }
}