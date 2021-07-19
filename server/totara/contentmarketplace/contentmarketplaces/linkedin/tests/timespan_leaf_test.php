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
 * @package contentmarketplace_linkedin
 */

use contentmarketplace_linkedin\dto\timespan;
use contentmarketplace_linkedin\tree\timespan_leaf;
use core_phpunit\testcase;

class contentmarketplace_linkedin_timespan_leaf_testcase extends testcase {
    /**
     * @var timespan_leaf|null
     */
    private $timespan_leaf;

    /**
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
        $this->timespan_leaf = new timespan_leaf(
            timespan::minutes(15),
            timespan::minutes(20),
            "Cool kid"
        );
    }

    /**
     * @return void
     */
    protected function tearDown(): void {
        parent::tearDown();
        $this->timespan_leaf = null;
    }

    /**
     * @return void
     */
    public function test_get_id(): void {
        self::assertEquals(
            json_encode([
                "min" => 15 * MINSECS,
                "max" => 20 * MINSECS
            ]),
            $this->timespan_leaf->get_id()
        );
    }

    /**
     * @return void
     */
    public function test_get_label(): void {
        self::assertEquals("Cool kid", $this->timespan_leaf->get_label());
    }

    /**
     * @return void
     */
    public function test_get_parent(): void {
        self::assertNull($this->timespan_leaf->get_parent());
    }
}