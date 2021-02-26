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
 * @package totara_evidence
 */

use mod_perform\constants;
use mod_perform\entity\activity\participant_instance;
use mod_perform\models\activity\activity;
use mod_perform\models\activity\element;
use mod_perform\models\activity\section_element;
use performelement_linked_review\models\linked_review_content;
use totara_core\advanced_feature;
use totara_core\feature_not_available_exception;
use totara_evidence\models\evidence_item;
use totara_evidence\performelement_linked_review\evidence;
use totara_webapi\phpunit\webapi_phpunit_helper;

class totara_evidence_perform_linked_evidence_content_testcase extends advanced_testcase {

    public function test_query_linked_evidences_successful() {
        global $USER;

        [$evidence1, $evidence2, $evidence3] = $this->create_evidence_items();

        $content_type = new evidence(context_system::instance());
        $result = $content_type->load_content_items(
            $USER->id,
            [$evidence1->id, $evidence2->id, $evidence3->id],
            time()
        );

        $this->assertEmpty($result);
    }

    public function test_feature_disabled() {
        global $USER;

        [$evidence1, $evidence2, $evidence3] = $this->create_evidence_items();

        $content_type = new evidence(context_system::instance());
        $result = $content_type->load_content_items(
            $USER->id,
            [$evidence1->id, $evidence2->id, $evidence3->id],
            time()
        );

        $this->assertEmpty($result);
    }


    /**
     * @return array
     */
    private function create_evidence_items() {
        $evidence_generator = $this->getDataGenerator()->get_plugin_generator('totara_evidence');

        $evidence_user = $evidence_generator->create_evidence_user();
        $evidence_type = $evidence_generator->create_evidence_type(['name' => 'Type']);

        $this->setUser($evidence_user->get_record());

        $field_data = (object)[
            'key' => 'value',
        ];

        $evidence_item1 = evidence_item::create($evidence_type, $evidence_user, $field_data, 'Evidence1');
        $evidence_item2 = evidence_item::create($evidence_type, $evidence_user, $field_data, 'Evidence2');
        $evidence_item3 = evidence_item::create($evidence_type, $evidence_user, $field_data, 'Evidence3');

        return [$evidence_item1, $evidence_item2, $evidence_item3];
    }

}