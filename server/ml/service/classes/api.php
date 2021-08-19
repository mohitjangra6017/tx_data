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
 * @author Cody Finegan <cody.finegan@totaralearning.com>
 * @package ml_service
 */

namespace ml_service;

use coding_exception;
use totara_core\http\clients\curl_client;
use totara_core\http\request;
use totara_core\http\response;

/**
 * API helper for calling the remote Totara machine learning service.
 *
 * @package ml_service
 */
class api {
    /**
     * @var curl_client
     */
    protected $client;

    /**
     * api constructor.
     *
     * @param curl_client|null $client
     */
    public function __construct(?curl_client $client = null) {
        $this->client = $client ?? new curl_client();
    }

    /**
     * @param curl_client|null $client
     * @return api
     */
    public static function make(?curl_client $client = null): api {
        return new self($client);
    }

    /**
     * Call the specific machine learning service endpoint
     *
     * @param string $endpoint
     * @return response
     */
    public function get(string $endpoint): response {
        $request = request::get($this->make_url($endpoint), $this->make_headers());
        return $this->client->execute($request);
    }

    /**
     * Return the full URL to the ML service
     *
     * @param string $endpoint
     * @return string
     */
    protected function make_url(string $endpoint): string {
        global $CFG;
        $service_url = $CFG->ml_service_url ? rtrim($CFG->ml_service_url, '/') : '';
        if (empty($service_url)) {
            throw new coding_exception('No ml_service_url was defined, cannot call the machine learning service.');
        }

        return $service_url . $endpoint;
    }

    /**
     * Attach any headers to the requests
     *
     * @return array
     */
    protected function make_headers(): array {
        $request_time = time();
        return [
            'X-Totara-Ml-Key' => $this->make_key($request_time),
            'X-Totara-Time' => $request_time,
        ];
    }

    /**
     * @param float $request_time
     * @return string
     */
    protected function make_key(float $request_time): string {
        global $CFG;
        if (empty($CFG->ml_service_key)) {
            throw new coding_exception('No ml_service_key was defined, cannot connect to the machine learning service.');
        }
        return hash('sha256', $request_time . $CFG->ml_service_key);
    }
}