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

    public function test_tree(): void {
        $parent_branch = new branch('1', 'Parent Branch');
        $child_branch = new branch('2', 'Child Branch');
        $grandchild_branch = new branch('3', 'Grandchild Branch');

        $grandchild_leaf_1 = new leaf('3-1', 'Grandchild Leaf 1');
        $grandchild_leaf_2 = new leaf('3-2', 'Grandchild Leaf 2');
        $grandchild_leaf_3 = new leaf('3-3', 'Grandchild Leaf 3');

        $grandchild_branch->add_leaves($grandchild_leaf_1, $grandchild_leaf_2, $grandchild_leaf_3);

        $parent_branch->add_branches($child_branch);
        $child_branch->add_branches($grandchild_branch);

        // Test get_id()
        $this->assertEquals('1', $parent_branch->get_id());
        $this->assertEquals('2', $child_branch->get_id());
        $this->assertEquals('3', $grandchild_branch->get_id());
        $this->assertEquals('3-1', $grandchild_leaf_1->get_id());
        $this->assertEquals('3-2', $grandchild_leaf_2->get_id());
        $this->assertEquals('3-3', $grandchild_leaf_3->get_id());

        // Test get_label()
        $this->assertEquals('Parent Branch', $parent_branch->get_label());
        $this->assertEquals('Child Branch', $child_branch->get_label());
        $this->assertEquals('Grandchild Branch', $grandchild_branch->get_label());
        $this->assertEquals('Grandchild Leaf 1', $grandchild_leaf_1->get_label());
        $this->assertEquals('Grandchild Leaf 2', $grandchild_leaf_2->get_label());
        $this->assertEquals('Grandchild Leaf 3', $grandchild_leaf_3->get_label());

        // Test get_branches()
        $this->assertEquals([$child_branch], $parent_branch->get_branches());
        $this->assertEquals([$grandchild_branch], $child_branch->get_branches());
        $this->assertEquals([], $grandchild_branch->get_branches());

        // Test has_branches()
        $this->assertTrue($parent_branch->has_branches());
        $this->assertTrue($child_branch->has_branches());
        $this->assertFalse($grandchild_branch->has_branches());

        // Test get_leaves()
        $this->assertEquals([], $parent_branch->get_leaves());
        $this->assertEquals([], $child_branch->get_leaves());
        $this->assertEquals([$grandchild_leaf_1, $grandchild_leaf_2, $grandchild_leaf_3], $grandchild_branch->get_leaves());

        // Test is_root()
        $this->assertTrue($parent_branch->is_root());
        $this->assertFalse($child_branch->is_root());
        $this->assertFalse($grandchild_branch->is_root());

        // Test that the root is always $parent_branch
        $this->assertEquals($parent_branch, $parent_branch->get_root());
        $this->assertEquals($parent_branch, $child_branch->get_root());
        $this->assertEquals($parent_branch, $grandchild_branch->get_root());
    }

}
