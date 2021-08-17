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

use OAuth2\Response as oauth2_response;
use totara_oauth2\facade\response_interface;

/**
 * A wrapper of OAuth2 Library response
 */
class response implements response_interface {
    /**
     * @var oauth2_response
     */
    private $response;

    /**
     * @param oauth2_response|null $response
     */
    public function __construct(?oauth2_response $response = null) {
        $this->response = $response ?? new oauth2_response();
    }

    /**
     * @param array $parameters
     * @return void
     */
    public function addParameters(array $parameters): void {
        $this->response->addParameters($parameters);
    }

    /**
     * @param array $httpHeaders
     * @return void
     */
    public function addHttpHeaders(array $httpHeaders): void {
        $this->response->addHttpHeaders($httpHeaders);
    }

    /**
     * @param int $statusCode
     * @return void
     */
    public function setStatusCode($statusCode): void {
        $this->response->setStatusCode($statusCode);
    }

    /**
     * @param int    $statusCode
     * @param string $name
     * @param null   $description
     * @param null   $uri
     *
     * @return void
     */
    public function setError($statusCode, $name, $description = null, $uri = null): void {
        $this->response->setError($statusCode, $name, $description, $uri);
    }

    /**
     * @param int    $statusCode
     * @param string $url
     * @param null   $state
     * @param null   $error
     * @param null   $errorDescription
     * @param null   $errorUri
     *
     * @return void
     */
    public function setRedirect(
        $statusCode, $url, $state = null, $error = null,
        $errorDescription = null, $errorUri = null
    ): void {
        $this->response->setRedirect($statusCode, $url, $state, $error, $errorDescription, $errorUri);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getParameter($name) {
        return $this->response->getParameter($name);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return $this->response->getParameters();
    }
}