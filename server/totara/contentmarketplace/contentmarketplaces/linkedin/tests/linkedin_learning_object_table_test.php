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
 * @package contentmarketplace_linkedin
 */
use core_phpunit\testcase;
use core\orm\query\builder;

/**
 * Low level of database tests for table "ttr_linkedin_learning_object"
 */
class contentmarketplace_linkedin_linkedin_learning_object_table_testcase extends testcase {
    /**
     * @return void
     */
    public function test_general_insert_for_linkedin_learning_object(): void {
        $urn = 'urn:li:lyndaCourse:144562';

        $record = new stdClass();
        $record->urn = $urn;
        $record->level = "BEGINNER";
        $record->last_updated_at = time();
        $record->published_at = time();
        $record->time_to_complete = 496;
        $record->title = 'This is title';
        $record->locale_language = 'en';
        $record->locale_country = 'US';

        $db = builder::get_db();
        $record->id = $db->insert_record('marketplace_linkedin_learning_object', $record);

        self::assertTrue($db->record_exists('marketplace_linkedin_learning_object', ['urn' => $urn]));
        self::assertEquals(
            1,
            $db->count_records('marketplace_linkedin_learning_object', ['urn' => $urn])
        );

        $fetched_record = $db->get_record('marketplace_linkedin_learning_object', ['urn' => $urn]);

        self::assertEquals($record->urn, $fetched_record->urn);
        self::assertEquals($record->id, $fetched_record->id);
        self::assertEquals($record->level, $fetched_record->level);
        self::assertEquals($record->last_updated_at, $fetched_record->last_updated_at);
        self::assertEquals($record->published_at, $fetched_record->published_at);
        self::assertEquals($record->time_to_complete, $fetched_record->time_to_complete);
        self::assertEquals($record->locale_language, $fetched_record->locale_language);
        self::assertEquals($record->locale_country, $fetched_record->locale_country);
        self::assertEquals($record->title, $fetched_record->title);

        self::assertNull($fetched_record->retired_at);
        self::assertNull($fetched_record->primary_image_url);
        self::assertNull($fetched_record->web_launch_url);
        self::assertNull($fetched_record->sso_launch_url);
        self::assertNull($fetched_record->description);
        self::assertNull($fetched_record->description_include_html);
        self::assertNull($fetched_record->short_description);
    }

    /**
     * @return void
     */
    public function test_insert_with_duplicated_urn(): void {
        $urn = 'urn:li:lyndaCourse:144562';
        $db = builder::get_db();

        $first_record = new stdClass();
        $first_record->urn = $urn;
        $first_record->level = "BEGINNER";
        $first_record->last_updated_at = time();
        $first_record->published_at = time();
        $first_record->time_to_complete = 469;
        $first_record->title = 'First title';
        $first_record->locale_language = 'en';
        $first_record->locale_country = 'US';

        $first_record->id = $db->insert_record('marketplace_linkedin_learning_object', $first_record);
        self::assertTrue($db->record_exists('marketplace_linkedin_learning_object', ['urn' => $urn]));
        self::assertEquals(1, $db->count_records('marketplace_linkedin_learning_object', ['urn' => $urn]));

        $second_record = new stdClass();
        $second_record->urn = $urn;
        $second_record->level = "INTERMEDIATE";
        $second_record->last_updated_at = time();
        $second_record->published_at = time();
        $second_record->time_to_complete = 496;
        $second_record->title = 'こんにちは世界';
        $second_record->locale_language = 'ja';
        $second_record->locale_country = 'JP';

        try {
            $db->insert_record('marketplace_linkedin_learning_object', $second_record);
            self::fail('Expecting the exception to be thrown');
        } catch (dml_write_exception $e) {
            // We have to be generic here, as different database vendors will yield different message.
            // But generally, it is about duplication.
            self::assertStringContainsString('error writing to database', strtolower($e->getMessage()));
            self::assertStringContainsString('duplicate', strtolower($e->getMessage()));
        }

        self::assertEquals(1, $db->count_records('marketplace_linkedin_learning_object', ['urn' => $urn]));
    }
}