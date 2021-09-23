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

use core_phpunit\testcase;
use mod_perform\models\activity\element;
use mod_perform\state\activity\active;
use mod_perform\state\activity\draft;
use performelement_date_picker\date_picker;

/**
 * @group perform
 * @group perform_element
 */
class performelement_date_picker_upgrade_test extends testcase {

    public function test_upgrade(): void {
        self::setAdminUser();

        $generator = \mod_perform\testing\generator::instance();

        $active_empty_data = $generator->create_element(
            [
                'plugin_name' => date_picker::get_plugin_name(),
                'title' => 'active date picker empty data',
                'is_required' => true,
                'data' => '{}'
            ]
        );
        $this->add_to_activity($active_empty_data, active::get_code());

        $draft = $generator->create_element(
            [
                'plugin_name' => date_picker::get_plugin_name(),
                'title' => 'draft date picker empty data',
                'is_required' => true,
                'data' => '{}'
            ]
        );
        $this->add_to_activity($draft, draft::get_code());

        $active_default_data = $generator->create_element(
            [
                'plugin_name' => date_picker::get_plugin_name(),
                'title' => 'active date picker empty data',
                'is_required' => true,
                'data' => json_encode(['yearRangeStart' => null, 'yearRangeEnd' => null]),
            ]
        );
        $this->add_to_activity($active_default_data, active::get_code());

        $active_empty_data = element::load_by_id($active_empty_data->id);
        self::assertEquals([
            'yearRangeStart' => 1900,
            'yearRangeEnd' => 2050,
        ], json_decode($active_empty_data->data, true, 512, JSON_THROW_ON_ERROR));

        $active_default_data = element::load_by_id($active_default_data->id);
        self::assertEquals([
            'yearRangeStart' => null,
            'yearRangeEnd' => null,
        ], json_decode($active_default_data->data, true, 512, JSON_THROW_ON_ERROR));

        $draft = element::load_by_id($draft->id);
        self::assertEquals([
            'yearRangeStart' => 1900,
            'yearRangeEnd' => 2050,
        ], json_decode($draft->data, true, 512, JSON_THROW_ON_ERROR));
    }

    private function add_to_activity(element $element, int $activity_state): void {
        $generator = \mod_perform\testing\generator::instance();

        $activity = $generator->create_activity_in_container(['activity_status' => $activity_state]);
        $section = $generator->create_section($activity, ['title' => 'Part one']);
        $generator->create_section_element($section, $element);
    }

}