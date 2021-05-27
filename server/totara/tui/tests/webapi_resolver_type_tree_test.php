<?php
/**
 * This file is part of Totara Core
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
 *
 * MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package totara_tui
 */

use core\format;
use core_phpunit\testcase;
use totara_tui\tree\branch;
use totara_tui\tree\leaf;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_tui_webapi_resolver_type_tree_test extends testcase {

    use webapi_phpunit_helper;

    private const TYPE_BRANCH = 'totara_tui_tree_branch';
    private const TYPE_LEAF = 'totara_tui_tree_leaf';

    public function test_resolve_tree(): void {
        $tree = (new branch('1', 'Root Branch'))->add_branches(
            (new branch('2', 'Child Branch 1'))->add_leaves(
                new leaf('2-1', 'Child Branch 1 Leaf 1'),
                new leaf('2-2', 'Child Branch 1 Leaf 2'),
            ),
            (new branch('3', 'Child Branch 2'))->add_leaves(
                new leaf('3-1', 'Child Branch 2 Leaf 1'),
                new leaf('3-2', 'Child Branch 2 Leaf 2'),
            ),
        );

        $expected_result = [
            'id' => '1',
            'label' => 'Root Branch',
            'children' => [
                [
                    'id' => '2',
                    'label' => 'Child Branch 1',
                    'content' => [
                        'items' => [
                            [
                                'id' => '2-1',
                                'label' => 'Child Branch 1 Leaf 1',
                            ],
                            [
                                'id' => '2-2',
                                'label' => 'Child Branch 1 Leaf 2',
                            ],
                        ],
                    ],
                ],
                [
                    'id' => '3',
                    'label' => 'Child Branch 2',
                    'content' => [
                        'items' => [
                            [
                                'id' => '3-1',
                                'label' => 'Child Branch 2 Leaf 1',
                            ],
                            [
                                'id' => '3-2',
                                'label' => 'Child Branch 2 Leaf 2',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected_result, $this->resolve_branch($tree));
    }

    /**
     * Ensure that the label has plain formatting applied.
     * Note that for now, the label is always formatted as plain due to how it is displayed in the front end.
     */
    public function test_label_is_formatted_correctly(): void {
        $xss_string = "Leaf <script>alert('XSS!')</script>Label";
        $leaf = new leaf('1', $xss_string);

        $resolved_label_field = $this->resolve_graphql_type(self::TYPE_LEAF, 'label', $leaf);
        $this->assertNotEquals($xss_string, $resolved_label_field);
        $this->assertEquals("Leaf alert('XSS!')Label", $resolved_label_field);
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
