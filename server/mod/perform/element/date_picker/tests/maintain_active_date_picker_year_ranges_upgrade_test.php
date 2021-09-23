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
 * @author Nathan Lewis <nathan.lewis@totaralearning.com>
 * @package mod_perform
 */

use core\collection;
use mod_perform\models\activity\element;
use mod_perform\models\activity\element_plugin;
use mod_perform\state\activity\active;
use mod_perform\state\activity\activity_state;
use mod_perform\state\activity\draft;
use performelement_date_picker\answer_required_error;
use performelement_date_picker\date_picker;
use performelement_date_picker\date_iso_required_error;
use performelement_date_picker\year_outside_range;
use performelement_date_picker\invalid_date_error;

/**
 * @group perform
 * @group perform_element
 */
class performelement_date_picker_maintain_active_date_picker_year_ranges_upgrade_test extends \core_phpunit\testcase {

    public function test_upgrade(): void {
        self::setAdminUser();

        $generator = \mod_perform\testing\generator::instance();

        // Null data should not actually happen in the real world.
        $active_null_data = $generator->create_element(
            ['plugin_name' => date_picker::get_plugin_name(), 'title' => 'active date picker null data', 'is_required' => true, 'data' => null]
        );
        $this->add_to_activity($active_null_data, active::get_code());

        $active_empty_data = $generator->create_element(
            ['plugin_name' => date_picker::get_plugin_name(), 'title' => 'active date picker empty data', 'is_required' => true, 'data' => '{}']
        );
        $this->add_to_activity($active_empty_data, active::get_code());

        $draft = $generator->create_element(
            ['plugin_name' => date_picker::get_plugin_name(), 'title' => 'draft date picker empty data', 'is_required' => true, 'data' => '{}']
        );
        $this->add_to_activity($draft, draft::get_code());

        require_once __DIR__ . '/../../../db/upgradelib.php';
        mod_perform_maintain_active_date_picker_year_ranges();

        $active_null_data = element::load_by_id($active_null_data->id);
        self::assertEquals([
            'yearRangeStart' => 1900,
            'yearRangeEnd' => 2050,
        ], json_decode($active_null_data->data, true, 512, JSON_THROW_ON_ERROR));

        $active_empty_data = element::load_by_id($active_empty_data->id);
        self::assertEquals([
            'yearRangeStart' => 1900,
            'yearRangeEnd' => 2050,
        ], json_decode($active_null_data->data, true, 512, JSON_THROW_ON_ERROR));

        $draft = element::load_by_id($draft->id);
        self::assertEquals('{}', $draft->data);
    }

    private function add_to_activity(element $element, int $activity_state): void {
        $generator = \mod_perform\testing\generator::instance();

        $activity = $generator->create_activity_in_container(['activity_status' => $activity_state]);
        $section = $generator->create_section($activity, ['title' => 'Part one']);
        $generator->create_section_element($section, $element);
    }

}