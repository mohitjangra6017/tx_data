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

use OAuth2\Request as oauth2_request;
use totara_oauth2\facade\request_interface;

/**
 * A wrapper request.
 */
class request implements request_interface {
    /**
     * @var oauth2_request
     */
    private $request;

    /**
     * @param oauth2_request $request
     */
    public function __construct(oauth2_request $request) {
        $this->request = $request;
    }

    /**
     * @return request
     */
    public static function create_from_global(): request {
        $request = oauth2_request::createFromGlobals();
        return new self($request);
    }

    /**
     * @param string $name
     * @param null   $default
     * @return mixed
     */
    public function query($name, $default = null) {
        return $this->request->query($name, $default);
    }

    /**
     * @param string $name
     * @param null   $default
     * @return mixed
     */
    public function request($name, $default = null) {
        return $this->request->request($name, $default);
    }

    /**
     * @param string $name
     * @param null   $default
     * @return mixed
     */
    public function server($name, $default = null) {
        return $this->request->server($name, $default);
    }

    /**
     * @param string $name
     * @param null   $default
     *
     * @return mixed
     */
    public function headers($name, $default = null) {
        return $this->request->headers($name, $default);
    }

    /**
     * @return array|mixed
     */
    public function getAllQueryParameters() {
        return $this->request->getAllQueryParameters();
    }
}