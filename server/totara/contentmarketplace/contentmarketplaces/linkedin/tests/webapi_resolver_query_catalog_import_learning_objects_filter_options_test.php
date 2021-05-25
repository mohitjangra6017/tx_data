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

use core_phpunit\testcase;
use totara_contentmarketplace\plugininfo\contentmarketplace;
use totara_contentmarketplace\testing\helper;
use totara_tui\tree\branch;
use totara_tui\tree\leaf;
use totara_webapi\phpunit\webapi_phpunit_helper;

/**
 * @covers \contentmarketplace_linkedin\webapi\resolver\query\catalog_import_learning_objects_filter_options
 */
class contentmarketplace_linkedin_webapi_resolver_query_catalog_import_learning_objects_filter_options_testcase extends testcase {

    use webapi_phpunit_helper;

    private const QUERY = 'contentmarketplace_linkedin_catalog_import_learning_objects_filter_options';

    private const TYPE_BRANCH = 'totara_tui_tree_branch';
    private const TYPE_LEAF = 'totara_tui_tree_leaf';

    protected function setUp(): void {
        parent::setUp();
        self::setAdminUser();
        $plugin = contentmarketplace::plugin('linkedin');
        $plugin->enable();
    }

    // TODO: Add test for subject data in TL-30373

    public function test_resolve_asset_types(): void {
        $result = $this->resolve_graphql_query(self::QUERY);
        $assert_types_branch = reset($result['asset_type']);

        $expected_data = [
            'id' => 'asset_types',
            'label' => 'Type',
            'content' => [
                'items' => [
                    [
                        'id' => 'COURSE',
                        'label' => 'Courses',
                    ],
                    [
                        'id' => 'VIDEO',
                        'label' => 'Videos',
                    ],
                    [
                        'id' => 'LEARNING_PATH',
                        'label' => 'Learning Paths',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected_data, $this->resolve_branch($assert_types_branch));
    }

    public function test_resolve_time_to_complete(): void {
        $result = $this->resolve_graphql_query(self::QUERY);
        $assert_types_branch = reset($result['time_to_complete']);

        $expected_data = [
            'id' => 'time_to_complete',
            'label' => 'Time to Complete',
            'content' => [
                'items' => [
                    [
                        'id' => '{"max":600}',
                        'label' => '< 10 mins',
                    ],
                    [
                        'id' => '{"min":600,"max":1800}',
                        'label' => '10 - 30 mins',
                    ],
                    [
                        'id' => '{"min":1800,"max":3600}',
                        'label' => '30 - 60 mins',
                    ],
                    [
                        'id' => '{"min":3600,"max":7200}',
                        'label' => '1 - 2 hours',
                    ],
                    [
                        'id' => '{"min":7200,"max":10800}',
                        'label' => '2 - 3 hours',
                    ],
                    [
                        'id' => '{"min":10800}',
                        'label' => '3+ hours',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected_data, $this->resolve_branch($assert_types_branch));
    }

    public function test_plugin_disabled(): void {
        $plugin = contentmarketplace::plugin('linkedin');

        $plugin->enable();
        $this->resolve_graphql_query(self::QUERY);

        $plugin->disable();
        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage('LinkedIn Learning Content Marketplace disabled');
        $this->resolve_graphql_query(self::QUERY);
    }

    public function test_no_permission(): void {
        $role_id = helper::get_authenticated_user_role();
        $context_id = helper::get_default_course_category_context()->id;
        $user = self::getDataGenerator()->create_user();
        self::setUser($user);

        assign_capability('totara/contentmarketplace:add', CAP_ALLOW, $role_id, $context_id);
        $this->resolve_graphql_query(self::QUERY);

        unassign_capability('totara/contentmarketplace:add', $role_id, $context_id);
        $this->expectException(required_capability_exception::class);
        $this->resolve_graphql_query(self::QUERY);
    }

    /**
     * Recursively resolves the tree using the GraphQL type fields.
     * This will return basically the same result as a query via Apollo would.
     *
     * @param branch $tree_branch
     * @return array
     */
    private function resolve_branch(branch $tree_branch): array {
        $result = $this->resolve_leaf($tree_branch);

        $content = $this->resolve_graphql_type(self::TYPE_BRANCH, 'content', $tree_branch);
        foreach ($content['items'] as $item) {
            $result['content']['items'][] = $this->resolve_leaf($item);
        }

        $children = $this->resolve_graphql_type(self::TYPE_BRANCH, 'children', $tree_branch);
        foreach ($children as $child) {
            $result['children'][] = $this->resolve_branch($child);
        }

        return $result;
    }

    /**
     * Resolve an individual leaf.
     *
     * @param leaf $tree_leaf
     * @return array
     */
    private function resolve_leaf(leaf $tree_leaf): array {
        return [
            'id' => $this->resolve_graphql_type(self::TYPE_LEAF, 'id', $tree_leaf),
            'label' => $this->resolve_graphql_type(self::TYPE_LEAF, 'label', $tree_leaf),
        ];
    }

}
