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
 * @package contentmarketplace_linkedin
 */

use contentmarketplace_linkedin\entity\user_completion;
use contentmarketplace_linkedin\testing\generator as linkedin_generator;
use contentmarketplace_linkedin\userdata\completion;
use core\testing\generator as core_generator;
use core_phpunit\testcase;
use totara_userdata\userdata\target_user;

/**
 * @group totara_contentmarketplace
 */
class contentmarketplace_linkedin_userdata_testcase extends testcase {

    public function test_completion_userdata_handling(): void {
        $context = context_system::instance();

        $learning_object1 = linkedin_generator::instance()->create_learning_object('one', [
            'title' => '<script>One</script>',
        ]);
        $learning_object2 = linkedin_generator::instance()->create_learning_object('two', [
            'title' => 'Two',
        ]);

        $user1 = core_generator::instance()->create_user();
        $user2 = core_generator::instance()->create_user();

        $user1_completion1 = [
            'user_id' => $user1->id,
            'learning_object_urn' => $learning_object1->urn,
            'progress' => 20,
            'completion' => 0,
            'time_created' => 123,
        ];
        $user1_completion1_entity = (new user_completion($user1_completion1))->save();
        $user1_completion2 = [
            'user_id' => $user1->id,
            'learning_object_urn' => $learning_object2->urn,
            'progress' => 25,
            'completion' => 0,
            'time_created' => 456,
        ];
        $user1_completion2_entity = (new user_completion($user1_completion2))->save();

        $user2_completion1 = [
            'user_id' => $user2->id,
            'learning_object_urn' => $learning_object2->urn,
            'progress' => 100,
            'completion' => 1,
            'time_created' => 789,
        ];
        $user2_completion1_entity = (new user_completion($user2_completion1))->save();

        $this->assertEquals(2, completion::execute_count(new target_user($user1), $context));
        $this->assertEquals(1, completion::execute_count(new target_user($user2), $context));

        $user1_export = completion::execute_export(new target_user($user1), $context);
        $this->assertEquals([
            'completion' => [
                [
                    'id' => $user1_completion1_entity->id,
                    'learning_object_urn' => 'one',
                    'learning_object_title' => 'One',
                    'progress' => 20,
                    'completion' => false,
                    'time_created' => 123,
                ],
                [
                    'id' => $user1_completion2_entity->id,
                    'learning_object_urn' => 'two',
                    'learning_object_title' => 'Two',
                    'progress' => 25,
                    'completion' => false,
                    'time_created' => 456,
                ],
            ],
        ], $user1_export->data);

        $user2_export = completion::execute_export(new target_user($user2), $context);
        $this->assertEquals([
            'completion' => [
                [
                    'id' => $user2_completion1_entity->id,
                    'learning_object_urn' => 'two',
                    'learning_object_title' => 'Two',
                    'progress' => 100,
                    'completion' => true,
                    'time_created' => 789,
                ],
            ],
        ], $user2_export->data);

        $this->assertEquals(2, user_completion::repository()->where('user_id', $user1->id)->count());
        $this->assertEquals(1, user_completion::repository()->where('user_id', $user2->id)->count());

        $this->assertEquals(completion::RESULT_STATUS_SUCCESS, completion::execute_purge(new target_user($user1), $context));

        $this->assertEquals(0, user_completion::repository()->where('user_id', $user1->id)->count());
        $this->assertEquals(1, user_completion::repository()->where('user_id', $user2->id)->count());
    }

}
