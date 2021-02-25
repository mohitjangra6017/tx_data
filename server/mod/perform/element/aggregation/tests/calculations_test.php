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
 * @author Riana Rossouw <riana.rossouw@totaralearning.com>
 * @package performelement_aggregation
 */

use performelement_aggregation\calculation_method;

/**
 * @group perform
 * @group perform_element
 */
class performelement_aggregation_calculations_testcase extends advanced_testcase {
    
    public function test_get_aggregation_calculation_method_classes():void {
        $methods = calculation_method::get_aggregation_calculation_methods();
        
        // TODO: Fix when other calculation methods are implemented
        $expected = [
            'average' => get_string('calculation_label_average', 'performelement_aggregation'),
        ];
        self::assertCount(count($expected), $methods);
        foreach ($expected as $expected_name => $expected_label) {
            foreach ($methods as $actual_name) {
                if ($expected_name == $actual_name) {
                    $instance = calculation_method::load_by_method($actual_name);
                    self::assertSame($expected_name, $instance::get_name());
                    self::assertSame($expected_label, $instance::get_label());
                    unset($expected[$expected_name]);
                }
            }
        }
        self::assertEmpty($expected);
    }

    public function calculation_method_provider(): array {
        return [
            'No values' => [
                'values' => [],
                'expected' => ['average' => 0],
            ],
            'Single non zero value' => [
                'values' => [123],
                'expected' => ['average' => 123],
            ],
            'Single float value' => [
                'values' => [12.3],
                'expected' => ['average' => 12.3],
            ],
            'All non zero whole numbers' => [
                'values' => [1, 2, 3, 4],
                'expected' => ['average' => 2.5],
            ],
            'Whole numbers with a zero value' => [
                'values' => [1, 0, 2, 0, 3, 4],
                'expected' => ['average' => 1.67],
            ],
            'Mixed types' => [
                'values' => [12.3, 7, 'abc'],
                'expected' => ['average' => 6.43],
            ],
        ];
    }

    /**
     * @dataProvider calculation_method_provider
     * @param array $values
     * @param array $expected
     */
    public function test_aggregation_calculation(array $values, array $expected): void {
        foreach ($expected as $method => $expected_outcome) {
            $instance = calculation_method::load_by_method($method);
            $answer = $instance->aggregate($values);
            // Testing 2 decimals as it is the default precision
            self::assertEqualsWithDelta($expected_outcome, $answer, 0.01);
        }
    }
}