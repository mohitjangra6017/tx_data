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

use totara_oauth2\facade\request_interface;
use coding_exception;

class mock_request implements request_interface {
    /**
     * Mock data for $_GET.
     *
     * @var array<string, mixed>
     */
    private $queryParameters;

    /**
     * Mock data for $_POST.
     *
     * @var array<string, mixed>
     */
    private $requestParameters;

    /**
     * Mock data for $_SERVER.
     *
     * @var array<string, mixed>
     */
    private $serverParameters;

    /**
     * Mock data for $_SERVER. However, this is more of
     * a custom data headers.
     *
     * @var array<string, mixed>
     */
    private $headerParameters;

    /**
     * @param array $query
     * @param array $request
     * @param array $server
     * @param array $headers
     */
    public function __construct(
        array $query = [],
        array $request = [],
        array $server = [],
        array $headers = []
    ) {
        $this->queryParameters = $query;
        $this->requestParameters = $request;
        $this->serverParameters = [];
        $this->headerParameters = [];

        // We need to make sure that all the keys in server parameters and
        // header parameters are all upper case.
        foreach ($server as $k => $v) {
            $this->serverParameters[strtoupper($k)] = $v;
        }

        foreach ($headers as $k => $v) {
            $this->headerParameters[strtoupper($k)] = $v;
        }
    }

    /**
     * Mock the POST request.
     *
     * @param array $query
     * @param array $request
     * @param array $server
     * @param array $headers
     *
     * @return mock_request
     */
    public static function mock_post(
        array $query = [],
        array $request = [],
        array $server = [],
        array $headers = []
    ): mock_request {
        if (isset($server["REQUEST_METHOD"]) || isset($server["request_method"])) {
            // Please do not set tthe REQUEST_METHOD for the $server.
            throw new coding_exception(
                "The argument \"request_method\" is being set for server parameter"
            );
        }

        $server["REQUEST_METHOD"] = "POST";
        return new self($query, $request, $server, $headers);
    }

    /**
     * @param string $name
     * @param null|mixed $default
     *
     * @return mixed|null
     */
    public function query($name, $default = null) {
        if (!array_key_exists($name, $this->queryParameters)) {
            return $default;
        }

        return $this->queryParameters[$name];
    }

    /**
     * @param string $name
     * @param null|mixed $default
     *
     * @return mixed|null
     */
    public function request($name, $default = null) {
        if (!array_key_exists($name, $this->requestParameters)) {
            return $default;
        }

        return $this->requestParameters[$name];
    }

    /**
     * @param string $name
     * @param null|mixed $default
     *
     * @return mixed|null
     */
    public function server($name, $default = null) {
        if (!array_key_exists($name, $this->serverParameters)) {
            return $default;
        }

        return $this->serverParameters[$name];
    }

    /**
     * @param string $name
     * @param null|mixed $default
     *
     * @return mixed|null
     */
    public function headers($name, $default = null) {
        if (!array_key_exists($name, $this->headerParameters)) {
            return $default;
        }

        return $this->headerParameters[$name];
    }

    /**
     * @return array<string, mixed>
     */
    public function getAllQueryParameters(): array {
        return $this->queryParameters;
    }
}