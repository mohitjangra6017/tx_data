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
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package performelement_linked_review
 */

namespace performelement_linked_review\webapi\resolver\query;

use coding_exception;
use core\orm\collection;
use core\webapi\execution_context;
use core\webapi\middleware\require_advanced_feature;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;
use mod_perform\entity\activity\participant_instance;
use mod_perform\entity\activity\participant_section;
use mod_perform\models\activity\helpers\external_participant_token_validator;
use mod_perform\models\activity\participant_source;
use mod_perform\models\activity\section_element as section_element_model;
use mod_perform\models\activity\subject_instance as subject_instance_model;
use mod_perform\util;
use performelement_linked_review\models\linked_review_content as linked_review_content_model;

abstract class content_items implements query_resolver, has_middleware {

    /**
     * {@inheritdoc}
     */
    final public static function resolve(array $args, execution_context $ec) {
        global $USER;

        $token = $args['token'] ?? null;
        if (!empty($token)) {
            $validator = new external_participant_token_validator($token);
            if (!$validator->is_valid() || $validator->is_subject_instance_closed()) {
                throw new coding_exception('Token validation for external participant failed');
            }
            if ((int) $validator->get_participant_instance()->subject_instance_id !== $args['subject_instance_id']) {
                throw new coding_exception('Invalid subject instance for given token');
            }
        } else {
            \require_login(null, false, null, false, true);
        }

        $subject_instance_id = $args['subject_instance_id'];
        $section_element_id = $args['section_element_id'];

        $section_element = section_element_model::load_by_id($section_element_id);
        if ($section_element->element->get_element_plugin()->get_plugin_name() !== 'linked_review') {
            throw new coding_exception('Invalid section element ID: ' . $section_element_id);
        }

        $subject_instance = subject_instance_model::load_by_id($subject_instance_id);

        if ($validator) {
            $has_participant_section = participant_section::repository()
                ->where('section_id', $section_element->section_id)
                ->where('participant_instance_id', $validator->get_participant_instance()->id)
                ->exists();
        } else {
            $has_participant_section = participant_section::repository()
                ->join([participant_instance::TABLE, 'pi'], 'participant_instance_id', 'id')
                ->where('section_id', $section_element->section_id)
                ->where('pi.participant_id', $USER->id)
                ->where('pi.participant_source', participant_source::INTERNAL)
                ->where('pi.subject_instance_id', $subject_instance_id)
                ->exists();
        }

        if (!$has_participant_section
            && !util::can_report_on_user($subject_instance->subject_user_id, $USER->id)
        ) {
            throw new coding_exception('User does not participant on given section');
        }

        $content = linked_review_content_model::get_existing_selected_content($section_element_id, $subject_instance_id);
        if ($content->count() === 0) {
            return [];
        }

        return static::query_content($subject_instance->subject_user_id, $content);
    }

    /**
     * Fetch the linked content with the specified IDs.
     *
     * @param int $user_id
     * @param linked_review_content_model[]|collection $content
     * @return array
     */
    abstract protected static function query_content(int $user_id, collection $content): array;

    /**
     * {@inheritdoc}
     */
    public static function get_middleware(): array {
        return [
            new require_advanced_feature('performance_activities'),
        ];
    }

}
