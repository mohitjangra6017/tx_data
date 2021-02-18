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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Matthias Bonk <matthias.bonk@totaralearning.com>
 */

use core\collection;
use mod_perform\models\activity\element;
use mod_perform\models\activity\section;
use mod_perform\models\activity\section_element;
use totara_webapi\phpunit\webapi_phpunit_helper;

/**
 * @group perform
 * @group perform_element
 */
class performelement_aggregation_webapi_resolver_query_aggregatable_question_elements_testcase extends advanced_testcase {

    private const QUERY = 'performelement_aggregation_aggregatable_question_elements';

    use webapi_phpunit_helper;

    public function test_get_question_elements_for_activity_shows_only_sections_with_respondable_elements(): void {
        self::setAdminUser();

        $perform_generator = \mod_perform\testing\generator::instance();
        $activity = $perform_generator->create_activity_in_container(['create_section' => false]);

        $section_1 = section::create($activity, 'First section');
        $section_2 = section::create($activity, 'Second section');
        $section_3 = section::create($activity, 'Section without aggregatable elements');

        $short_text_element = element::create(
            $activity->get_context(),
            'short_text',
            'Short text test element',
            'ST ID'
        );

        $long_text_element = element::create(
            $activity->get_context(),
            'long_text',
            'Long text test element',
            'LT ID'
        );

        $custom_rating_element = element::create(
            $activity->get_context(),
            'custom_rating_scale',
            'Aggregatable 1',
            'CR ID',
            '{"options":[{"name":"option_1","value":{"text":"text1","score":"1"}},{"name":"option_2","value":{"text":"text2","score":"2"}}]}'
        );

        $numeric_rating_element = element::create(
            $activity->get_context(),
            'numeric_rating_scale',
            'Aggregatable 2',
            'NR ID',
            '{"defaultValue":"3","highValue":"5","lowValue":"1"}'
        );

        $section_1->add_element($short_text_element);
        $expected_section_element_1 = $section_1->add_element($custom_rating_element);
        $expected_section_element_2 = $section_2->add_element($numeric_rating_element);
        $section_3->add_element($long_text_element);

        $result = $this->resolve_graphql_query(self::QUERY, [
            'input' => [
                'activity_id' => $activity->id,
            ]
        ]);

        /** @var collection $result_sections */
        $result_sections = $result['sections'];
        self::assertCount(2, $result_sections);
        self::assertEqualsCanonicalizing([$section_1->id, $section_2->id], $result_sections->pluck('id'));

        // Make sure the expected sections are in the result.
        $result_section_1 = $result_sections->item($section_1->id);
        $result_section_2 = $result_sections->item($section_2->id);
        self::assertNull($result_sections->item($section_3->id));

        // Make sure the expected section_elements are in the result.
        self::assertCount(1, $result_section_1->aggregatable_section_elements);
        /** @var section_element $result_section_element_1 */
        $result_section_element_1 = $result_section_1->aggregatable_section_elements->first();
        self::assertEquals($expected_section_element_1->id, $result_section_element_1->id);
        self::assertCount(1, $result_section_2->aggregatable_section_elements);
        /** @var section_element $result_section_element_2 */
        $result_section_element_2 = $result_section_2->aggregatable_section_elements->first();
        self::assertEquals($expected_section_element_2->id, $result_section_element_2->id);

        // Make sure the expected elements are in the result.
        self::assertEquals($custom_rating_element->id, $result_section_element_1->element->id);
        self::assertEquals($numeric_rating_element->id, $result_section_element_2->element->id);

        foreach ($result_sections as $section) {
            self::assertEquals($activity->id, (int)$section->activity_id);
        }
    }
}