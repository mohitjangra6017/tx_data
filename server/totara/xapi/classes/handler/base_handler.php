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

use totara_xapi\model\xapi_statement;
use totara_xapi\request\request;
use totara_xapi\response\facade\result;

abstract class base_handler {
    /**
     * A request wrapper for xAPI statement.
     * @var request
     */
    protected $request;

    /**
     * @var int|null
     */
    protected $time_now;

    /**
     * @param request $request
     * @param int|null $time_now
     */
    public function __construct(request $request, ?int $time_now = null) {
        $this->request = $request;
        $this->time_now = $time_now ?? time();
    }

    /**
     * @param int $time_now
     * @return void
     */
    public function set_time_now(int $time_now): void {
        $this->time_now = $time_now;
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
     * Ensure that the xAPI statement data is valid, or throw an exception if it isn't.
     *
     * @param string $statement
     */
    abstract public function validate_structure(string $statement): void;

    /**
     * Get the ID of the Totara user that this xAPI statement is about.
     *
     * @param string $statement
     * @return int|null
     */
    abstract public function get_user_id(string $statement): ?int;

    /**
     * Process on the xAPI request.
     * Note the process should return the result/response.
     *A
     * @param xapi_statement $statement
     * @return result
     */
    abstract public function process(xapi_statement $statement): result;
}