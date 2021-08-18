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
namespace totara_xapi\controller;

use context;
use context_system;
use totara_mvc\controller;
use totara_xapi\handler\factory;
use totara_xapi\local\helper;
use totara_xapi\request\request;
use totara_xapi\response\facade\result;
use moodle_url;

class receiver_controller extends controller {
    /**
     * @var request
     */
    private $request;

    /**
     * @var int
     */
    private $time_now;

    /**
     * @param request|null $request
     * @param int|null $time_now
     */
    public function __construct(?request $request = null, ?int $time_now = null) {
        parent::__construct();

        $this->request = $request ?? request::create_from_global();

        // The current of processing time.
        $this->time_now = $time_now ?? time();
        $this->require_login = false;

        $this->url = new moodle_url(
            "/totara/xapi/receiver.php",
            ["component" => $this->request->get_required_parameter("component", PARAM_COMPONENT)]
        );
    }

    /**
     * @return context
     */
    protected function setup_context(): context {
        return context_system::instance();
    }

    /**
     * @return result
     */
    public function action(): result {
        $component = $this->request->get_required_parameter("component", PARAM_COMPONENT);
        $handler = factory::create_handler($component, $this->request);
        $handler->set_time_now($this->time_now);

        // Authenticate with the request.
        $result_response = $handler->authenticate();
        if (!is_null($result_response)) {
            // Response appears to be an issue. Hence we are going to returns the response, back to
            // the client, instead of processing it.
            return $result_response;
        }

        // Logging the xapi statement.
        $statement = helper::logging_request($this->request);
        return $handler->process($statement);
    }

    /**
     * @return void
     */
    protected function authorize(): void {
        // NOTE: we do not authorize for this controller, because it has been delegated down
        // to the xAPI handler/processor.
    }
}