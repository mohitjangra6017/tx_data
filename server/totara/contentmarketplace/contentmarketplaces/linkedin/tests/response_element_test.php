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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package core
 */

use contentmarketplace_linkedin\api\v2\service\learning_asset\constant;
use contentmarketplace_linkedin\exception\json_validation_exception;
use core_phpunit\testcase;
use contentmarketplace_linkedin\api\v2\service\learning_asset\response\element;

class contentmarketplace_linkedin_response_element_testcase extends testcase {
    /**
     * @return void
     */
    public function test_instantiate_element_from_invalid_json(): void {
        $json_data = [
            'urn' => 'urn:li:lyndaCourse:252',
            'title' => [
                'value' => 'Title one',
                'locale' => [
                    'language' => 'en'
                ]
            ]
        ];

        // Note: the error message should be updated once the json validator
        // library is in place.
        $this->expectException(json_validation_exception::class);
        $this->expectExceptionMessage(
            implode(
                ' ',
                [
                    'Failed to validate the json data:',
                    'Missing field \'country\' in the json object',
                    'at field \'locale\' within the json object at',
                    'field \'title\' within the json object'
                ]
            )
        );

        element::create($json_data);
    }

    /**
     * @return void
     */
    public function test_instantiate_element_from_valid_json(): void {
        $time_now = time();
        $json_data = [
            'urn' => 'urn:li:lyndaCourse:252',
            'title' => [
                'value' => 'this is title',
                'locale' => [
                    'language' => 'en',
                    'country' => 'US'
                ]
            ],
            'type' => constant::ASSET_TYPE_COURSE,
            'details' => [
                'level' => constant::DIFFICULTY_LEVEL_BEGINNER,
                'images' => [],
                'lastUpdatedAt' => $time_now,
                'publishedAt' => $time_now,
                'urls' => []
            ]
        ];

        $element = element::create($json_data);

        self::assertEquals('urn:li:lyndaCourse:252', $element->get_urn());
        self::assertEquals('en_US', $element->get_title_locale()->__toString());
        self::assertEquals('this is title', $element->get_title_value());

        self::assertEquals($time_now, $element->get_last_updated_at()->get_raw());
        self::assertEquals($time_now, $element->get_published_at()->get_raw());
    }
}