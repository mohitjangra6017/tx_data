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
use core\webapi\middleware\require_login;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;
use mod_perform\entity\activity\element;
use mod_perform\entity\activity\participant_instance;
use mod_perform\entity\activity\section_element;
use mod_perform\entity\activity\subject_instance;
use mod_perform\models\activity\subject_instance as subject_instance_model;
use mod_perform\webapi\middleware\require_activity;
use performelement_linked_review\entity\linked_review_content;

abstract class content_items implements query_resolver, has_middleware {

    /**
     * {@inheritdoc}
     */
    final public static function resolve(array $args, execution_context $ec) {
        $subject_instance_id = $args['subject_instance_id'];
        $section_element_id = $args['section_element_id'];

        $element = element::repository()
            ->join([section_element::TABLE, 'se'], 'id', 'element_id')
            ->where('plugin_name', 'linked_review')
            ->where('se.id', $section_element_id)
            ->one(true);
        if (!$element) {
            throw new coding_exception('Invalid section element ID: ' . $section_element_id);
        }

        $subject_instance = subject_instance_model::load_by_id($subject_instance_id);

        // TODO: Need to check permissions:
        //       Check whether the current user participates in the section the review element is in
        //       or has reporting capabilities on responses?

        $content = linked_review_content::repository()
            ->where('section_element_id', $section_element_id)
            ->where('subject_instance_id', $subject_instance->id)
            ->get();

        if ($content->count() === 0) {
            return [];
        }

        return static::query_content($subject_instance->subject_user_id, $content);
    }

    /**
     * Fetch the linked content with the specified IDs.
     *
     * @param int $user_id
     * @param linked_review_content[]|collection $content
     * @return array
     */
    abstract protected static function query_content(int $user_id, collection $content): array;

    /**
     * {@inheritdoc}
     */
    public static function get_middleware(): array {
        return [
            new require_advanced_feature('performance_activities'),
            new require_login(),
            require_activity::by_subject_instance_id('subject_instance_id', true),
        ];
    }

}
