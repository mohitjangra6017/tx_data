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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Marco Song <marco.song@totaralearning.com>
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package performelement_linked_review
 */

namespace performelement_linked_review\models;

use coding_exception;
use core\entity\user;
use core\orm\collection;
use core\orm\entity\model;
use core\orm\query\builder;
use mod_perform\entity\activity\element;
use mod_perform\entity\activity\participant_section as participant_section_entity;
use mod_perform\entity\activity\section_element as section_element_entity;
use mod_perform\models\activity\element as element_model;
use mod_perform\models\activity\participant_instance as participant_instance_model;
use mod_perform\models\activity\participant_source;
use mod_perform\models\activity\section_element;
use moodle_exception;
use performelement_linked_review\entity\linked_review_content as linked_review_content_entity;
use performelement_linked_review\linked_review;

/**
 * Class element_subject_instance_review_content
 *
 * @property-read int $id
 * @property-read int $section_element_id
 * @property-read int $participant_instance_id
 * @property-read int $content_id
 * @property-read int $created_at
 *
 * @package performelement_linked_review\models
 */
class linked_review_content extends model {

    /**
     * @var linked_review_content_entity
     */
    protected $entity;

    /**
     * @inheritDoc
     */
    protected static function get_entity_class(): string {
        return linked_review_content_entity::class;
    }

    protected $entity_attribute_whitelist = [
        'id',
        'content_id',
        'section_element_id',
        'participant_instance_id',
    ];

    /**
     * Update the content IDs that are linked to the subject user's section element.
     *
     * @param array $content_ids
     * @param int $section_element_id
     * @param int $participant_instance_id
     * @return static[]|collection
     */
    public static function update_content(array $content_ids, int $section_element_id, int $participant_instance_id): collection {
        self::validate_input($content_ids, $section_element_id, $participant_instance_id);

        return builder::get_db()->transaction(function () use ($content_ids, $section_element_id, $participant_instance_id) {
            $current_linked_content_ids = self::get_existing_selected_content($section_element_id, $participant_instance_id)
                ->pluck('content_id');

            $content_ids_to_delete = array_diff($current_linked_content_ids, $content_ids);
            self::delete_multiple($content_ids_to_delete, $section_element_id, $participant_instance_id, false);

            $new_content_ids_to_create = array_diff($content_ids, $current_linked_content_ids);
            return self::create_multiple($new_content_ids_to_create, $section_element_id, $participant_instance_id, false);
        });
    }

    /**
     * Create multiple new content links to a section element.
     *
     * @param array $content_ids
     * @param int $section_element_id
     * @param int $participant_instance_id
     * @param bool $validate Whether to validate the inputted IDs.
     * @return collection
     */
    public static function create_multiple(
        array $content_ids,
        int $section_element_id,
        int $participant_instance_id,
        bool $validate = true
    ): collection {
        if ($validate) {
            self::validate_input($content_ids, $section_element_id, $participant_instance_id);
        }

        return collection::new($content_ids)
            ->map(static function (int $content_id) use ($section_element_id, $participant_instance_id) {
                return self::create($content_id, $section_element_id, $participant_instance_id, false);
            });
    }

    /**
     * Create a new content link to a section element.
     *
     * @param int $content_id
     * @param int $section_element_id
     * @param int $participant_instance_id
     * @param bool $validate Whether to validate the inputted IDs.
     * @return static
     */
    public static function create(
        int $content_id,
        int $section_element_id,
        int $participant_instance_id,
        bool $validate = true
    ): self {
        if ($validate) {
            self::validate_input([$content_id], $section_element_id, $participant_instance_id);
        }

        $entity = new linked_review_content_entity();
        $entity->content_id = $content_id;
        $entity->section_element_id = $section_element_id;
        $entity->participant_instance_id = $participant_instance_id;
        $entity->save();

        return static::load_by_entity($entity);
    }

    /**
     * Unlink the specified content IDs from the section element.
     *
     * @param int[] $content_ids
     * @param int $section_element_id
     * @param int $participant_instance_id
     * @param bool $validate Whether to validate the inputted IDs.
     * @throws coding_exception
     */
    public static function delete_multiple(
        array $content_ids,
        int $section_element_id,
        int $participant_instance_id,
        bool $validate = true
    ): void {
        if ($validate) {
            self::validate_input($content_ids, $section_element_id, $participant_instance_id);
        }

        linked_review_content_entity::repository()
            ->where('section_element_id', $section_element_id)
            ->where('participant_instance_id', $participant_instance_id)
            ->where_in('content_id', $content_ids)
            ->delete();
    }

    /**
     * Unlink this.
     */
    public function delete(): void {
        $this->entity->delete();
    }

    /**
     * Get the content IDs that have already been selected for the subject's section element.
     *
     * @param int $section_element_id
     * @param int $participant_instance_id
     * @return static[]|collection
     */
    private static function get_existing_selected_content(
        int $section_element_id,
        int $participant_instance_id
    ): collection {
        return linked_review_content_entity::repository()
            ->where('section_element_id', $section_element_id)
            // Check other links done for the same subject
            ->join(['perform_participant_instance', 'pi1'], 'participant_instance_id', 'id')
            ->join(['perform_participant_instance', 'pi2'], 'pi1.subject_instance_id', 'subject_instance_id')
            ->where('pi1.id', $participant_instance_id)
            ->or_where('pi2.id', $participant_instance_id)
            ->get()
            ->map_to(static::class);
    }

    /**
     * Validate the values inputted when saving and throw errors if they are invalid.
     *
     * @param array $content_ids
     * @param int $section_element_id
     * @param int $participant_instance_id
     * @throws coding_exception
     * @throws moodle_exception
     */
    private static function validate_input(
        array $content_ids,
        int $section_element_id,
        int $participant_instance_id
    ): void {
        // Make sure the section element is a linked review element, and that the participant section has the element in it.
        $is_valid_element = section_element_entity::repository()
            ->where('id', $section_element_id)
            ->join([element::TABLE, 'el'], 'element_id', 'id')
            ->where('el.plugin_name', 'linked_review')
            ->join([participant_section_entity::TABLE, 'ps'], 'section_id', 'section_id')
            ->where('ps.participant_instance_id', $participant_instance_id)
            ->exists();
        if (!$is_valid_element) {
            throw new coding_exception(
                "The specified section element with ID {$section_element_id} is not a linked review element " .
                "or the specified participant instance with ID {$participant_instance_id} does not share the same section."
            );
        }

        $section_element = section_element::load_by_id($section_element_id);
        $element = $section_element->get_element();
        if (!self::can_participant_select_content($participant_instance_id, $element)) {
            throw new moodle_exception('nopermissions', 'error');
        }

        // Make sure the content IDs actually point to content.;
        /** @var linked_review $element_plugin */
        $element_plugin = $element->get_element_plugin();
        $content_table = $element_plugin->get_content_type($element)::get_table_name();
        $content_count = builder::table($content_table)->where_in('id', $content_ids)->count();
        if ($content_count !== count($content_ids)) {
            throw new coding_exception(
                'Not all the specified content IDs actually exist. ' .
                'Specified IDs: ' . json_encode($content_ids) .
                ', Number of IDs in the ' . $content_table . ' table: ' . $content_count
            );
        }
    }

    /**
     * Checks if the participant can select content.
     *
     * @param int $participant_instance_id
     * @param element_model $linked_review_element
     * @return bool
     */
    private static function can_participant_select_content(int $participant_instance_id, element_model $linked_review_element): bool {
        $participant_instance = participant_instance_model::load_by_id($participant_instance_id);
        $element_data = json_decode($linked_review_element->get_data(), 'true');
        $selection_relationships = $element_data['selection_relationships'] ?? [];

        if (empty($selection_relationships)) {
            return false;
        }

        return self::participant_instance_belongs_to_logged_in_user($participant_instance)
            && in_array((int)$participant_instance->core_relationship_id, $selection_relationships);
    }

    /**
     * Checks if participant instance belongs to logged in user.
     *
     * @param participant_instance_model $participant_instance
     * @return bool
     */
    private static function participant_instance_belongs_to_logged_in_user(participant_instance_model $participant_instance): bool {
        return (int)$participant_instance->participant_id === user::logged_in()->id
        && (int)$participant_instance->participant_source === participant_source::INTERNAL;
    }

}
