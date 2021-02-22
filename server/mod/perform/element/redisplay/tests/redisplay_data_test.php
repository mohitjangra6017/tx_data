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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Kunle Odusan <kunle.odusan@totaralearning.com>
 */

use mod_perform\models\activity\activity;
use mod_perform\models\activity\element;
use mod_perform\models\activity\section;
use mod_perform\models\activity\section_element;
use mod_perform\state\activity\draft;

/**
 * @group perform
 * @group perform_element
 */
class performelement_redisplay_redisplay_data_testcase extends advanced_testcase {

    /**
     * @var activity
    */
    private $activity;

    /**
     * @var array
     */
    private $redisplay_extra_data = [
        'activityName',
        'activityStatus',
        'elementTitle',
        'elementPluginName',
        'relationships',
    ];

    public function test_redisplay_element_adds_extra_info_to_data() {
        $data = $this->create_test_data();

        /** @var $other_section_element section_element*/
        $other_section_element = $data['other'];
        $this->assertNull($other_section_element->element->data);

        /** @var $redisplay_section_element section_element*/
        $redisplay_section_element = $data['redisplay'];
        $redisplay_data = json_decode($redisplay_section_element->element->get_data(), true);

        $this->assert_extra_fields_exist($redisplay_data);

        $this->assertEquals($this->activity->name, $redisplay_data['activityName']);
        $this->assertEquals(draft::get_display_name(), $redisplay_data['activityStatus']);
        $this->assertEquals('Projected performance', $redisplay_data['elementTitle']);
        $this->assertNotEmpty($redisplay_data['elementPluginName']);
        $this->assertStringContainsString('No responding relationships added yet', $redisplay_data['relationships']);

        $this->assert_relationship_string_is_anonymized($redisplay_section_element);
    }

    private function assert_extra_fields_exist(array $redisplay_data) {
        foreach ($this->redisplay_extra_data as $redisplay_extra_datum) {
            $this->assertArrayHasKey($redisplay_extra_datum, $redisplay_data);
        }
    }

    private function assert_relationship_string_is_anonymized(section_element $redisplay_section_element) {
        $this->activity->set_anonymous_setting(true);
        $this->activity->update();

        $redisplay_data = json_decode($redisplay_section_element->element->get_data(), true);
        $this->assert_extra_fields_exist($redisplay_data);
        $this->assertEquals(
            get_string('responses_from_anonymous_relationships', 'performelement_redisplay'),
            $redisplay_data['relationships']
        );
    }

    public function tearDown(): void {
        $this->activity = null;
        $this->redisplay_extra_data = null;
    }

    private function create_test_data(): array {
        $this->setAdminUser();

        /** @var $perform_generator \mod_perform\testing\generator*/
        $perform_generator = \mod_perform\testing\generator::instance();

        $this->activity = $perform_generator->create_activity_in_container(
            [
                'create_section' => false,
                'activity_name' => 'My redisplay test activity',
                'activity_status' => draft::get_code()
            ]
        );

        $section_1 = section::create($this->activity, 'First section');

        $short_text_element_1 = element::create(
            $this->activity->get_context(),
            'short_text',
            'Projected performance',
            'A2 Element'
        );
        $section_element_1 = $section_1->get_section_element_manager()->add_element_after($short_text_element_1);
        $redisplay_section_element = $section_1->get_section_element_manager()->add_element_after(
            $this->get_redisplay_data($section_element_1->id, 'Performance analysis')
        );

        return [
            'redisplay' => $redisplay_section_element,
            'other' => $section_element_1,
        ];
    }

    private function get_redisplay_data($section_element_id, $name): element {
        return element::create(
            $this->activity->get_context(),
            'redisplay',
            $name,
            'A2 Element',
            json_encode([
                'sectionElementId' => $section_element_id,
            ])
        );
    }
}