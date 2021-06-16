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

use core_phpunit\testcase;
use totara_tui\tree\branch;
use totara_tui\tree\leaf;

class totara_tui_tree_test extends testcase {

    /**
     * @var branch
     */
    protected $parent_branch;

    /**
     * @var branch
     */
    protected $child_branch;

    /**
     * @var branch
     */
    protected $grandchild_branch;

    /**
     * @var leaf
     */
    protected $grandchild_leaf_1;

    /**
     * @var leaf
     */
    protected $grandchild_leaf_2;

    /**
     * @var leaf
     */
    protected $grandchild_leaf_3;

    protected function setUp(): void {
        parent::setUp();
        $this->parent_branch = new branch('1', 'Parent Branch');
        $this->child_branch = new branch('2', 'Child Branch');
        $this->grandchild_branch = new branch('3', 'Grandchild Branch');

        $this->grandchild_leaf_1 = new leaf('3-1', 'Grandchild Leaf 1');
        $this->grandchild_leaf_2 = new leaf('3-2', 'Grandchild Leaf 2');
        $this->grandchild_leaf_3 = new leaf('3-3', 'Grandchild Leaf 3');

        $this->grandchild_branch->add_leaves($this->grandchild_leaf_1, $this->grandchild_leaf_2, $this->grandchild_leaf_3);

        $this->parent_branch->add_branches($this->child_branch);
        $this->child_branch->add_branches($this->grandchild_branch);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->parent_branch = $this->child_branch = $this->grandchild_branch = null;
        $this->grandchild_leaf_1 = $this->grandchild_leaf_2 = $this->grandchild_leaf_3 = null;
    }

    public function test_get_id(): void {
        self::assertEquals('1', $this->parent_branch->get_id());
        self::assertEquals('2', $this->child_branch->get_id());
        self::assertEquals('3', $this->grandchild_branch->get_id());
        self::assertEquals('3-1', $this->grandchild_leaf_1->get_id());
        self::assertEquals('3-2', $this->grandchild_leaf_2->get_id());
        self::assertEquals('3-3', $this->grandchild_leaf_3->get_id());
    }

    public function test_get_label(): void {
        self::assertEquals('Parent Branch', $this->parent_branch->get_label());
        self::assertEquals('Child Branch', $this->child_branch->get_label());
        self::assertEquals('Grandchild Branch', $this->grandchild_branch->get_label());
        self::assertEquals('Grandchild Leaf 1', $this->grandchild_leaf_1->get_label());
        self::assertEquals('Grandchild Leaf 2', $this->grandchild_leaf_2->get_label());
        self::assertEquals('Grandchild Leaf 3', $this->grandchild_leaf_3->get_label());
    }

    public function test_branches(): void {
        self::assertEquals([$this->child_branch], $this->parent_branch->get_branches());
        self::assertEquals([$this->grandchild_branch], $this->child_branch->get_branches());
        self::assertEquals([], $this->grandchild_branch->get_branches());
    }

    public function test_leaves(): void {
        self::assertEquals([], $this->parent_branch->get_leaves());
        self::assertEquals([], $this->child_branch->get_leaves());
        self::assertEquals(
            [$this->grandchild_leaf_1, $this->grandchild_leaf_2, $this->grandchild_leaf_3],
            $this->grandchild_branch->get_leaves()
        );
    }

    public function test_is_root(): void {
        self::assertTrue($this->parent_branch->is_root());
        self::assertFalse($this->child_branch->is_root());
        self::assertFalse($this->grandchild_branch->is_root());
    }

    public function test_get_root(): void {
        self::assertEquals($this->parent_branch, $this->parent_branch->get_root());
        self::assertEquals($this->parent_branch, $this->child_branch->get_root());
        self::assertEquals($this->parent_branch, $this->grandchild_branch->get_root());
    }

    public function test_get_nodes_from_ids(): void {
        // Nothing returns nothing - pretty simple really
        self::assertEquals([], $this->parent_branch->get_nodes_from_ids([]));

        // Parent branch's ID is specified last, but the order returned will be the order that is displayed in the frontend.
        self::assertEquals(
            [
                $this->parent_branch->get_id() => $this->parent_branch,
                $this->child_branch->get_id() => $this->child_branch,
                $this->grandchild_leaf_1->get_id() => $this->grandchild_leaf_1,
                $this->grandchild_leaf_3->get_id() => $this->grandchild_leaf_3,
            ],
            $this->parent_branch->get_nodes_from_ids([
                $this->child_branch->get_id(),
                $this->grandchild_leaf_3->get_id(),
                $this->grandchild_leaf_1->get_id(),
                $this->parent_branch->get_id(),
            ])
        );

        // Searching for the parent's ID, but the method was called on the child, so the parent won't be included in the search.
        self::assertEquals(
            [
                $this->grandchild_leaf_2->get_id() => $this->grandchild_leaf_2,
            ],
            $this->child_branch->get_nodes_from_ids([
                $this->grandchild_leaf_2->get_id(),
                $this->parent_branch->get_id(),
            ])
        );
    }

}
