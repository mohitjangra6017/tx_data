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

use coding_exception;
use contentmarketplace_linkedin\api\response\result;
use contentmarketplace_linkedin\api\v2\api;
use contentmarketplace_linkedin\api\v2\service\learning_asset\query\criteria;
use contentmarketplace_linkedin\api\v2\service\learning_asset\response\collection;
use contentmarketplace_linkedin\api\v2\service\learning_asset\service;
use contentmarketplace_linkedin\config;
use contentmarketplace_linkedin\oauth\oauth_2;
use core\testing\component_generator;
use totara_contentmarketplace\token\token;
use totara_core\http\clients\simple_mock_client;
use totara_core\http\response;
use totara_core\http\response_code;

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

    /**
     * @param token $token
     * @return void
     */
    public function set_token(token $token): void {
        config::save_access_token($token->get_value());
        config::save_access_token_expiry($token->get_expiry());
    }

    /**
     * Load a static JSON response from a file.
     *
     * @param string $json_filename
     * @return string
     */
    public function get_json_content_from_fixtures(string $json_filename): string {
        global $CFG;
        if (false === strpos($json_filename, '.json')) {
            $json_filename .= ".json";
        }

        $base_directory = "{$CFG->dirroot}/totara/contentmarketplace/contentmarketplaces/linkedin/tests/fixtures/json";
        $file = "{$base_directory}/{$json_filename}";

        if (!file_exists($file)) {
            throw new coding_exception("The file '{$file}' does not exist");
        }

        return file_get_contents($file);
    }

    /**
     * Load a static response from a file and wrap it in an appropriate mock response object.
     *
     * @param string $json_filename
     * @return result|collection
     */
    public function get_mock_result_from_fixtures(string $json_filename): result {
        $client = new simple_mock_client();
        $token = oauth_2::create_from_config()->get_current_token();

        if ($token === null || $token->is_expired()) {
            // Mock token response
            $client->mock_queue(
                new response(
                    json_encode([
                        'access_token' => 'token',
                        'expires_in' => HOURSECS,
                    ]),
                    response_code::OK,
                    [],
                    'application/json'
                )
            );
        }

        // Mock API response.
        $client->mock_queue(
            new response(
                $this->get_json_content_from_fixtures($json_filename),
                response_code::OK,
                [],
                'application/json'
            )
        );

        $api = api::create($client);
        $service = new service(new criteria());

        return $api->execute($service);
    }

}