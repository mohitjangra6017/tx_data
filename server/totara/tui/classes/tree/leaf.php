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
 * Represents a selectable item within a tree filter structure.
 * A recursive structure for use for the Tree filter vue component.
 *
 * @see client/component/tui/src/components/tree/TreeBranch.vue
 */
class leaf {

    /**
     * ID is the value of the selected item in the front end.
     *
     * @var string
     */
    private $id;

    /**
     * The label text that can be viewed & clicked in the front end.
     *
     * @var string
     */
    private $label;

    /**
     * @var branch
     */
    private $parent;

    /**
     * leaf constructor.
     * @param string $id
     * @param string $label
     */
    public function __construct(string $id, string $label) {
        $this->id = $id;
        $this->label = $label;
    }

    /**
     * @param branch $tree_node
     */
    final protected function set_parent(branch $tree_node): void {
        $this->parent = $tree_node;
    }

    /**
     * Get the tree branch that is the parent of this tree node.
     *
     * @return branch|null
     */
    final public function get_parent(): ?branch {
        return $this->parent;
    }

    /**
     * Get the unique identifier of this tree node.
     *
     * @return string
     */
    final public function get_id(): string {
        return $this->id;
    }

    /**
     * Get the string to output when displaying this tree node.
     *
     * @return string
     */
    final public function get_label(): string {
        return $this->label;
    }

}
