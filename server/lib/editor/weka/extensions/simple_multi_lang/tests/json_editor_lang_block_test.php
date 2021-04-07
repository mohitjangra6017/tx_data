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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package weka_simple_multi_lang
 */

use core\json_editor\formatter\default_formatter;
use core\json_editor\node\heading;
use core\json_editor\node\paragraph;
use core_phpunit\testcase;
use weka_simple_multi_lang\json_editor\node\lang_block;

class weka_simple_multi_lang_json_editor_lang_block_testcase extends testcase {
    /**
     * @return void
     */
    public function test_validate_schema(): void {
        self::assertTrue(
            lang_block::validate_schema([
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'en',
                    'direction' => 'ltr'
                ],
                'content' => [
                    paragraph::create_json_node_from_text('boom')
                ]
            ])
        );

        self::assertTrue(
            lang_block::validate_schema([
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'en',
                    'direction' => 'ltr',
                    'siblings_count' => 2
                ],
                'content' => [
                    paragraph::create_json_node_from_text('boom'),
                    paragraph::create_json_node_from_text('boom')
                ]
            ])
        );

        self::assertFalse(
            lang_block::validate_schema([
                'attrs' => [
                    'lang' => 'en',
                    'direction' => 'ltr'
                ],
                'content' => [
                    paragraph::create_json_node_from_text('boom')
                ]
            ])
        );

        self::assertFalse(
            lang_block::validate_schema([
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'direction' => 'ltr'
                ],
                'content' => [
                    paragraph::create_json_node_from_text('boom')
                ]
            ])
        );

        self::assertFalse(
            lang_block::validate_schema([
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'en',
                ],
                'content' => [
                    paragraph::create_json_node_from_text('boom')
                ]
            ])
        );

        self::assertFalse(
            lang_block::validate_schema([
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'en',
                    'direction' => 'ttr',
                ],
                'content' => [
                    paragraph::create_json_node_from_text('boom')
                ]
            ])
        );

        self::assertTrue(
            lang_block::validate_schema([
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'en',
                    'direction' => 'rtl',
                ],
                'content' => [
                    paragraph::create_json_node_from_text('boom')
                ]
            ])
        );

        self::assertFalse(
            lang_block::validate_schema([
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'en',
                    'direction' => 'rtl',
                ],
            ])
        );

        self::assertFalse(
            lang_block::validate_schema([
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'ennnninni',
                    'direction' => 'rtl',
                ],
                'content' => [
                    paragraph::create_json_node_from_text('boom')
                ]
            ])
        );

        self::resetDebugging();
    }

    /**
     * @return void
     */
    public function test_clean_raw_node(): void {
        self::assertEquals(
            [
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'en',
                    'direction' => 'ltr'
                ],
                'content' => [
                    [
                        'type' => 'paragraph',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => 'Boom',
                                'marks' => []
                            ]
                        ]
                    ]
                ]
            ],
            lang_block::clean_raw_node([
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'en',
                    'direction' => 'ltr'
                ],
                'content' => [
                    paragraph::create_json_node_from_text('Boom')
                ]
            ])
        );

        // This is to make sure that we do not sanitize the lang as PARAM_LANG,
        // because PARAM_LANG will be checked against system installed PARAM_LANG.
        self::assertEquals(
            [
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'enddw',
                    'direction' => 'ltr'
                ],
                'content' => [
                    [
                        'type' => 'paragraph',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => 'Boom',
                                'marks' => []
                            ]
                        ]
                    ]
                ]
            ],
            lang_block::clean_raw_node([
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'enddw',
                    'direction' => 'ltr'
                ],
                'content' => [
                    paragraph::create_json_node_from_text('Boom')
                ]
            ])
        );

        self::assertNull(
            lang_block::clean_raw_node([
                'type' => '<script>alert("cc")</script>',
                'attrs' => [
                    'lang' => 'en',
                    'direction' => 'ltr'
                ],
                'content' => [
                    paragraph::create_json_node_from_text('Boom')
                ]
            ])
        );
    }

    /**
     * @return void
     */
    public function test_render_lang_block_as_html(): void {
        $raw_node = lang_block::create_raw_json_node(
            'en',
            "<script>alert('Hello world')</script>"
        );

        $node = lang_block::from_node($raw_node);
        $formatter = new default_formatter();

        $html = $node->to_html($formatter);
        self::assertEquals(
            /** @lang text */
            '<p>&lt;script&gt;alert(&#039;Hello world&#039;)&lt;/script&gt;</p>',
            $html
        );
    }

    public function test_render_lang_block_as_html_with_more_than_one_siblings_count(): void {
        $raw_node = lang_block::create_raw_json_node(
            'en',
            'This is random text'
        );

        $raw_node['attrs']['siblings_count'] = 2;
        $node = lang_block::from_node($raw_node);

        $formatter = new default_formatter();
        $html = $node->to_html($formatter);

        self::assertEquals(
            /** @lang text */
            '<span class="multilang" lang="en"><p>This is random text</p></span>',
            $html
        );
    }

    /**
     * @return void
     */
    public function test_validation_with_headings_in_lang_block(): void {
        self::assertTrue(
            lang_block::validate_schema([
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'en',
                    'direction' => 'ltr',
                    'siblings_count' => 1,
                ],
                'content' => [
                    heading::create_raw_node('Hello world')
                ],
            ])
        );

        self::assertFalse(
            lang_block::validate_schema([
                'type' => 'weka_simple_multi_lang_lang_block',
                'attrs' => [
                    'lang' => 'en',
                    'direction' => 'ltr',
                    'siblings_count' => 1
                ],
                'content' => [
                    [
                        'type' => 'heading',
                        'attrs' => [
                            'level' => 1,
                            'other_random_attr' => 'sdwq'
                        ],
                        'content' => [
                            [
                                'type' => 'text',
                                'randome_text' => 'sss'
                            ]
                        ]
                    ]
                ]
            ])
        );

        self::resetDebugging();
    }
}