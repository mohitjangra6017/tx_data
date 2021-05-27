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
 * @author Matthias Bonk <matthias.bonk@totaralearning.com>
 * @package core_user
 */

use core_phpunit\testcase;
use core_user\totara_notification\placeholder\user;
use totara_core\advanced_feature;
use totara_notification\placeholder\option;

defined('MOODLE_INTERNAL') || die();

/**
 * @group totara_notification
 */
class core_user_totara_notification_placeholder_testcase extends testcase {
    /**
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
        user::clear_instance_cache();
    }

    protected function tearDown(): void {
        parent::tearDown();
        user::clear_instance_cache();
    }

    public function test_user_placeholders(): void {
        // Make devs aware they should extend this test when adding placeholders.
        $option_keys = array_map(static function (option $option) {
            return $option->get_key();
        }, user::get_options());
        self::assertEqualsCanonicalizing([
            'first_name',
            'last_name',
            'full_name',
            'full_name_link',
            'username',
            'email',
            'city',
            'country',
            'department',
            'first_name_phonetic',
            'last_name_phonetic',
            'middle_name',
            'alternate_name',
            'time_zone',
        ], $option_keys, 'Please add missing placeholders to test coverage.');

        $user = self::getDataGenerator()->create_user([
            'firstname' => 'Joe',
            'lastname' => 'Brown',
            'username' => 'joebrown123',
            'email' => 'joebrown123@example.com',
            'city' => 'Wellington',
            'country' => 'nz',
            'department' => 'Design',
            'firstnamephonetic' => 'Jou',
            'lastnamephonetic' => 'Braun',
            'middlename' => 'Jim',
            'alternatename' => 'Jayjay',
            'timezone' => 99,
        ]);

        $placeholder_group = user::from_id($user->id);

        self::assertEquals('Joe', $placeholder_group->do_get('first_name'));
        self::assertEquals('Brown', $placeholder_group->do_get('last_name'));
        self::assertEquals('Joe Brown', $placeholder_group->do_get('full_name'));
        self::assertEquals(
            '<a href="https://www.example.com/moodle/user/profile.php?id=' . $user->id . '">Joe Brown</a>',
            $placeholder_group->do_get('full_name_link')
        );
        self::assertEquals('joebrown123', $placeholder_group->do_get('username'));
        self::assertEquals('joebrown123@example.com', $placeholder_group->do_get('email'));
        self::assertEquals('Wellington', $placeholder_group->do_get('city'));
        self::assertEquals('nz', $placeholder_group->do_get('country'));
        self::assertEquals('Design', $placeholder_group->do_get('department'));
        self::assertEquals('Jou', $placeholder_group->do_get('first_name_phonetic'));
        self::assertEquals('Braun', $placeholder_group->do_get('last_name_phonetic'));
        self::assertEquals('Jim', $placeholder_group->do_get('middle_name'));
        self::assertEquals('Jayjay', $placeholder_group->do_get('alternate_name'));
        self::assertEquals('Australia/Perth', $placeholder_group->do_get('time_zone'));

        $other_user = self::getDataGenerator()->create_user();
        self::setUser($other_user);

        // To make email invisible, we have to disable the feature that allows everyone seeing everybody else's email.
        advanced_feature::disable('engage_resources');

        self::assertEquals('(Email not visible)', $placeholder_group->do_get('email'));

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("Invalid key 'password'");
        $placeholder_group->do_get('password');
    }

    public function test_instances_are_cached(): void {
        global $DB;

        self::setAdminUser();
        $user1 = self::getDataGenerator()->create_user();
        $user2 = self::getDataGenerator()->create_user();

        $query_count = $DB->perf_get_reads();
        user::from_id($user1->id);
        self::assertEquals($query_count + 1, $DB->perf_get_reads());

        user::from_id($user1->id);
        self::assertEquals($query_count + 1, $DB->perf_get_reads());

        user::from_id($user2->id);
        self::assertEquals($query_count + 2, $DB->perf_get_reads());

        user::from_id($user1->id);
        user::from_id($user2->id);
        self::assertEquals($query_count + 2, $DB->perf_get_reads());
    }
}