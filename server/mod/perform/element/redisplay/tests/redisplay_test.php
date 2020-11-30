<?php
/**
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Marco Song <marco.song@totaralearning.com>
 * @package mod_perform
 */

use performelement_redisplay\models\element_redisplay_relationship;

/**
 * @group perform
 * @group perform_element
 */
abstract class redisplay_testcase extends advanced_testcase {
    protected function create_test_data() {
        $this->setAdminUser();

        $data = new stdClass();

        /** @var mod_perform_generator $perform_generator */
        $perform_generator = $this->getDataGenerator()->get_plugin_generator('mod_perform');

        /*
         * activity1                    [SOURCE ACTIVITY]
         * ** section1                  [SOURCE SECTION]
         *    ** element1(short-text)   [SOURCE SECTION ELEMENT]
         *
         * activity2
         * ** section2
         *    ** element2(redisplay) --> element1
         *
         * activity3
         * ** section3
         *    ** element3(redisplay) --> element1
         */
        $data->activity1 = $perform_generator->create_activity_in_container(['activity_name' => 'activity1']);
        $data->activity2 = $perform_generator->create_activity_in_container(['activity_name' => 'activity2']);
        $data->activity3 = $perform_generator->create_activity_in_container(['activity_name' => 'activity3']);

        $data->section1 = $perform_generator->create_section($data->activity1, ['title' => 'section1']);
        $data->section2 = $perform_generator->create_section($data->activity2, ['title' => 'section2']);
        $data->section3 = $perform_generator->create_section($data->activity3, ['title' => 'section3']);

        $data->element1 = $perform_generator->create_element();
        $data->element2 = $perform_generator->create_element(['plugin_name' => 'redisplay']);
        $data->element3 = $perform_generator->create_element(['plugin_name' => 'redisplay']);

        $data->section_element1 = $perform_generator->create_section_element($data->section1, $data->element1);
        $data->section_element2 = $perform_generator->create_section_element($data->section2, $data->element2);
        $data->section_element3 = $perform_generator->create_section_element($data->section3, $data->element3);

        $data->element_redisplay_relationship2 = element_redisplay_relationship::create(
            $data->activity1->id, $data->section_element1->id, $data->element2->id
        );
        $data->element_redisplay_relationship3 = element_redisplay_relationship::create(
            $data->activity1->id, $data->section_element1->id, $data->element3->id
        );

        return $data;
    }
}