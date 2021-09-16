<?php
/**
 * This file is part of Totara Core
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
 * @package mod_contentmarketplace
 */
use core_phpunit\testcase;
use mod_contentmarketplace\model\content_marketplace;
use mod_contentmarketplace\output\content_marketplace_logo;

/**
 * @group totara_contentmarketplace
 */
class mod_contentmarketplace_content_marketplace_logo_testcase extends testcase {
    /**
     * @return void
     */
    public function test_render_template(): void {
        global $OUTPUT;
        $generator = self::getDataGenerator();
        $course = $generator->create_course();

        $cm = $generator->create_module('contentmarketplace', ['course' => $course->id]);
        $content_marketplace = content_marketplace::from_course_module_id($cm->cmid);
        $learning_object = $content_marketplace->get_learning_object();

        $template = content_marketplace_logo::create_from_model($content_marketplace);
        $expected = sprintf(
            /** @lang text */'<img class="%s" alt="%s" src="%s" />',
            'tw-mod-contentmarketplace__logo',
            get_string('marketplace_logo', 'mod_contentmarketplace'),
            $learning_object::get_marketplace_image_url()
        );

        // Remove space to make the assertion easier.
        $content = $OUTPUT->render($template);
        $content = preg_replace('/\s+/', ' ', $content);
        self::assertEquals(trim($expected), trim($content));
    }
}