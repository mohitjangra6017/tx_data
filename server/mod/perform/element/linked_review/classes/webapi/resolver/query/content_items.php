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
use core\webapi\execution_context;
use core\webapi\middleware\require_advanced_feature;
use core\webapi\middleware\require_login;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;
use mod_perform\entity\activity\element;
use mod_perform\entity\activity\participant_instance;
use mod_perform\entity\activity\section_element;
use mod_perform\entity\activity\subject_instance;
use mod_perform\webapi\middleware\require_activity;
use performelement_linked_review\entity\linked_review_content;

abstract class content_items implements query_resolver, has_middleware {

    /**
     * {@inheritdoc}
     */
    final public static function resolve(array $args, execution_context $ec) {
        $participant_instance_id = $args['participant_instance_id'];
        $section_element_id = $args['section_element_id'];

        $element = element::repository()
            ->join([section_element::TABLE, 'se'], 'id', 'element_id')
            ->where('plugin_name', 'linked_review')
            ->where('se.id', $section_element_id)
            ->one(true);
        if (!$element) {
            throw new coding_exception('Invalid section element ID: ' . $section_element_id);
        }

        // TODO: Need to check permissions.
        $subject_user_id = subject_instance::repository()
            ->select('subject_user_id')
            ->join([participant_instance::TABLE, 'pi'], 'id', 'subject_instance_id')
            ->where('pi.id', $participant_instance_id)
            ->one()
            ->subject_user_id;

        $content_ids = linked_review_content::repository()
            ->select('content_id')
            ->where('section_element_id', $section_element_id)
            ->where('participant_instance_id', $participant_instance_id)
            ->get()
            ->pluck('content_id');

        if (empty($content_ids)) {
            return [];
        }

        return static::query_content($subject_user_id, $content_ids);
    }

    /**
     * Fetch the linked content with the specified IDs.
     *
     * @param int $user_id
     * @param int[] $content_ids
     * @return array
     */
    abstract protected static function query_content(int $user_id, array $content_ids): array;

    /**
     * {@inheritdoc}
     */
    public static function get_middleware(): array {
        return [
            new require_advanced_feature('performance_activities'),
            new require_login(),
            //todo: verify use of content_items with participant instance_id.
            // performance response data page does not use participant instance id.
            // require_activity::by_participant_instance_id('participant_instance_id', true),
        ];
    }

}
