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
use totara_xapi\handler\factory;
use totara_xapi\request\request;

class totara_xapi_handler_factory_testcase extends testcase {
    /**
     * @return void
     */
    public function test_get_invalid_handler(): void {
        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage(
            "The class 'totara_dota2\\totara_xapi\\handler\\handler' does not exist."
        );

        factory::create_handler("totara_dota2", request::create_from_global());
    }
}