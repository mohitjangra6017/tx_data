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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
use core_phpunit\testcase;
use totara_notification\placeholder\abstraction\single_placeholder;
use totara_notification\placeholder\placeholder_option;
use core_user\totara_notification\placeholder\user;
use totara_notification\testing\generator;

class totara_notification_instantiate_placeholder_option_testcase extends testcase {
    /**
     * @return void
     */
    public function test_instantiate_placeholder_instance_with_invalid_callback(): void {
        $generator = generator::instance();
        $group = placeholder_option::create(
            'recipient',
            user::class,
            $generator->give_my_mock_lang_string('boom'),
            function (string $boom): user {
                return user::from_id(1);
            }
        );

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('Invalid instantiation callback had been given');

        $group->get_placeholder_instance([], 1);
    }

    /**
     * @return void
     */
    public function test_instantiate_placeholder_instance_with_valid_callback_and_zero_parameter(): void {
        $generator = generator::instance();
        $user = get_admin();

        $group = placeholder_option::create(
            'recipient',
            user::class,
            $generator->give_my_mock_lang_string('boom'),
            function () use ($user): user {
                return user::from_id($user->id);
            }
        );

        /** @var single_placeholder $placeholder */
        $placeholder = $group->get_placeholder_instance([], $user->id);
        self::assertInstanceOf(single_placeholder::class, $placeholder);

        self::assertEquals(fullname($user), $placeholder->get('fullname'));
        self::assertEquals($user->firstname, $placeholder->get('firstname'));
        self::assertEquals($user->lastname, $placeholder->get('lastname'));
    }

    /**
     * @return void
     */
    public function test_instantiate_placeholder_instance_with_valid_callback_and_integer_parameter(): void {
        $generator = generator::instance();
        $user = get_admin();

        $group = placeholder_option::create(
            'recipient',
            user::class,
            $generator->give_my_mock_lang_string('boom'),
            function (int $user_id): user {
                return user::from_id($user_id);
            }
        );

        /** @var single_placeholder $placeholder */
        $placeholder = $group->get_placeholder_instance([], $user->id);
        self::assertInstanceOf(single_placeholder::class, $placeholder);

        self::assertEquals(fullname($user), $placeholder->get('fullname'));
        self::assertEquals($user->firstname, $placeholder->get('firstname'));
        self::assertEquals($user->lastname, $placeholder->get('lastname'));
    }

    /**
     * @return void
     */
    public function test_instantiate_placeholder_instance_with_valid_callback_and_array_parameter(): void {
        $generator = generator::instance();
        $user = get_admin();

        $group = placeholder_option::create(
            'recipient',
            user::class,
            $generator->give_my_mock_lang_string('boom'),
            function (array $event_data): user {
                return user::from_id($event_data['user_id']);
            }
        );

        /** @var single_placeholder $placeholder */
        $placeholder = $group->get_placeholder_instance(['user_id' => $user->id], $user->id);
        self::assertInstanceOf(single_placeholder::class, $placeholder);

        self::assertEquals(fullname($user), $placeholder->get('fullname'));
        self::assertEquals($user->firstname, $placeholder->get('firstname'));
        self::assertEquals($user->lastname, $placeholder->get('lastname'));
    }

    /**
     * @return void
     */
    public function test_instantiate_placeholder_instance_with_valid_callback_and_two_parameters(): void {
        $generator = generator::instance();
        $user = get_admin();

        $that = $this;
        $group = placeholder_option::create(
            'recipient',
            user::class,
            $generator->give_my_mock_lang_string('boom'),
            function (array $event_data, int $user_id) use ($that, $user): user {
                // Just several simple assertions before returns the instance of placeholder.
                $that::assertEquals($user_id, $user->id);
                $that::assertArrayHasKey('user_id', $event_data);
                $that::assertEquals($event_data['user_id'], $user_id);
                $that::assertEquals($event_data['user_id'], $user->id);

                return user::from_id($event_data['user_id']);
            }
        );

        /** @var single_placeholder $placeholder */
        $placeholder = $group->get_placeholder_instance(['user_id' => $user->id], $user->id);
        self::assertInstanceOf(single_placeholder::class, $placeholder);

        self::assertEquals(fullname($user), $placeholder->get('fullname'));
        self::assertEquals($user->firstname, $placeholder->get('firstname'));
        self::assertEquals($user->lastname, $placeholder->get('lastname'));
    }

    /**
     * @return void
     */
    public function test_instantiate_placeholder_instance_with_valid_callback_and_unamed_parameter(): void {
        $generator = generator::instance();
        $user = get_admin();

        $that = $this;
        $group = placeholder_option::create(
            'recipient',
            user::class,
            $generator->give_my_mock_lang_string('boom'),
            function ($event_data) use ($that, $user): user {
                // Just several  simple assertions before returns the instance of placeholder.
                $that::assertIsArray($event_data);
                $that::assertArrayHasKey('user_id', $event_data);
                $that::assertEquals($event_data['user_id'], $user->id);

                return user::from_id($event_data['user_id']);
            }
        );

        /** @var single_placeholder $placeholder */
        $placeholder = $group->get_placeholder_instance(['user_id' => $user->id], $user->id);
        self::assertInstanceOf(single_placeholder::class, $placeholder);

        self::assertEquals(fullname($user), $placeholder->get('fullname'));
        self::assertEquals($user->firstname, $placeholder->get('firstname'));
        self::assertEquals($user->lastname, $placeholder->get('lastname'));
    }
}