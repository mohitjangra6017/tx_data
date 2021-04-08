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

use mod_perform\entity\activity\activity as activity_entity;
use mod_perform\models\activity\activity;
use totara_webapi\phpunit\webapi_phpunit_helper;

require_once(__DIR__ . '/section_element_reference_test.php');

/**
 * @group perform
 * @group perform_element
 */
class mod_perform_reference_element_activity_deletion_testcase extends section_element_reference_testcase {
    use webapi_phpunit_helper;

    public const QUERY = 'mod_perform_activity_deletion_validation';

    public function test_delete_watcher(): void {
        $this->create_test_data();

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessageMatches("/This activity cannot be deleted/");

        activity::load_by_id($this->source_activity->id)->delete();
    }

    public function test_delete_watcher_redisplay_same_activity(): void {
        $this->create_test_data_referencing_same_section();

        // Deleting activity should not be blocked.
        activity::load_by_id($this->self_reference_activity->id)->delete();
        self::assertNull(activity_entity::repository()->find($this->self_reference_activity->id));
    }

    public function test_query_validation_with_problems(): void {
        $this->create_test_data();

        $args = ['input' => ['activity_id' => $this->source_activity->id]];
        $result = $this->resolve_graphql_query(self::QUERY, $args);

        $this->assertEquals("Cannot delete activity", $result['title']);
        $this->assertFalse($result['can_delete']);

        $description = $result['reason']['description'];
        $result_data = $result['reason']['data'];

        $this->assertEquals('This activity cannot be deleted, because it contains questions that are being referenced by other elements:', $description);

        // Note the aggregation element is not a problem because it is in the same activity.
        self::assertCount(1, $result_data);
        self::assertEquals('referencing_redisplay_activity : referencing_redisplay_section (Response redisplay)', $result_data[0]);
    }

    public function test_query_validation_redisplay_same_activity(): void {
        $this->create_test_data_referencing_same_section();

        $args = ['input' => ['activity_id' => $this->self_reference_activity->id]];
        $result = $this->resolve_graphql_query(self::QUERY, $args);

        self::assertTrue($result['can_delete']);
        self::assertNull($result['reason']['description']);
        self::assertNull($result['reason']['data']);
    }

}