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
 * @package totara_xapi
 */
namespace totara_xapi\handler;

use totara_xapi\request\request;
use totara_xapi\response\facade\result;

abstract class base_handler {
    /**
     * A request wrapper for xAPI statement.
     * @var request
     */
    protected $request;

    /**
     * @param request $request
     */
    public function __construct(request $request) {
        $this->request = $request;
    }

    /**
     * Authenticate the request, whether the user is logged in or logged out.
     * Or whether the request is genuinely the right ones.
     *
     * Returns NULL if everything is alright, otherwise a result object
     * to indicate that something went wrong.
     *
     * @return result|null
     */
    abstract public function authenticate(): ?result;

    /**
     * Process on the xAPI request.
     * Note the process should return the result/response.
     *
     * @return result
     */
    abstract public function process(): result;
}