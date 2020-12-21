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

namespace performelement_redisplay\models;

use Closure;
use coding_exception;
use core\orm\collection;
use core\orm\entity\model;
use mod_perform\entity\activity\participant_section as participant_section_entity;
use mod_perform\models\activity\section;
use mod_perform\entity\activity\section as section_entity;
use mod_perform\entity\activity\section_element;
use performelement_redisplay\entity\element_redisplay_relationship as element_redisplay_relationship_entity;

/**
 * The presence of relationship of redisplay element, activity and source element
 *
 * @property-read int $id ID
 * @property-read int $source_activity_id
 * @property-read int $source_section_element_id
 * @property-read int $redisplay_element_id
 *
 * @package performelement_redisplay\model
 */
class element_redisplay_relationship extends model {

    /**
     * @var element_redisplay_relationship_entity
     */
    protected $entity;

    /**
     * @inheritDoc
     */
    protected static function get_entity_class(): string {
        return element_redisplay_relationship_entity::class;
    }

    protected $entity_attribute_whitelist = [
        'id',
        'source_activity_id',
        'source_section_element_id',
        'redisplay_element_id',
    ];

    /**
     * Create redisplay element
     *
     * @param int $source_activity_id
     * @param int $source_section_element_id
     * @param int $redisplay_element_id
     * @return element_redisplay_relationship
     * @throws coding_exception
     */
    public static function create(int $source_activity_id, int $source_section_element_id, int $redisplay_element_id) {
        $entity = new element_redisplay_relationship_entity();
        $entity->source_activity_id = $source_activity_id;
        $entity->source_section_element_id = $source_section_element_id;
        $entity->redisplay_element_id = $redisplay_element_id;
        $entity->save();

        return static::load_by_entity($entity);
    }

    /**
     * Get element redisplay relationship by redisplay element id
     *
     * @param int $redisplay_element_id
     * @return element_redisplay_relationship
     * @throws coding_exception
     */
    private static function load_by_redisplay_element_id(int $redisplay_element_id) {
        $entity = element_redisplay_relationship_entity::repository()
            ->where('redisplay_element_id', $redisplay_element_id)
            ->one();

        return self::load_by_entity($entity);
    }

    /**
     * Update element redisplay relationship
     *
     * @param int $source_activity_id
     * @param int $source_section_element_id
     * @param int $redisplay_element_id
     * @throws coding_exception
     */
    public static function update(int $source_activity_id, int $source_section_element_id, int $redisplay_element_id) {
        $element_redisplay_relationship = self::load_by_redisplay_element_id($redisplay_element_id);
        $element_redisplay_relationship->entity->source_activity_id = $source_activity_id;
        $element_redisplay_relationship->entity->source_section_element_id = $source_section_element_id;
        $element_redisplay_relationship->entity->update();
    }

    /**
     * Get sections which are referencing elements in a specific activity
     *
     * @param int $source_activity_id
     * @return collection
     * @throws coding_exception
     */
    public static function get_sections_by_source_activity_id(int $source_activity_id) {
        $sections = section_entity::repository()
            ->with('activity')
            ->as('s')
            ->join([section_element::TABLE, 'se'], 's.id', 'se.section_id')
            ->join([element_redisplay_relationship_entity::TABLE, 'rd'], 'se.element_id', 'rd.redisplay_element_id')
            ->where('rd.source_activity_id', $source_activity_id)
            ->get()
            ->sort(Closure::fromCallable([element_redisplay_relationship::class, 'activity_sections_callback']))
            ->map_to(section::class);

        return $sections;
    }

    /**
     * Get sections which are referencing elements in a specific section
     *
     * @param int $source_section_id
     * @return collection
     * @throws coding_exception
     */
    public static function get_sections_by_source_section_id(int $source_section_id) {
        $source_section_element_ids = section_element::repository()
            ->where('section_id', $source_section_id)
            ->get()
            ->pluck('id');

        $sections = section_entity::repository()
            ->with('activity')
            ->as('s')
            ->join([section_element::TABLE, 'se'], 's.id', 'se.section_id')
            ->join([element_redisplay_relationship_entity::TABLE, 'rd'], 'se.element_id', 'rd.redisplay_element_id')
            ->where_in('rd.source_section_element_id', $source_section_element_ids)
            ->get()
            ->sort(Closure::fromCallable([element_redisplay_relationship::class, 'activity_sections_callback']))
            ->map_to(section::class);

        return $sections;
    }

    /**
     * Get sections which are referencing a specific section element
     *
     * @param int $section_element_id source section element id
     * @return collection
     * @throws coding_exception
     */
    public static function get_sections_by_source_section_element_id(int $section_element_id) {
        $sections = section_entity::repository()
            ->with('activity')
            ->as('s')
            ->join([section_element::TABLE, 'se'], 's.id', 'se.section_id')
            ->join([element_redisplay_relationship_entity::TABLE, 'rd'], 'se.element_id', 'rd.redisplay_element_id')
            ->where('rd.source_section_element_id', $section_element_id)
            ->get()
            ->sort(Closure::fromCallable([element_redisplay_relationship::class, 'activity_sections_callback']))
            ->map_to(section::class);

        return $sections;
    }

    /**
     * Sort activities and sections by name
     *
     * @param section_entity $first
     * @param section_entity $second
     * @return int
     */
    private static function activity_sections_callback(section_entity $first, section_entity $second) {
        $first_activity_name = strtolower(trim($first->activity->name));
        $first_section_name = strtolower(trim($first->title));
        $second_activity_name = strtolower(trim($second->activity->name));
        $second_section_name = strtolower(trim($second->title));

        if ($first_activity_name != $second_activity_name) {
            return strcmp($first_activity_name, $second_activity_name);
        }

        if ($first_section_name != $second_section_name) {
            return strcmp($first_section_name, $second_section_name);
        }

        return 0;
    }

    /**
     * Checks if a participant section can access section element through the redisplay component.
     *
     * @param int $participant_section_id
     * @param int $section_element_id
     * @return bool
     */
    public static function participant_section_can_access_section_element(int $participant_section_id, int $section_element_id): bool {
        return section_element::repository()->as('se')
            ->join([participant_section_entity::TABLE, 'ps'], 'se.section_id', 'ps.section_id')
            ->join([element_redisplay_relationship_entity::TABLE, 'err'], 'se.element_id', 'err.redisplay_element_id')
            ->where('ps.id', $participant_section_id)
            ->where('err.source_section_element_id', $section_element_id)
            ->exists();
    }
}
