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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package weka_simple_multi_lang
 */
namespace weka_simple_multi_lang\json_editor\node;

use coding_exception;
use core\json_editor\formatter\formatter;
use core\json_editor\helper\node_helper;
use core\json_editor\node\abstraction\block_node;
use core\json_editor\node\heading;
use core\json_editor\node\node;
use core\json_editor\node\paragraph;
use core\json_editor\schema;
use html_writer;

/**
 * A single block node for multi lang block.
 */
class lang_block extends node implements block_node {
    /**
     * @var string
     */
    public const RTL = 'rtl';

    /**
     * @var string
     */
    public const LTR = 'ltr';

    /**
     * @var string
     */
    public const MAX_LANG_LENGTH = 5;

    /**
     * The attribute langs
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $direction;

    /**
     * An array of paragraph | heading nodes.
     * @var array[]
     */
    private $contents;

    /**
     * @param formatter $formatter
     * @return string
     */
    public function to_html(formatter $formatter): string {
        if (empty($this->contents)) {
            return html_writer::span('', 'multilang', ['lang' => $this->lang]);
        }

        $content = '';
        foreach ($this->contents as $single_node) {
            $content .= $formatter->print_node($single_node, formatter::HTML);
        }

        return html_writer::span(
            $content,
            "multilang",
            ['lang' => $this->lang]
        );
    }

    /**
     * There is no supports for multi lang in simple text yet. And this implementation
     * is only about outputing the content to text.
     *
     * @param formatter $formatter
     * @return string
     */
    public function to_text(formatter $formatter): string {
        if (empty($this->contents)) {
            return "```{$this->lang}```";
        }

        $content = '';
        foreach ($this->contents as $single_node) {
            $content .= $formatter->print_node($single_node, formatter::TEXT) . ' ';
        }

        return "```{$this->lang}\n{$content}```";
    }

    /**
     * @return string
     */
    protected static function do_get_type(): string {
        return 'lang_block';
    }

    /**
     * @param array $node
     * @return lang_block|node
     */
    public static function from_node(array $node): node {
        /** @var lang_block $lang_block */
        $lang_block = parent::from_node($node);
        $lang_block->lang = $node['attrs']['lang'];
        $lang_block->direction = $node['attrs']['direction'];
        $lang_block->contents = $node['content'];

        return $lang_block;
    }

    /**
     * @param array $raw_node
     * @return bool
     */
    public static function validate_schema(array $raw_node): bool {
        if (!node_helper::check_keys_match_against_data($raw_node, ['content', 'type', 'attrs'])) {
            return false;
        }

        $attrs = $raw_node['attrs'];
        if (!node_helper::check_keys_match_against_data($attrs, ['lang', 'direction'], ['siblings_count'])) {
            return false;
        } else if (strlen($attrs['lang']) > static::MAX_LANG_LENGTH) {
            // We can only accept the 5 characters as max length of lang keyword.
            return false;
        }

        if (!in_array($attrs['direction'], [static::LTR, static::RTL])) {
            return false;
        }

        $content_nodes = $raw_node['content'];
        if (!empty($content_nodes)) {
            foreach ($content_nodes as $single_node) {
                if (!isset($single_node['type'])) {
                    return false;
                }

                if (paragraph::get_type() === $single_node['type']) {
                    if (!paragraph::validate_schema($single_node)) {
                        return false;
                    }

                    continue;
                }

                if (heading::get_type() === $single_node['type']) {
                    if (!heading::validate_schema($single_node)) {
                        return false;
                    }

                    continue;
                }

                return false;
            }
        }

        return true;
    }

    /**
     * @param array $raw_node
     * @return array
     */
    public static function sanitize_raw_node(array $raw_node): array {
        $raw_node = parent::sanitize_raw_node($raw_node);

        // Trim down the lang value, as it is invalid
        $lang = $raw_node['attrs']['lang'];
        $lang = clean_string($lang);
        $raw_node['attrs']['lang'] = substr($lang, 0, static::MAX_LANG_LENGTH);

        // Santize the direction.
        $direction = $raw_node['attrs']['direction'];
        if (!in_array($direction, [static::LTR, static::RTL])) {
            // Default the direction to LTR.
            $raw_node['attrs']['direction'] = static::LTR;
        }

        $content_nodes = $raw_node['content'];
        foreach ($content_nodes as $i => $single_node) {
            if (!isset($single_node['type'])) {
                throw new coding_exception("Invalid single node does not have attribute 'type'");
            }

            if (paragraph::get_type() === $single_node['type']) {
                $raw_node['content'][$i] = paragraph::sanitize_raw_node($single_node);
                continue;
            }

            if (heading::get_type() === $single_node['type']) {
                $raw_node['content'][$i] = heading::sanitize_raw_node($single_node);
                continue;
            }

            throw new coding_exception("The node within lang block is not supported");
        }

        return $raw_node;
    }

    /**
     * @param array $raw_node
     * @return array|null
     */
    public static function clean_raw_node(array $raw_node): ?array {
        $cleaned_raw_node = parent::clean_raw_node($raw_node);
        if (null === $cleaned_raw_node) {
            return null;
        }

        // Cleaning the content of the raw node.
        $content = $cleaned_raw_node['content'] ?? [];
        $schema = schema::instance();

        foreach ($content as $i => $single_node) {
            if (!isset($single_node['type'])) {
                throw new coding_exception("Invalid node structure", static::get_type());
            }

            $node_class = $schema->get_node_classname($single_node['type']);
            if (null === $node_class) {
                debugging("Cannot find node class for node type '{$single_node['type']}'", DEBUG_DEVELOPER);
                continue;
            }

            /**
             * @see node::clean_raw_node()
             * @var array $cleaned_single_node
             */
            $cleaned_single_node = call_user_func([$node_class, 'clean_raw_node'], $single_node);
            if (null === $cleaned_single_node) {
                return null;
            }

            $cleaned_raw_node['content'][$i] = $cleaned_single_node;
        }

        // Clean the attribute of the raw node. Note that we do not want to use clean with
        // PARAM_LANG because that would strip out the language value from the node.
        // The rendering will make sure that the invalid lang pack is stripped,
        // hence we need to keep it for the record.
        $lang = $cleaned_raw_node['attrs']['lang'];
        $cleaned_raw_node['attrs']['lang'] = clean_param($lang, PARAM_ALPHAEXT);

        $direction = $cleaned_raw_node['attrs']['direction'];
        if (!in_array($direction, [static::LTR, static::RTL])) {
            // Default to LTR direction, if it is invalid direction.
            $cleaned_raw_node['attrs']['direction'] = static::LTR;
        }

        if (isset($cleaned_raw_node['attrs']['siblings_count'])) {
            $cleaned_raw_node['attrs']['siblings_count'] = clean_param(
                $cleaned_raw_node['attrs']['siblings_count'],
                PARAM_INT
            );
        }

        return $cleaned_raw_node;
    }

    /**
     * This function is mainly for phpunit tests, please do not use it esle where outside unit test environment.
     *
     * @param string $lang
     * @param string $content
     * @param string $direction
     *
     * @return array
     */
    public static function create_raw_json_node(string $lang, string $content, string $direction = self::LTR): array {
        if (!defined('PHPUNIT_TEST') || !PHPUNIT_TEST) {
            $fn = __FUNCTION__;
            throw new coding_exception("The function {$fn} is only available for phpunit tests");
        }

        $paragraphs = [$content];
        if (false !== strpos($content, "\n")) {
            $paragraphs = explode("\n", $content);
        }

        return [
            'type' => static::get_type(),
            'attrs' => [
                'lang' => $lang,
                'direction' => $direction,
                'siblings_count' => 1
            ],
            'content' => array_map(
                function (string $paragraph): array {
                    return paragraph::create_json_node_from_text($paragraph);
                },
                $paragraphs
            ),
        ];
    }
}