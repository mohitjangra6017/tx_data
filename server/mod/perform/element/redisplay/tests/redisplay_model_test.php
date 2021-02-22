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

use core\orm\collection;
use performelement_redisplay\entity\element_redisplay_relationship as element_redisplay_relationship_entity;
use performelement_redisplay\models\element_redisplay_relationship;

require_once(__DIR__ . '/redisplay_test.php');

/**
 * @group perform
 * @group perform_element
 */
class performelement_redisplay_element_relationship_model_testcase extends redisplay_testcase {

    public function test_create() {
        $this->setAdminUser();

        /** @var \mod_perform\testing\generator $perform_generator */
        $perform_generator = \mod_perform\testing\generator::instance();

        $source_activity = $perform_generator->create_activity_in_container(['activity_name' => 'activity']);
        $source_section = $perform_generator->create_section($source_activity, ['title' => 'section']);

        $element = $perform_generator->create_element();
        $source_section_element = $perform_generator->create_section_element($source_section, $element);

        $redisplay_data = sprintf('{"sectionElementId": "%s" }', $source_section_element->id);
        $redisplay_element = $perform_generator->create_element([
            'plugin_name' => 'redisplay',
            'data' => $redisplay_data,
        ]);

        $element_redisplay_relationship = element_redisplay_relationship_entity::repository()
            ->where('redisplay_element_id', $redisplay_element->id)
            ->get()->first();

        $this->assertEquals($source_activity->id, $element_redisplay_relationship->source_activity_id);
        $this->assertEquals($source_section_element->id, $element_redisplay_relationship->source_section_element_id);
        $this->assertEquals($redisplay_element->id, $element_redisplay_relationship->redisplay_element_id);
    }

    public function test_update() {
        $data = $this->create_test_data();

        /** @var \mod_perform\testing\generator $perform_generator */
        $perform_generator = \mod_perform\testing\generator::instance();

        $activity = $perform_generator->create_activity_in_container(['activity_name' => 'activity']);
        $section = $perform_generator->create_section($activity, ['title' => 'section']);
        $element = $perform_generator->create_element();
        $section_element = $perform_generator->create_section_element($section, $element);

        element_redisplay_relationship::update($activity->id, $section_element->id, $data->element3->id);
        $element_redisplay_relationship = element_redisplay_relationship::load_by_id($data->element_redisplay_relationship3->id);

        $this->assertEquals($activity->id, $element_redisplay_relationship->source_activity_id);
        $this->assertEquals($section_element->id, $element_redisplay_relationship->source_section_element_id);
    }

    public function test_get_sections_by_source_activity_id() {
        $data = $this->create_test_data();

        $sections = element_redisplay_relationship::get_sections_by_source_activity_id($data->activity1->id);

        $this->assertCount(2, $sections);

        $this->assert_activity_sections_data($data, $sections);
    }

    public function test_get_sections_by_source_section_id() {
        $data = $this->create_test_data();

        $sections = element_redisplay_relationship::get_sections_by_source_section_id($data->section1->id);

        $this->assertCount(2, $sections);

        $this->assert_activity_sections_data($data, $sections);
    }

    public function test_get_sections_by_source_section_element_id() {
        $data = $this->create_test_data();

        $sections = element_redisplay_relationship::get_sections_by_source_section_element_id($data->section_element1->id);

        $this->assertCount(2, $sections);

        $this->assert_activity_sections_data($data, $sections);
    }

    /**
     * Assert returns activity sections with correct order
     *
     * @param stdClass $data
     * @param collection $sections
     */
    private function assert_activity_sections_data(stdClass $data, collection $sections): void {
        $this->assertNotContains($data->section1->title, $sections->pluck('title'));
        $first_section = $sections->shift();
        $this->assertEquals($data->section2->title, $first_section->get_display_title());
        $this->assertEquals($data->activity2->name, $first_section->activity->name);

        $second_section = $sections->shift();
        $this->assertEquals($data->section3->title, $second_section->get_display_title());
        $this->assertEquals($data->activity3->name, $second_section->activity->name);
    }
}