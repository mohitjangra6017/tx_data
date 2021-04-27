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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\api;

use contentmarketplace_linkedin\config;
use moodle_url;
use totara_contentmarketplace\token\token;
use totara_core\http\client;
use totara_core\http\request;

abstract class api {
    /**
     * @var string
     */
    public const ENDPOINT = 'https://api.linkedin.com';

    /**
     * @var client
     */
    protected $client;

    /**
     * @var token
     */
    protected $token;

    /**
     * api constructor.
     * @param client $client
     * @param token  $token
     */
    protected function __construct(client $client, token $token) {
        $this->client = $client;
        $this->token = $token;
    }

    /**
     * @return string
     */
    abstract public static function get_version(): string;

    /**
     * @return moodle_url
     */
    protected function get_endpoint_url(): moodle_url {
        $version = static::get_version();
        return new moodle_url(static::ENDPOINT . '/' . $version);
    }

    /**
     * @param moodle_url $url
     * @return request
     */
    protected function prepare_get_request_from_url(moodle_url $url): request {
        $headers = [
            'Authorization' => "Bearer {$this->token}",
            'referer' => config::PARTNER_IDENTIFIER,
        ];

        return request::get($url, $headers);
    }
}