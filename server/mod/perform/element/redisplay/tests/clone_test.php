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
use mod_perform\entity\activity\section;
use mod_perform\entity\activity\section_element;
use mod_perform\models\activity\activity;
use performelement_redisplay\entity\element_redisplay_relationship as element_redisplay_relationship_entity;
use performelement_redisplay\models\element_redisplay_relationship;

/**
 * @group perform
 * @group perform_element
 */
class clone_testcase extends advanced_testcase {

    public function test_clone() {
        $this->setAdminUser();

        /** @var \mod_perform\testing\generator $perform_generator */
        $perform_generator = \mod_perform\testing\generator::instance();

        /*
         * activity1                    [SOURCE ACTIVITY]
         * ** section1                  [SOURCE SECTION]
         *    ** element1(short-text)   [SOURCE SECTION ELEMENT]
         *
         * activity2
         * ** section2
         *    ** element2(redisplay) --> element1
         */
        $activity1 = $perform_generator->create_activity_in_container(['activity_name' => 'activity1']);
        $section1 = $perform_generator->create_section($activity1, ['title' => 'section1']);
        $element1 = $perform_generator->create_element();
        $section_element1 = $perform_generator->create_section_element($section1, $element1);

        $redisplay_data = [
            'activityId' => $activity1->id,
            'sectionElementId' => $section_element1->id,
        ];

        $activity2 = $perform_generator->create_activity_in_container(['activity_name' => 'activity2']);
        $section2 = $perform_generator->create_section($activity2, ['title' => 'section2']);
        $element2 = $perform_generator->create_element(['plugin_name' => 'redisplay', 'data' => json_encode($redisplay_data)]);
        $section_element2 = $perform_generator->create_section_element($section2, $element2);

        element_redisplay_relationship::create($activity1->id, $section_element1->id, $element2->id);

        $redisplay_relationships = $this->load_relationships_by_source_activity_id($activity1->id);

        // Only one redisplay relationship exists before cloning
        $this->assertCount(1, $redisplay_relationships);

        // Clone the activity.
        activity::load_by_id($activity2->id)->clone();

        $redisplay_relationships = $this->load_relationships_by_source_activity_id($activity1->id);
        $this->assertCount(2, $redisplay_relationships);

        $relationship = $redisplay_relationships->first();
        $cloned_relationship = $redisplay_relationships->last();

        $this->assertEquals($relationship->source_activity_id, $cloned_relationship->source_activity_id);
        $this->assertEquals($relationship->source_section_element_id, $cloned_relationship->source_section_element_id);
        $this->assertNotEquals($relationship->redisplay_element_id, $cloned_relationship->redisplay_element_id);
    }

    /**
     * There is a special case when cloning a redisplay question that points to an element of the same activity:
     * The cloned redisplay question should then point to the cloned element of the cloned activity, not the source one.
     */
    public function test_clone_with_redisplay_pointing_to_same_activity(): void {
        self::setAdminUser();

        /** @var mod_perform_generator $perform_generator */
        $perform_generator = self::getDataGenerator()->get_plugin_generator('mod_perform');

        /*
         * activity1                    [SOURCE ACTIVITY]
         * ** section1                  [SOURCE SECTION]
         *    ** element1(short-text)   [SOURCE SECTION ELEMENT]
         * ** section2
         *    ** element2(redisplay) --> element1
         */
        $activity1 = $perform_generator->create_activity_in_container(['activity_name' => 'activity1']);
        $section1 = $perform_generator->create_section($activity1, ['title' => 'section1']);
        $section2 = $perform_generator->create_section($activity1, ['title' => 'section2']);
        $element1 = $perform_generator->create_element();
        $section_element1 = $perform_generator->create_section_element($section1, $element1);

        $redisplay_data = [
            'sectionElementId' => $section_element1->id,
            'someOtherKey' => 'some other value',
        ];
        $redisplay_element = $perform_generator->create_element(
            ['plugin_name' => 'redisplay', 'data' => json_encode($redisplay_data, JSON_THROW_ON_ERROR)]
        );
        $section_element2 = $perform_generator->create_section_element($section2, $redisplay_element);
        element_redisplay_relationship::create($activity1->id, $section_element1->id, $redisplay_element->id);

        $redisplay_relationships = $this->load_relationships_by_source_activity_id($activity1->id);

        // Only one redisplay relationship exists before cloning
        self::assertCount(1, $redisplay_relationships);

        // Clone the activity.
        $cloned_activity = activity::load_by_id($activity1->id)->clone();

        // Still only one redisplay relationship exists for activity1
        $redisplay_relationships = $this->load_relationships_by_source_activity_id($activity1->id);
        self::assertCount(1, $redisplay_relationships);

        // One redisplay relationship should exist for cloned_activity.
        $redisplay_relationships = $this->load_relationships_by_source_activity_id($cloned_activity->id);
        self::assertCount(1, $redisplay_relationships);
        $cloned_relationship = $redisplay_relationships->first();

        // Verify that the redisplay_relationship data is pointing to the cloned section1 element, not the original one.
        /** @var collection|section[] $cloned_sections */
        $cloned_sections = section::repository()
            ->where('activity_id', $cloned_activity->get_id())
            ->get();
        $cloned_section1 = $cloned_sections->filter('title', 'section1')->first();
        $cloned_section2 = $cloned_sections->filter('title', 'section2')->first();
        /** @var section_element $cloned_section1_element */
        $cloned_section1_element = section_element::repository()
            ->where('section_id', $cloned_section1->id)
            ->one(true);
        /** @var section_element $cloned_redisplay_section_element */
        $cloned_redisplay_section_element = section_element::repository()
            ->where('section_id', $cloned_section2->id)
            ->one(true);
        self::assertEquals($cloned_activity->get_id(), $cloned_relationship->source_activity_id);
        self::assertEquals($cloned_section1_element->id, $cloned_relationship->source_section_element_id);
        self::assertEquals($cloned_redisplay_section_element->element_id, $cloned_relationship->redisplay_element_id);

        // Also verify that the element's json got adjusted.
        $element = \mod_perform\entity\activity\element::repository()->find($cloned_redisplay_section_element->element_id);
        $data = json_decode($element->data, true, 512, JSON_THROW_ON_ERROR);
        self::assertEquals($cloned_relationship->source_section_element_id, $data['sectionElementId']);
        // Make sure that the adjustment left other json data untouched.
        self::assertCount(2, $data);
        self::assertEquals('some other value', $data['someOtherKey']);
    }

    /**
     * Get redisplay relationships by source activity id
     *
     * @param $source_activity_id
     * @return collection
     * @throws coding_exception
     */
    private function load_relationships_by_source_activity_id($source_activity_id) {
        return element_redisplay_relationship_entity::repository()
            ->where('source_activity_id', $source_activity_id)
            ->get()
            ->map_to(element_redisplay_relationship::class);
    }
}