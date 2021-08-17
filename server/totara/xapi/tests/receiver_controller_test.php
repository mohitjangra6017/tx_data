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
use core_phpunit\testcase;
use totara_xapi\controller\receiver_controller;
use totara_xapi\request\request;

class totara_xapi_receiver_controller_testcase extends testcase {
    /**
     * @return void
     */
    public function test_progress_with_invalid_component(): void  {
        $request = request::create_from_global(["component" => "totara_dota2"]);
        $controller = new receiver_controller($request);

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage(
            "The class 'totara_dota2\\totara_xapi\\handler\\handler' does not exist."
        );

        $controller->process();
    }
}