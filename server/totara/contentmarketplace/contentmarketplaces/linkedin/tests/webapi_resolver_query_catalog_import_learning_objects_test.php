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

use contentmarketplace_linkedin\data_provider\learning_objects;
use contentmarketplace_linkedin\entity\learning_object as learning_object_entity;
use contentmarketplace_linkedin\formatter\timespan_field_formatter;
use contentmarketplace_linkedin\model\learning_object;
use contentmarketplace_linkedin\testing\generator;
use contentmarketplace_linkedin\testing\helper;
use core\date_format;
use core\format;
use core_phpunit\testcase;
use totara_contentmarketplace\plugininfo\contentmarketplace;
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

        $result = generator::instance()->get_mock_result_from_fixtures('response_1');
        learning_object::create_bulk_from_result($result);
        $this->data = learning_object_entity::repository()->get()->map_to(learning_object::class)->all();
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->data = null;
    }

    public function test_pagination(): void {
        // Get first page
        $first_result = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, 1));
        $this->assertCount(1, $first_result['items']);
        $this->assertEquals(2, $first_result['total']);
        $this->assertNotEmpty($first_result['next_cursor']);
        $this->assertEquals($this->data[1]->id, $first_result['items']->first()->id);

        // Get next result set
        $second_result = $this->resolve_graphql_query(self::QUERY, $this->get_query_options($first_result['next_cursor']));
        $this->assertCount(1, $second_result['items']);
        $this->assertEquals(2, $second_result['total']);
        $this->assertEmpty($second_result['next_cursor']);
        $this->assertEquals($this->data[0]->id, $second_result['items']->first()->id);
    }

    public function test_sort_by(): void {
        // sort by latest
        $result_latest = $this->resolve_graphql_query(
            self::QUERY,
            $this->get_query_options(null, 10, [], learning_objects::SORT_BY_LATEST)
        );
        $this->assertEquals($this->data[0]->id, $result_latest['items']->last()->id);
        $this->assertEquals($this->data[1]->id, $result_latest['items']->first()->id);

        // sort by alphabetical
        $result_alpha = $this->resolve_graphql_query(
            self::QUERY,
            $this->get_query_options(null, 10, [], learning_objects::SORT_BY_ALPHABETICAL)
        );
        $this->assertEquals($this->data[0]->id, $result_alpha['items']->first()->id);
        $this->assertEquals($this->data[1]->id, $result_alpha['items']->last()->id);
    }

    public function test_language_filter(): void {
        // language filter: english
        $result_en = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, 10, ['language' => 'en']));
        $this->assertEquals($this->data[0]->id, $result_en['items']->last()->id);
        $this->assertEquals($this->data[1]->id, $result_en['items']->first()->id);

        // language filter: french
        $result_fr = $this->resolve_graphql_query(self::QUERY, $this->get_query_options(null, 10, ['language' => 'fr']));
        $this->assertEmpty($result_fr['items']);
    }

    public function test_type_resolver(): void {
        $results = $this->resolve_graphql_query(self::QUERY, $this->get_query_options());
        $item = $results['items']->first();

        $expected_item_field_data = [
            'name' => ['Visio 2007 Essential Training', format::FORMAT_PLAIN],
            'short_description' => [
                'Explores how Visio 2007 can be used to create business and planning documents such as flow charts and floor layouts.',
                format::FORMAT_PLAIN
            ],
            'last_updated_at' => ['17 February 2021', date_format::FORMAT_DATE],
            'published_at' => ['27 March 2007', date_format::FORMAT_DATE],
            'level' => ['BEGINNER'],
            'time_to_complete' => ['8h 55m 53s', timespan_field_formatter::FORMAT_HUMAN],
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
        $this->expectDeprecationMessage('LinkedIn Learning Content Marketplace disabled');
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

    private function get_query_options($cursor = null, $limit = 10, $filters = [], $sort_by = 'latest'): array {
        return [
            'input' => [
                'pagination' => [
                    'limit' => $limit,
                    'cursor' => $cursor,
                ],
                'filters' => array_merge([
                    'language' => 'en',
                ], $filters),
                'sort_by' => $sort_by,
            ],
        ];
    }

}
