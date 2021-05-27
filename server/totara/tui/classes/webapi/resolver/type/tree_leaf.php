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

namespace totara_tui\webapi\resolver\type;

use coding_exception;
use context_system;
use core\format;
use core\webapi\execution_context;
use core\webapi\formatter\field\string_field_formatter;
use core\webapi\type_resolver;
use totara_tui\tree\leaf;

final class tree_leaf implements type_resolver {

    /**
     * @param string $field
     * @param leaf $tree_leaf
     * @param array $args
     * @param execution_context $ec
     *
     * @return mixed
     */
    public static function resolve(string $field, $tree_leaf, array $args, execution_context $ec) {
        if (!$tree_leaf instanceof leaf) {
            throw new coding_exception('Expected an instance of leaf');
        }

        switch ($field) {
            case 'id':
                return $tree_leaf->get_id();
            case 'label':
                // Use the context if set, but it is fine to fall back to the system context.
                $context = $ec->has_relevant_context() ? $ec->get_relevant_context() : context_system::instance();
                // Force the format to be plain - tree labels can only be simple text.
                $formatter = new string_field_formatter(format::FORMAT_PLAIN, $context);
                return $formatter->format($tree_leaf->get_label());
            default:
                throw new coding_exception("Unsupported field: $field");
        }
    }

}
