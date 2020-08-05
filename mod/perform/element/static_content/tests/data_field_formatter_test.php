<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Angela Kuznetsova <angela.kuznetsova@totaralearning.com>
 * @package performelement_static_element
 */

use core\format;
use mod_perform\formatter\activity\element_data_field_formatter;
use mod_perform\models\activity\element_plugin;
use performelement_static_content\formatter\data_field_formatter;
use performelement_static_content\static_content;

/**
 * @group perform
 */
class performelement_static_content_data_field_formatter_testcase extends advanced_testcase {

    public function test_format() {
        global $CFG;
        require_once($CFG->libdir . '/filterlib.php');

        /** @var static_content $plugin */
        $plugin = element_plugin::load_by_plugin('static_content');

        // Initiate through main class
        $formatter_string = element_data_field_formatter::for_plugin($plugin);
        $this->assertEquals(data_field_formatter::class, $formatter_string);

        $context = context_system::instance();
        $format = format::FORMAT_PLAIN;

        $formatter = new data_field_formatter($format, $context);

        $data['textValue'] = 'I see trees of green, red roses too';
        $data = json_encode($data);
        $result = $formatter->format($data);
        $this->assertEquals($data, $result);
    }
}