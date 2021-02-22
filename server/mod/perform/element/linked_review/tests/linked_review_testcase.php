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
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package performelement_linked_review
 */

use mod_perform\constants;
use mod_perform\entity\activity\subject_instance;
use mod_perform\models\activity\activity;
use mod_perform\models\activity\element;
use mod_perform\models\activity\section;
use mod_perform\testing\generator as perform_generator;
use totara_competency\testing\generator as competency_generator;
use totara_core\relationship\relationship;

class linked_review_testcase extends advanced_testcase {

    /**
     * @var perform_generator $perform_generator
     */
    protected $perform_generator;

    /**
     * @var competency_generator $competency_generator
     */
    protected $competency_generator;

    protected function tearDown(): void {
        parent::tearDown();
        $this->perform_generator = null;
        $this->competency_generator = null;
    }

    protected function create_element(array $data, activity $activity = null): element {
        $context = $activity ? $activity->get_context() : context_system::instance();
        return element::create($context, 'linked_review', 'title', '', json_encode($data));
    }

    protected function create_activity_with_section_and_review_element(string $content_type = 'totara_competency'): array {
        self::setAdminUser();
        $activity = $this->perform_generator()->create_activity_in_container();
        $section = $this->perform_generator()->create_section($activity);
        $element = element::create($activity->get_context(), 'linked_review', 'title', '', json_encode([
            'content_type' => $content_type,
            'content_type_settings' => [],
            'selection_relationships' => [relationship::load_by_idnumber(constants::RELATIONSHIP_SUBJECT)->id],
        ]));
        $section_element = $this->perform_generator()->create_section_element($section, $element);
        return [$activity, $section, $element, $section_element];
    }

    protected function create_participant_in_section(
        activity $activity,
        section $section,
        subject_instance $subject_instance = null,
        int $relationship_id = null
    ): array {
        $user = self::getDataGenerator()->create_user();
        if ($subject_instance === null) {
            $subject_instance = $this->perform_generator()->create_subject_instance([
                'activity_id' => $activity->id,
                'subject_user_id' => $user->id,
                'include_questions' => false,
            ]);
        }
        if ($relationship_id === null) {
            $relationship_id = relationship::load_by_idnumber(constants::RELATIONSHIP_SUBJECT)->id;
        }

        $participant_section = $this->perform_generator()->create_participant_instance_and_section(
            $activity,
            $user,
            $subject_instance->id,
            $section,
            $relationship_id
        );
        return [$user, $subject_instance, $participant_section->participant_instance, $participant_section];
    }

    protected function create_competency_assignments(int $user_id, int $count = 3): array {
        $competency_assignment_ids = [];
        for ($i = 0; $i < $count; $i++) {
            $competency = $this->competency_generator()->create_competency();
            $assignment = $this->competency_generator()->assignment_generator()->create_user_assignment($competency->id, $user_id);
            $competency_assignment_ids[] = $assignment->id;
        }
        return $competency_assignment_ids;
    }

    public function perform_generator(): perform_generator {
        if (!isset($this->perform_generator)) {
            $this->perform_generator = self::getDataGenerator()->get_plugin_generator('mod_perform');
        }
        return $this->perform_generator;
    }

    public function competency_generator(): competency_generator {
        if (!isset($this->competency_generator)) {
            $this->competency_generator = self::getDataGenerator()->get_plugin_generator('totara_competency');
        }
        return $this->competency_generator;
    }

}
