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
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

use contentmarketplace_linkedin\formatter\timespan_field_formatter;
use core_phpunit\testcase;

/**
 * @covers \contentmarketplace_linkedin\formatter\timespan_field_formatter
 */
class contentmarketplace_linkedin_timespan_formatter_testcase extends testcase {

    /**
     * @return array[] First value is the input in seconds, second value is the expected human readable output
     */
    public function provider(): array {
        return [
            [0, "0s"],
            [6, "6s"],
            [45, "45s"],
            [61, "1m 1s"],
            [119, "1m 59s"],
            [121, "2m 1s"],
            [180, "3m 0s"],
            [600, "10m 0s"],
            [3599, "59m 59s"],
            [3600, "1h 0m 0s"],
            [3614, "1h 0m 14s"],
            [3660, "1h 1m 0s"],
            [52343, "14h 32m 23s"],
            [852972, "236h 56m 12s"],
        ];
    }

    /**
     * @dataProvider provider
     */
    public function test_formatter_human_format(int $input_time, string $expected_string): void {
        $formatter = new timespan_field_formatter(timespan_field_formatter::FORMAT_HUMAN, context_system::instance());
        $this->assertEquals($expected_string, $formatter->format($input_time));
    }

    /**
     * @dataProvider provider
     */
    public function test_formatter_seconds_format(int $input_time): void {
        $formatter = new timespan_field_formatter(timespan_field_formatter::FORMAT_SECONDS, context_system::instance());
        $this->assertEquals($input_time, $formatter->format($input_time));
    }

}
