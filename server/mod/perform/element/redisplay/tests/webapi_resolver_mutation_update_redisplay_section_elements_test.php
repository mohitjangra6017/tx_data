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
 * @author Jaron Steenson <jaron.steenson@totaralearning.com>
 * @package mod_perform
 */

use mod_perform\models\activity\respondable_element_plugin;
use mod_perform\models\activity\section;
use mod_perform\models\activity\section_element;
use mod_perform\testing\generator;
use performelement_custom_rating_scale\custom_rating_scale;
use performelement_redisplay\redisplay;
use performelement_numeric_rating_scale\numeric_rating_scale;
use totara_webapi\phpunit\webapi_phpunit_helper;

/**
 * @group perform
 * @group perform_element
 */
class mod_perform_webapi_resolver_mutation_update_redisplay_section_elements_testcase extends advanced_testcase {
    private const MUTATION = 'mod_perform_update_section_elements';

    use webapi_phpunit_helper;

    public function test_create_and_update_redisplay_section_elements(): void {
        self::setAdminUser();

        $perform_generator = generator::instance();

        $activity = $perform_generator->create_activity_in_container();
        $section = $perform_generator->create_section($activity);

        $args = [
            'input' => [
                'section_id' => $section->id,
                'create_new' => [
                    [
                        'plugin_name' => numeric_rating_scale::get_plugin_name(),
                        'title' => 'Original source numeric rating scale',
                        'identifier' => 'num-rating-scale',
                        'data' => '{}',
                        'is_required' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'plugin_name' => custom_rating_scale::get_plugin_name(),
                        'title' => 'Other source, custom rating scale',
                        'identifier' => 'custom-rating-scale',
                        'data' => '{}',
                        'is_required' => true,
                        'sort_order' => 2,
                    ],
                ],
            ],
        ];

        $result = $this->resolve_graphql_mutation(self::MUTATION, $args);
        [$source_section_element, ,] = $this->assert_correct_elements_returned($result, false);

        $args = [
            'input' => [
                'section_id' => $section->id,
                'create_new' => [
                    [
                        'plugin_name' => redisplay::get_plugin_name(),
                        'title' => 'Redisplay element',
                        'identifier' => 're-element',
                        'data' => json_encode([
                            redisplay::SOURCE_SECTION_ELEMENT_ID => $source_section_element->id,
                        ], JSON_THROW_ON_ERROR),
                        'is_required' => false,
                        'sort_order' => 3,
                    ],
                ],
            ],
        ];

        $result = $this->resolve_graphql_mutation(self::MUTATION, $args);

        [
            $source_section_element,
            $other_source_section_element,
            $redisplay_section_element
        ] = $this->assert_correct_elements_returned($result);

        /** @var respondable_element_plugin $source_plugin */
        $source_plugin = $source_section_element->get_element()->get_element_plugin();

        self::assertEquals(
            [
                redisplay::SOURCE_SECTION_ELEMENT_ID => $source_section_element->id,
                'activityId' => $activity->id,
                'activityName' => $activity->name,
                'activityStatus' => 'Active',
                'elementTitle' => 'Original source numeric rating scale',
                'elementPluginName' => 'Rating scale: Numeric',
                'elementPluginDisplayComponent' => $source_plugin->get_participant_response_component(),
                'relationships' => '{No responding relationships added yet}'
            ],
            json_decode($redisplay_section_element->get_element()->get_data(), true, 512, JSON_THROW_ON_ERROR)
        );

        $args = [
            'input' => [
                'section_id' => $section->id,
                'update' => [
                    [
                        'element_id' => $redisplay_section_element->get_element()->id,
                        'plugin_name' => redisplay::get_plugin_name(),
                        'title' => 'Redisplay element',
                        'identifier' => 're-element',
                        'data' => json_encode([
                            redisplay::SOURCE_SECTION_ELEMENT_ID => $other_source_section_element->id,
                        ], JSON_THROW_ON_ERROR),
                        'is_required' => false,
                        'sort_order' => 3,
                    ],
                ],
            ],
        ];

        $result = $this->resolve_graphql_mutation(self::MUTATION, $args);

        [
            ,
            $other_source_section_element,
            $redisplay_section_element
        ] = $this->assert_correct_elements_returned($result);

        /** @var respondable_element_plugin $other_source_plugin */
        $other_source_plugin = $other_source_section_element->get_element()->get_element_plugin();

        self::assertEquals(
            [
                redisplay::SOURCE_SECTION_ELEMENT_ID => $other_source_section_element->id,
                'activityId' => $activity->id,
                'activityName' => $activity->name,
                'activityStatus' => 'Active',
                'elementTitle' => 'Other source, custom rating scale',
                'elementPluginName' => 'Rating scale: Custom',
                'elementPluginDisplayComponent' => $other_source_plugin->get_participant_response_component(),
                'relationships' => '{No responding relationships added yet}'
            ],
            json_decode($redisplay_section_element->get_element()->get_data(), true, 512, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @param array $result
     * @param bool $include_redisplay_element
     * @return section_element[]
     */
    private function assert_correct_elements_returned(array $result, bool $include_redisplay_element = true): array {
        /** @var section $section */
        $section = $result['section'];

        /** @var section_element[] $section_elements */
        $section_elements = $section->get_section_elements()->all(false);

        [$source_section_element, $other_source_section_element] = $section_elements;

        self::assertCount($include_redisplay_element ? 3 : 2, $section_elements);
        self::assertEquals('Original source numeric rating scale', $source_section_element->get_element()->title);
        self::assertEquals('Other source, custom rating scale', $other_source_section_element->get_element()->title);

        if ($include_redisplay_element) {
            $redisplay_section_element = $section_elements[2];
            self::assertEquals('Redisplay element', $redisplay_section_element->get_element()->title);
            self::assertNull(
                $redisplay_section_element->get_element()->get_raw_data(),
                'No data should be persisted for Redisplay elements'
            );
        }

        return [$source_section_element, $other_source_section_element, $redisplay_section_element ?? null];
    }

}
