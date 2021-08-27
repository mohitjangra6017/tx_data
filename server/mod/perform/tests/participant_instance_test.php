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
 * @author Murali Nair <murali.nair@totaralearning.com>
 * @package mod_perform
 * @category test
 */

use core\collection;
use mod_perform\constants;
use mod_perform\models\activity\participant_instance;
use mod_perform\models\activity\participant_source;
use mod_perform\entity\activity\participant_instance as participant_instance_entity;
use totara_core\relationship\relationship;

/**
 * @group perform
 */
class mod_perform_participant_instance_testcase extends \core_phpunit\testcase {
    public function test_get_activity_roles(): void {
        $sub_r = constants::RELATIONSHIP_SUBJECT;
        $mgr_r = constants::RELATIONSHIP_MANAGER;
        $appr_r = constants::RELATIONSHIP_APPRAISER;
        $peer_r = constants::RELATIONSHIP_PEER;
        $mtr_r = constants::RELATIONSHIP_MENTOR;
        $rwr_r = constants::RELATIONSHIP_REVIEWER;

        [$uid1, $uid2, $uid3, $mgr_uid, $appr_uid] = $this
            ->create_users(5)
            ->map(
                function (stdClass $user): int {
                    return $user->id;
                }
            );

        $this->create_participant_instances(
            $uid1,
            [
                $mgr_uid => [$mgr_r, $mtr_r],
                $appr_uid => [$appr_r],
                $uid2 => [$rwr_r]
            ]
        );

        $this->create_participant_instances(
            $uid2,
            [
                $mgr_uid => [$rwr_r],
                $appr_uid => [$appr_r],
                $uid1 => [$peer_r]
            ]
        );

        $expected = [
            $uid1 => [$sub_r, $peer_r],
            $uid2 => [$sub_r, $rwr_r],
            $uid3 => [],
            $mgr_uid => [$mgr_r, $mtr_r, $rwr_r],
            $appr_uid => [$appr_r]
        ];

        foreach ($expected as $uid => $expected_roles) {
            $actual_roles = participant_instance::get_activity_roles_for($uid)
                ->map(
                    function (relationship $relationship): string {
                        return $relationship->idnumber;
                    }
                )
                ->all();

            $this->assertEqualsCanonicalizing($expected_roles, $actual_roles);
        }
    }

    /**
     * Generates test data.
     *
     * @param int $subject_userid subject user id.
     * @param array $all_relationships [user id => [relationship idnumbers]] mappings for
     *        other participants.
     *
     * @return participant_instance[] the created participant instances.
     */
    private function create_participant_instances(
        int $subject_userid, array $all_relationships
    ): array {
        $this->setAdminUser();

        $perform_generator = \mod_perform\testing\generator::instance();

        $subject_instance_id = $perform_generator->create_subject_instance([
            'subject_user_id' => $subject_userid,
            'relationships_can_view' => '',
            'subject_is_participating' => false,
            'include_questions' => false
        ])->id;

        $this->create_participant_instance(
            $subject_instance_id,
            $subject_userid,
            $perform_generator->get_core_relationship(constants::RELATIONSHIP_SUBJECT)
        );

        $participant_instances = [];
        foreach ($all_relationships as $userid => $relationships) {
            foreach ($relationships as $relationship) {
                $participant_instances[] = $this->create_participant_instance(
                    $subject_instance_id,
                    $userid,
                    $perform_generator->get_core_relationship($relationship)
                );
            }
        }

        return $participant_instances;
    }

    /**
     * Generates test users.
     *
     * @param int count no of users to generate.
     *
     * @return collection|stdClass[] the created users.
     */
    private function create_users(int $count): collection {
        $this->setAdminUser();

        return collection::new(range(0, $count - 1))
            ->map(
                function (int $i): stdClass {
                    return self::getDataGenerator()->create_user();
                }
            );
    }

    /**
     * Creates a participant instance.
     *
     * @return participant_instance participant instance.
     */
    private function create_participant_instance(
        int $subject_instance_id, int $participant_userid, relationship $relationship
    ): participant_instance {
        $pi = new participant_instance_entity();
        $pi->core_relationship_id = $relationship->id;
        $pi->participant_id = $participant_userid;
        $pi->participant_source = participant_source::INTERNAL;
        $pi->subject_instance_id = $subject_instance_id;
        $pi->save();

        return participant_instance::load_by_entity($pi);
    }
}
