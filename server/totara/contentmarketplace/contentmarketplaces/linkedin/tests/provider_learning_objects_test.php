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

use contentmarketplace_linkedin\constants;
use contentmarketplace_linkedin\data_provider\learning_objects;
use contentmarketplace_linkedin\entity\learning_object;
use contentmarketplace_linkedin\model\learning_object as model;
use core\orm\query\builder;
use core_phpunit\testcase;
use contentmarketplace_linkedin\testing\generator;

/**
 * Most of the functionalities from {@see learning_objects} were covered
 * by webapi unit tests {@see contentmarketplace_linkedin_webapi_resolver_query_catalog_import_learning_objects_testcase}
 */
class contentmarketplace_linkedin_provider_learning_objects_testcase extends testcase {
    /**
     * @return void
     */
    public function test_fetch_by_asset_types(): void {
        $db = builder::get_db();
        $generator = generator::instance();

        // Create two records for course asset type.
        $course_1 = $generator->create_learning_object(
            "urn:lyndaCourse:252",
            ["asset_type" => constants::ASSET_TYPE_COURSE]
        );

        $course_2 = $generator->create_learning_object(
            "urn:lyndaCourse:251",
            ["asset_type" => constants::ASSET_TYPE_COURSE]
        );

        // Create two records for video asset type.
        $video_1 = $generator->create_learning_object(
            "urn:lyndaVideo:111",
            ["asset_type" => constants::ASSET_TYPE_VIDEO]
        );

        $video_2 = $generator->create_learning_object(
            "urn:lyndaVideo:112",
            ["asset_type" => constants::ASSET_TYPE_VIDEO]
        );

        // 4 records were added.
        self::assertEquals(4, $db->count_records(learning_object::TABLE));

        $video_provider  = new learning_objects();
        $video_provider->add_filters([
            "asset_type" => [constants::ASSET_TYPE_VIDEO]
        ]);

        $video_result = $video_provider->fetch()->get();
        self::assertNotEquals(4, $video_result->count());
        self::assertEquals(2, $video_result->count());

        /** @var model $video */
        foreach ($video_result as $video) {
            self::assertInstanceOf(model::class, $video);
            self::assertNotEquals($video->id, $course_1->id);
            self::assertNotEquals($video->id, $course_2->id);

            self::assertTrue(in_array($video->id, [$video_1->id, $video_2->id]));
        }

        $course_provider = new learning_objects();
        $course_provider->add_filters([
            "asset_type" => [constants::ASSET_TYPE_COURSE]
        ]);

        $course_result = $course_provider->fetch()->get();
        self::assertNotEquals(4, $video_result->count());
        self::assertEquals(2, $video_result->count());

        /** @var model $course */
        foreach ($course_result as $course) {
            self::assertInstanceOf(model::class, $course);
            self::assertNotEquals($course->id, $video_1->id);
            self::assertNotEquals($course->id, $video_2->id);

            self::assertTrue(in_array($course->id, [$course_1->id, $course_2->id]));
        }
    }

    /**
     * @return void
     */
    public function test_fetch_by_language(): void {
        $db = builder::get_db();
        $generator = generator::instance();

        // Create two records for course asset type.
        $course_1 = $generator->create_learning_object(
            "urn:lyndaCourse:252",
            [
                "locale_language" => "en",
                "locale_country" => "US"
            ]
        );

        $course_2 = $generator->create_learning_object(
            "urn:lyndaCourse:251",
            [
                "locale_language" => "ja",
                "locale_country" => "JA"
            ]
        );

        self::assertEquals(2, $db->count_records(learning_object::TABLE));

        $provider = new learning_objects();
        $provider->add_filters([
            "language" => "ja"
        ]);

        $result = $provider->fetch()->get();
        self::assertNotEquals(2, $result->count());
        self::assertEquals(1, $result->count());

        /** @var model $model */
        $model = $result->first();
        self::assertInstanceOf(model::class, $model);
        self::assertNotEquals($course_1->id, $model->id);
        self::assertEquals($course_2->id, $model->id);
    }

    /**
     * @return void
     */
    public function test_fetch_by_ids(): void {
        $db = builder::get_db();
        $generator = generator::instance();

        $course_1 = $generator->create_learning_object("a");
        $course_2 = $generator->create_learning_object("b");
        $course_3 = $generator->create_learning_object("c");

        self::assertEquals(3, $db->count_records(learning_object::TABLE));
        $provider = new learning_objects();
        $provider->add_filters(["ids" => [$course_1->id, $course_2->id]]);

        $result = $provider->fetch()->get();
        self::assertNotEquals(3, $result->count());
        self::assertEquals(2, $result->count());

        /** @var model $course */
        foreach ($result as $course) {
            self::assertInstanceOf(model::class, $course);
            self::assertNotEquals($course_3->id, $course->id);

            self::assertContainsEquals($course->id, [$course_1->id, $course_2->id]);
        }
    }

    /**
     * @return void
     */
    public function test_fetch_by_retired(): void {
        $db = builder::get_db();
        $generator = generator::instance();

        $course_1 = $generator->create_learning_object("a", ["retired_at" => time()]);
        $course_2 = $generator->create_learning_object("b");

        self::assertEquals(2, $db->count_records(learning_object::TABLE));
        $provider = new learning_objects();
        $provider->add_filters(["is_retired" => true]);

        $result = $provider->fetch()->get();
        self::assertNotEquals(2, $result->count());
        self::assertEquals(1, $result->count());

        /** @var model $course */
        $course = $result->first();
        self::assertInstanceOf(model::class, $course);
        self::assertNotEquals($course_2->id, $course->id);
        self::assertEquals($course_1->id, $course->id);
    }

    /**
     * @return void
     */
    public function test_sort_query_by_alphabetical(): void {
        $db = builder::get_db();
        $generator = generator::instance();

        $course_1 = $generator->create_learning_object("a", ["title" => "a"]);
        $course_2 = $generator->create_learning_object("b", ["title" => "b"]);

        self::assertEquals(2, $db->count_records(learning_object::TABLE));
        $provider = new learning_objects();
        $provider->sort_by(learning_objects::SORT_BY_ALPHABETICAL);

        $result = $provider->fetch()->get();
        self::assertEquals(2, $result->count());

        /** @var model $first */
        $first = $result->first();
        self::assertInstanceOf(model::class, $first);
        self::assertNotEquals($course_2->id, $first->id);
        self::assertEquals($course_1->id, $first->id);

        /** @var model $last */
        $last = $result->last();
        self::assertInstanceOf(model::class, $last);
        self::assertNotEquals($course_1->id, $last->id);
        self::assertEquals($course_2->id, $last->id);
    }
}