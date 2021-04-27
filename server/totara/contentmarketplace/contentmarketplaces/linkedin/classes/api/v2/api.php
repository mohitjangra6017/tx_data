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
namespace contentmarketplace_linkedin\api\v2;

use coding_exception;
use contentmarketplace_linkedin\api\response\result;
use contentmarketplace_linkedin\api\v2\service\service;
use contentmarketplace_linkedin\oauth\oauth_2;
use totara_contentmarketplace\token\token;
use totara_core\http\client;
use totara_contentmarketplace\oauth\oauth_2_client;
use contentmarketplace_linkedin\api\api as base;

class api extends base {
    /**
     * @return string
     */
    public static function get_version(): string {
        return 'v2';
    }

    /**
     * @param client $client
     * @return api
     */
    public static function create(client $client, ?token $token = null): api {
        if (null !== $token && $token->is_expired()) {
            throw new coding_exception("The given token is expired");
        }

        if (null === $token) {
            $oauth = oauth_2::create_from_config();
            $oauth_2_client = new oauth_2_client($oauth, $client);

            $token = $oauth_2_client->request_token();
        }

        return new static($client, $token);
    }

    /**
     * @param service $service
     * @return result
     */
    public function execute(service $service): result {
        $endpoint_url = $this->get_endpoint_url();
        $endpoint_url = $service->apply_to_url($endpoint_url);

        $request = $this->prepare_get_request_from_url($endpoint_url);

        $response = $this->client->execute($request);
        $response->throw_if_error();

        return $service->wrap_response($response);
    }
}