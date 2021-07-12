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

use container_course\course;
use contentmarketplace_linkedin\dto\course_creation_result;
use contentmarketplace_linkedin\testing\generator;
use contentmarketplace_linkedin\webapi\resolver\mutation\catalog_import_create_course;
use core\orm\query\builder;
use core_phpunit\testcase;
use totara_contentmarketplace\plugininfo\contentmarketplace;
use totara_contentmarketplace\testing\helper;
use totara_webapi\phpunit\webapi_phpunit_helper;

class contentmarketplace_linkedin_webapi_resolver_mutation_catalog_import_create_course_testcase extends testcase {
    use webapi_phpunit_helper;

    /**
     * @return void
     */
    protected function setUp(): void {
        $marketplace_linkedin = contentmarketplace::plugin('linkedin');
        $marketplace_linkedin->enable();
    }

    /**
     * @return void
     */
    public function test_create_two_courses_with_default_category_id(): void {
        $marketplace_generator = generator::instance();
        $learning_object_1 = $marketplace_generator->create_learning_object('urn:lyndaCourse:252');
        $learning_object_2 = $marketplace_generator->create_learning_object('urn:lyndaCourse:260');

        $generator = self::getDataGenerator();
        $new_category = $generator->create_category();
        $user_one = $generator->create_user();
        $context = context_coursecat::instance($new_category->id);

        self::assertFalse(has_capability('totara/contentmarketplace:add', $context, $user_one->id));

        role_assign(
            helper::get_course_creator_role(),
            $user_one->id,
            $context->id
        );

        self::assertTrue(has_capability('totara/contentmarketplace:add', $context, $user_one->id));
        self::setUser($user_one);

        $db = builder::get_db();
        self::assertEquals(0, $db->count_records('course', ['containertype' => course::get_type()]));

        // Add the course with the graphql mutation.
        /** @var course_creation_result $result */
        $result = $this->resolve_graphql_mutation(
            $this->get_graphql_name(catalog_import_create_course::class),
            [
                'input' => [
                    ['learning_object_id' => $learning_object_1->id],
                    ['learning_object_id' => $learning_object_2->id],
                ],
            ]
        );

        self::assertInstanceOf(course_creation_result::class, $result);
        self::assertTrue($result->is_success());

        // 2 new courses created, and they are all under the new category.
        self::assertEquals(2, $db->count_records('course', ['containertype' => course::get_type()]));
        self::assertEquals(
            2,
            $db->count_records(
                'course',
                [
                    'containertype' => course::get_type(),
                    'category' => $new_category->id,
                ]
            )
        );
    }

    /**
     * @return void
     */
    public function test_create_course_with_authenticated_user(): void {
        $marketplace_generator = generator::instance();
        $learning_object = $marketplace_generator->create_learning_object('urn:lyndaCourse:252');

        $generator = self::getDataGenerator();
        $user = $generator->create_user();

        self::setUser($user);

        $this->expectException(required_capability_exception::class);
        $this->expectExceptionMessage(
            get_string(
                'nopermissions',
                'error',
                get_string('contentmarketplace:add', 'totara_contentmarketplace')
            )
        );

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(catalog_import_create_course::class),
            [
                'input' => [
                    ['learning_object_id' => $learning_object->id],
                ],
            ]
        );
    }

    /**
     * @return void
     */
    public function test_create_course_with_disabled_plugin(): void {
        $marketplace_linkedin = contentmarketplace::plugin('linkedin');
        $marketplace_linkedin->disable();

        $generator = generator::instance();
        $learning_object = $generator->create_learning_object('urn:lyndaCourse:252');

        self::setAdminUser();
        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage(
            get_string(
                'error:disabledmarketplace',
                'totara_contentmarketplace',
                $marketplace_linkedin->displayname
            )
        );

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(catalog_import_create_course::class),
            [
                'input' => [
                    ['learning_object_id' => $learning_object->id],
                ],
            ]
        );
    }

    /**
     * @return void
     */
    public function test_create_course_as_guest_user(): void {
        $generator = generator::instance();
        $learning_object = $generator->create_learning_object('urn:lyndaCourse:252');

        self::setGuestUser();

        $this->expectException(required_capability_exception::class);
        $this->expectExceptionMessage(
            get_string(
                'nopermissions',
                'error',
                get_string('contentmarketplace:add', 'totara_contentmarketplace')
            )
        );

        $this->resolve_graphql_mutation(
            $this->get_graphql_name(catalog_import_create_course::class),
            [
                'input' => [
                    ['learning_object_id' => $learning_object->id],
                ],
            ]
        );
    }

    /**
     * @return void
     */
    public function test_create_course_with_authenticated_user_that_has_permission(): void {
        $generator = self::getDataGenerator();
        $user_one = $generator->create_user();

        $new_category = $generator->create_category();
        $context_category = context_coursecat::instance($new_category->id);

        self::assertFalse(has_capability('totara/contentmarketplace:add', $context_category, $user_one->id));

        // assign capability for the user, within this very context category.
        assign_capability(
            'totara/contentmarketplace:add',
            CAP_ALLOW,
            helper::get_authenticated_user_role(),
            $context_category->id
        );

        self::assertTrue(has_capability('totara/contentmarketplace:add', $context_category, $user_one->id));

        // Create learning object.
        $marketplace_generator = generator::instance();
        $learning_object = $marketplace_generator->create_learning_object('urn:lyndaCourse:469');

        $db = builder::get_db();
        self::assertEquals(0, $db->count_records('course', ['containertype' => course::get_type()]));

        self::setUser($user_one->id);

        /** @var course_creation_result $result */
        $result = $this->resolve_graphql_mutation(
            $this->get_graphql_name(catalog_import_create_course::class),
            [
                'input' => [
                    [
                        'learning_object_id' => $learning_object->id,
                        'category' => $context_category->id
                    ]
                ]
            ]
        );

        self::assertInstanceOf(course_creation_result::class, $result);
        self::assertTrue($result->is_success());
        self::assertEmpty($result->get_message());

        $redirect_url = $result->get_redirect_url();
        self::assertNotNull($redirect_url);
        self::assertEquals(
            new moodle_url('/totara/catalog/index.php', ['orderbykey' => 'time']),
            $redirect_url
        );

        self::assertEquals(1, $db->count_records('course', ['containertype' => course::get_type()]));
    }

    /**
     * @return void
     */
    public function test_create_course_as_course_creator_role(): void {
        $generator = self::getDataGenerator();
        $user_one = $generator->create_user();

        $context_category = helper::get_default_course_category_context();
        self::assertFalse(has_capability('totara/contentmarketplace:add', $context_category, $user_one->id));

        role_assign(
            helper::get_course_creator_role(),
            $user_one->id,
            $context_category->id
        );

        self::assertTrue(has_capability('totara/contentmarketplace:add', $context_category, $user_one->id));

        $db = builder::get_db();
        self::assertEquals(0, $db->count_records('course', ['containertype' => course::get_type()]));

        self::setUser($user_one);
        $marketplace_generator = generator::instance();
        $learning_object = $marketplace_generator->create_learning_object('urn:lyndaCourse:496');

        /** @var course_creation_result $result */
        $result = $this->resolve_graphql_mutation(
            $this->get_graphql_name(catalog_import_create_course::class),
            [
                'input' => [
                    [
                        'learning_object_id' => $learning_object->id,
                        'category' => $context_category->instanceid
                    ]
                ],
            ]
        );

        self::assertInstanceOf(course_creation_result::class, $result);
        self::assertTrue($result->is_success());
        self::assertEmpty($result->get_message());

        $redirect_url = $result->get_redirect_url();
        self::assertNotNull($redirect_url);
        self::assertEquals(
            new moodle_url('/totara/catalog/index.php', ['orderbykey' => 'time']),
            $redirect_url
        );

        self::assertEquals(1, $db->count_records('course', ['containertype' => course::get_type()]));
    }

    /**
     * @return void
     */
    public function test_create_course_with_over_50_items(): void {
        $input = [];
        $category_id = helper::get_default_course_category_id();

        for ($i = 0; $i < 51; $i++) {
            $input[] = [
                'learning_object_id' => rand(0, 999),
                'category' => $category_id
            ];
        }

        self::setAdminUser();

        $db = builder::get_db();
        self::assertEquals(0, $db->count_records('course', ['containertype' => course::get_type()]));

        /** @var course_creation_result $result */
        $result = $this->resolve_graphql_mutation(
            $this->get_graphql_name(catalog_import_create_course::class),
            ['input' => $input]
        );

        self::assertInstanceOf(course_creation_result::class, $result);
        self::assertTrue($result->is_success());
        self::assertEmpty($result->get_message());

        $redirect_url = $result->get_redirect_url();
        self::assertNotNull($redirect_url);
        self::assertEquals(
            new moodle_url('/totara/catalog/index.php'),
            $redirect_url
        );

        self::assertEquals(0, $db->count_records('course', ['containertype' => course::get_type()]));
    }

    /**
     * @return void
     */
    public function test_create_course_with_persist_mutation(): void {
        $generator = generator::instance();
        $learning_object = $generator->create_learning_object('urn:lyndaCourse:252');

        $db = builder::get_db();
        self::assertEquals(0, $db->count_records('course', ['containertype' => course::get_type()]));

        self::setAdminUser();
        $category_id = helper::get_default_course_category_id();
        $result = $this->execute_graphql_operation(
            'contentmarketplace_linkedin_catalog_import_create_course',
            [
                'input' => [
                    [
                        'learning_object_id' => $learning_object->id,
                        'category_id' => $category_id
                    ]
                ]
            ]
        );

        self::assertEmpty($result->errors);
        self::assertNotEmpty($result->data);

        self::assertIsArray($result->data);
        self::assertArrayHasKey('payload', $result->data);

        $payload = $result->data['payload'];
        self::assertIsArray($payload);

        self::assertArrayHasKey('success', $payload);
        self::assertArrayHasKey('message', $payload);
        self::assertArrayHasKey('redirect_url', $payload);

        self::assertTrue($payload['success']);
        self::assertEmpty($payload['message']);
        self::assertEquals(
            (string) new moodle_url('/totara/catalog/index.php', ['orderbykey' => 'time']),
            $payload['redirect_url']
        );
    }

    /**
     * @return void
     */
    public function test_create_course_with_system_category(): void {
        global $DB;
        self::setAdminUser();

        $records = $DB->get_records('course_categories', ['issystem' => 1]);
        self::assertGreaterThanOrEqual(1, count($records));
        $cat = reset($records);

        $generator = generator::instance();
        $learning_object = $generator->create_learning_object('urn:lyndaCourse:252');

        self::expectException(coding_exception::class);
        self::expectExceptionMessage("Category {$cat->id} is not supported.");
        $this->resolve_graphql_mutation(
            'contentmarketplace_linkedin_catalog_import_create_course',
            [
                'input' => [
                    [
                        'learning_object_id' => $learning_object->id,
                        'category_id' => $cat->id
                    ]
                ]
            ]
        );
    }
}