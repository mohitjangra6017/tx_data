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
 * @author  Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

use contentmarketplace_linkedin\constants;
use contentmarketplace_linkedin\data_provider\learning_objects;
use contentmarketplace_linkedin\entity\learning_object as learning_object_entity;
use contentmarketplace_linkedin\formatter\timespan_field_formatter;
use contentmarketplace_linkedin\model\learning_object;
use contentmarketplace_linkedin\testing\generator;
use core\date_format;
use core\format;
use core_phpunit\testcase;
use totara_contentmarketplace\plugininfo\contentmarketplace;
use totara_contentmarketplace\testing\helper;
use totara_webapi\phpunit\webapi_phpunit_helper;

/**
 * @covers \contentmarketplace_linkedin\webapi\resolver\query\catalog_import_learning_objects
 */
class contentmarketplace_linkedin_webapi_resolver_query_catalog_import_learning_objects_testcase extends testcase {

    use webapi_phpunit_helper;

    private const QUERY = 'contentmarketplace_linkedin_catalog_import_learning_objects';

    private const TYPE = 'contentmarketplace_linkedin_learning_object';

    /**
     * @var learning_object[]
     */
    protected $data;

    protected function setUp(): void {
        parent::setUp();
        self::setAdminUser();
        $plugin = contentmarketplace::plugin('linkedin');
        $plugin->enable();
    }

    private function create_data_from_fixture(): void {
        $result = generator::instance()->get_mock_result_from_fixtures('response_1');
        learning_object::create_bulk_from_result($result);
        $this->data = learning_object_entity::repository()->get()->map_to(learning_object::class)->all();
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->data = null;
    }

    public function test_pagination(): void {
        $this->create_data_from_fixture();

        // Get first page
        $first_result = $this->resolve_graphql_query(self::QUERY, $this->get_query_options([
            'page' => 1,
            'limit' => 1,
        ]));
        $this->assertCount(1, $first_result['items']);
        $this->assertEquals(2, $first_result['total']);
        $this->assertNotEmpty($first_result['next_cursor']);
        $this->assertEquals($this->data[1]->id, $first_result['items']->first()->id);

        // Get next result set
        $second_result = $this->resolve_graphql_query(self::QUERY, $this->get_query_options([
            'cursor' => $first_result['next_cursor'],
        ]));
        $this->assertCount(1, $second_result['items']);
        $this->assertEquals(2, $second_result['total']);
        $this->assertEmpty($second_result['next_cursor']);
        $this->assertEquals($this->data[0]->id, $second_result['items']->first()->id);
    }

    public function test_sort_by(): void {
        $this->create_data_from_fixture();

        // sort by latest
        $result_latest = $this->resolve_graphql_query(
            self::QUERY,
            $this->get_query_options(null, [], learning_objects::SORT_BY_LATEST)
        );
        $this->assertEquals($this->data[0]->id, $result_latest['items']->last()->id);
        $this->assertEquals($this->data[1]->id, $result_latest['items']->first()->id);

        // sort by alphabetical
        $result_alpha = $this->resolve_graphql_query(
            self::QUERY,
            $this->get_query_options(null, [], learning_objects::SORT_BY_ALPHABETICAL)
        );
        $this->assertEquals($this->data[0]->id, $result_alpha['items']->first()->id);
        $this->assertEquals($this->data[1]->id, $result_alpha['items']->last()->id);
    }

    public function test_language_filter(): void {
        $this->create_data_from_fixture();

        // language filter: english
        $result_en = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, ['language' => 'en']));
        $this->assertEquals($this->data[0]->id, $result_en['items']->last()->id);
        $this->assertEquals($this->data[1]->id, $result_en['items']->first()->id);

        // language filter: french
        $result_fr = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, ['language' => 'fr']));
        $this->assertEmpty($result_fr['items']);
    }

    public function test_search_filter(): void {
        $learning_object_1 = generator::instance()->create_learning_object('1', [
            'title' => 'Flash For Beginners',
            'short_description' => 'Flash not Photoshop',
            'description' => 'A great course!',
        ]);
        $learning_object_2 = generator::instance()->create_learning_object('2', [
            'title' => 'Flash For Experts',
            'short_description' => 'adobe flash is an out of date technology',
            'description' => 'why would anyone use it now days?',
        ]);
        $learning_object_3 = generator::instance()->create_learning_object('3', [
            'title' => 'Photoshop Pro 2021',
            'short_description' => 'Become a master',
            'description' => 'A course for experts',
        ]);

        $result_search_for_adobe_flash = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'search' => 'Adobe Flash',
        ]));
        $this->assertEquals(1, $result_search_for_adobe_flash['total']);
        $this->assertEquals($learning_object_2->id, $result_search_for_adobe_flash['items']->first()->id);

        $result_search_for_photoshop = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'search' => '     photoshop     ',
        ]));
        $this->assertEquals(2, $result_search_for_photoshop['total']);
        $this->assertEquals($learning_object_1->id, $result_search_for_photoshop['items']->first()->id);
        $this->assertEquals($learning_object_3->id, $result_search_for_photoshop['items']->last()->id);

        $result_search_for_course = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'search' => 'COURSE',
        ]));
        $this->assertEquals(2, $result_search_for_course['total']);
        $this->assertEquals($learning_object_1->id, $result_search_for_course['items']->first()->id);
        $this->assertEquals($learning_object_3->id, $result_search_for_course['items']->last()->id);

        $result_search_no_results = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'search' => '   UNKNOWN  ',
        ]));
        $this->assertEquals(0, $result_search_no_results['total']);

        $result_search_whitespace = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'search' => '     ',
        ]));
        $this->assertEquals(3, $result_search_whitespace['total']);
    }

    public function test_time_to_complete_filter(): void {
        $learning_object_1_min = generator::instance()->create_learning_object('1', [
            'time_to_complete' => MINSECS,
            'title' => '1',
        ]);
        $learning_object_30_mins = generator::instance()->create_learning_object('2', [
            'time_to_complete' => MINSECS * 30,
            'title' => '2',
        ]);
        $learning_object_1_hour = generator::instance()->create_learning_object('3', [
            'time_to_complete' => HOURSECS,
            'title' => '3',
        ]);
        $learning_object_3_hours = generator::instance()->create_learning_object('4', [
            'time_to_complete' => HOURSECS * 3,
            'title' => '4',
        ]);

        $result_under_10_mins = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'time_to_complete' => [
                json_encode([
                    'max' => MINSECS * 10,
                ]),
            ],
        ], learning_objects::SORT_BY_ALPHABETICAL));
        $this->assertEquals(1, $result_under_10_mins['total']);
        $this->assertEquals($learning_object_1_min->id, $result_under_10_mins['items']->first()->id);

        $result_1_to_2_hours = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'time_to_complete' => [
                json_encode([
                    'min' => HOURSECS,
                    'max' => HOURSECS * 2,
                ]),
            ],
        ], learning_objects::SORT_BY_ALPHABETICAL));
        $this->assertEquals(1, $result_1_to_2_hours['total']);
        $this->assertEquals($learning_object_1_hour->id, $result_1_to_2_hours['items']->first()->id);

        $result_10_to_30_mins_and_over_3_hours = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'time_to_complete' => [
                json_encode([
                    'min' => MINSECS * 10,
                    'max' => MINSECS * 30,
                ]),
                json_encode([
                    'min' => HOURSECS * 3,
                ]),
            ],
        ], learning_objects::SORT_BY_ALPHABETICAL));
        $this->assertEquals(2, $result_10_to_30_mins_and_over_3_hours['total']);
        $this->assertEquals($learning_object_30_mins->id, $result_10_to_30_mins_and_over_3_hours['items']->first()->id);
        $this->assertEquals($learning_object_3_hours->id, $result_10_to_30_mins_and_over_3_hours['items']->last()->id);

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('A min or a max value must be specified for the time_to_complete filter.');
        $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'time_to_complete' => [
                json_encode([
                    'min' => 0,
                    'max' => null,
                ]),
            ],
        ]));
    }

    public function test_asset_type_filter(): void {
        $learning_object_course = generator::instance()->create_learning_object('1', [
            'asset_type' => constants::ASSET_TYPE_COURSE,
            'title' => '1',
        ]);
        $learning_object_video = generator::instance()->create_learning_object('2', [
            'asset_type' => constants::ASSET_TYPE_VIDEO,
            'title' => '2',
        ]);
        $learning_object_chapter = generator::instance()->create_learning_object('3', [
            'asset_type' => constants::ASSET_TYPE_CHAPTER,
            'title' => '3',
        ]);

        $result_courses = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'asset_type' => [constants::ASSET_TYPE_COURSE],
        ], learning_objects::SORT_BY_ALPHABETICAL));
        $this->assertEquals(1, $result_courses['total']);
        $this->assertEquals($learning_object_course->id, $result_courses['items']->first()->id);

        $result_videos_or_chapters = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'asset_type' => [constants::ASSET_TYPE_VIDEO, constants::ASSET_TYPE_CHAPTER],
        ], learning_objects::SORT_BY_ALPHABETICAL));
        $this->assertEquals(2, $result_videos_or_chapters['total']);
        $this->assertEquals($learning_object_video->id, $result_videos_or_chapters['items']->first()->id);
        $this->assertEquals($learning_object_chapter->id, $result_videos_or_chapters['items']->last()->id);

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('Invalid asset type: NOT_A_TYPE');
        $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null,  [
            'asset_type' => ['NOT_A_TYPE'],
        ]));
    }

    public function test_level_filter(): void {
        $learning_object_beginner = generator::instance()->create_learning_object('1', [
            'level' => constants::DIFFICULTY_LEVEL_BEGINNER,
            'title' => '1',
        ]);
        $learning_object_intermediate = generator::instance()->create_learning_object('2', [
            'level' => constants::DIFFICULTY_LEVEL_INTERMEDIATE,
            'title' => '2',
        ]);
        $learning_object_advanced = generator::instance()->create_learning_object('3', [
            'level' => constants::DIFFICULTY_LEVEL_ADVANCED,
            'title' => '3',
        ]);

        $result_beginner = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'level' => [constants::DIFFICULTY_LEVEL_BEGINNER],
        ], learning_objects::SORT_BY_ALPHABETICAL));
        $this->assertEquals(1, $result_beginner['total']);
        $this->assertEquals($learning_object_beginner->id, $result_beginner['items']->first()->id);

        $result_intermediate_and_advanced = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'level' => [constants::DIFFICULTY_LEVEL_INTERMEDIATE, constants::DIFFICULTY_LEVEL_ADVANCED],
        ], learning_objects::SORT_BY_ALPHABETICAL));
        $this->assertEquals(2, $result_intermediate_and_advanced['total']);
        $this->assertEquals($learning_object_intermediate->id, $result_intermediate_and_advanced['items']->first()->id);
        $this->assertEquals($learning_object_advanced->id, $result_intermediate_and_advanced['items']->last()->id);

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('Invalid difficulty level: NOT_A_LEVEL');
        $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, [
            'level' => ['NOT_A_LEVEL'],
        ]));
    }

    public function test_type_resolver(): void {
        $this->create_data_from_fixture();

        $results = $this->resolve_graphql_query(self::QUERY, $this->get_query_options());
        $item = $results['items']->first();

        $expected_item_field_data = [
            'name' => ['Visio 2007 Essential Training', format::FORMAT_PLAIN],
            'short_description' => [
                'Explores how Visio 2007 can be used to create business and planning documents such as flow charts and floor layouts.',
                format::FORMAT_PLAIN,
            ],
            'last_updated_at' => ['17 February 2021', date_format::FORMAT_DATE],
            'published_at' => ['27 March 2007', date_format::FORMAT_DATE],
            'level' => ['BEGINNER'],
            'time_to_complete' => ['8h 56m', timespan_field_formatter::FORMAT_HUMAN],
            'asset_type' => ['COURSE'],
            'image_url' => ['https://cdn.lynda.com/course/260/260-636456652549313738-16x9.jpg'],
        ];
        foreach ($expected_item_field_data as $field => $data) {
            $this->assertEquals(
                $data[0],
                $this->resolve_graphql_type(self::TYPE, $field, $item, isset($data[1]) ? ['format' => $data[1]] : [])
            );
        }
    }

    public function test_plugin_disabled(): void {
        $plugin = contentmarketplace::plugin('linkedin');

        $plugin->enable();
        $this->resolve_graphql_query(self::QUERY, $this->get_query_options());

        $plugin->disable();
        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage('LinkedIn Learning Content Marketplace disabled');
        $this->resolve_graphql_query(self::QUERY, $this->get_query_options());
    }

    public function test_no_permission(): void {
        $role_id = helper::get_authenticated_user_role();
        $context_id = helper::get_default_course_category_context()->id;
        $user = self::getDataGenerator()->create_user();
        self::setUser($user);

        assign_capability('totara/contentmarketplace:add', CAP_ALLOW, $role_id, $context_id);
        $this->resolve_graphql_query(self::QUERY, $this->get_query_options());

        unassign_capability('totara/contentmarketplace:add', $role_id, $context_id);
        $this->expectException(required_capability_exception::class);
        $this->resolve_graphql_query(self::QUERY, $this->get_query_options());
    }

    private function get_query_options($pagination = null, $filters = [], $sort_by = 'latest'): array {
        return [
            'input' => [
                'pagination' => $pagination ?? [
                    'page' => null,
                    'limit' => 10,
                ],
                'filters' => array_merge([
                    'language' => 'en',
                ], $filters),
                'sort_by' => $sort_by,
            ],
        ];
    }

}
