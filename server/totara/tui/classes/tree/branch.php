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

namespace totara_tui\tree;

/**
 * Represents an expandable category within a tree filter structure.
 *
 * @see client/component/tui/src/components/tree/TreeBranch.vue
 */
class branch extends leaf {

    /**
     * @var leaf[]
     */
    private $leaves = [];

    /**
     * @var branch[]
     */
    private $branches = [];

    /**
     * Get the very top level tree branch node in the tree.
     *
     * @return branch
     */
    final public function get_root(): branch {
        if ($this->is_root()) {
            return $this;
        }

        $branch = $this->get_parent();
        while (!$branch->is_root()) {
            $branch = $branch->get_parent();
        }
        return $branch;
    }

    /**
     * Is this tree branch the root node in the tree?
     *
     * @return bool
     */
    final public function is_root(): bool {
        return $this->get_parent() === null;
    }

    /**
     * Get the leaves of this branch.
     *
     * @return self[]
     */
    final public function get_leaves(): array {
        return $this->leaves;
    }

    /**
     * Add leaves (i.e. content items) to this branch.
     *
     * @param leaf ...$leaves
     * @return $this
     */
    final public function add_leaves(leaf ...$leaves): self {
        foreach ($leaves as $leaf) {
            $leaf->set_parent($this);
            $this->leaves[] = $leaf;
        }

        return $this;
    }

    /**
     * Get the child branches of this branch.
     *
     * @return branch[]
     */
    final public function get_branches(): array {
        return $this->branches;
    }

    /**
     * Does this branch have any child branches?
     *
     * @return bool
     */
    final public function has_branches(): bool {
        return !empty($this->branches);
    }

    /**
     * Add branches (i.e. children) to this branch.
     *
     * @param branch ...$branches
     * @return $this
     */
    final public function add_branches(branch ...$branches): self {
        foreach ($branches as $branch) {
            $branch->set_parent($this);
            $this->branches[] = $branch;
        }

        return $this;
    }

}
