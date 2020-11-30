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

use mod_perform\models\activity\section;
use totara_webapi\phpunit\webapi_phpunit_helper;

require_once(__DIR__ . '/redisplay_test.php');

/**
 * @group perform
 * @group perform_element
 */
class section_deletion_testcase extends redisplay_testcase {
    const QUERY = "mod_perform_element_deletion_validation";

    use webapi_phpunit_helper;

    public function test_delete_watcher() {
        $data = $this->create_test_data();

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessageMatches("/This section cannot be deleted/");

        section::load_by_id($data->section1->id)->delete();
    }

    public function test_query_validation_successful() {
        $data = $this->create_test_data();

        $args = ['input' => ['section_element_id' => $data->section_element1->id]];
        $result = $this->resolve_graphql_query(self::QUERY, $args);

        $this->assertEquals("Cannot delete question element", $result['title']);
        $this->assertFalse($result['can_delete']);

        $description = $result['reason']['description'];
        $result_data = $result['reason']['data'];

        $this->assertEquals('This question cannot be deleted, because it is being referenced in a response redisplay element in:', $description);

        // // check data with correct order
        $this->assertCount(2, $result_data);

        $first_section = $result_data[0];
        $this->assertEquals('activity2 : section2', $first_section);

        $first_section = $result_data[1];
        $this->assertEquals('activity3 : section3', $first_section);
    }
}