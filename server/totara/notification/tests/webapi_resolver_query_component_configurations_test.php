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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_notification
 */
defined('MOODLE_INTERNAL') || die();

use totara_webapi\phpunit\webapi_phpunit_helper;

class webapi_resolver_query_component_configurations_testcase extends advanced_testcase {
    use webapi_phpunit_helper;


    /**
     * @return void
     */
    public function test_successful_ajax_call(): void {
        $this->setAdminUser();
        $result = $this->parsed_graphql_operation('totara_notification_component_configurations', []);
        $this->assert_webapi_operation_successful($result);

        $actual = $this->get_webapi_operation_data($result);
        $result = reset($actual);

        // Expected data structure:
        // [
        //    'component' => '',
        //    'notifiable_event_configurations' => [
        //         [
        //             'event_key' => '',
        //             'title' => '',
        //             'notification_configurations' => [
        //                 [
        //                     'title' => ''
        //                 ]
        //                 ...
        //             ]
        //         ]
        //         ...
        //     ]
        //]
        self::assertArrayHasKey('component', $result);
        self::assertArrayHasKey('notifiable_event_configurations', $result);
        self::assertIsArray($result['notifiable_event_configurations']);
        self::assertGreaterThanOrEqual(1, $result['notifiable_event_configurations']);

        $notifiable_event = reset($result['notifiable_event_configurations']);
        self::assertArrayHasKey('event_key', $notifiable_event);
        self::assertArrayHasKey('notification_configurations', $notifiable_event);
        self::assertArrayHasKey('title', $notifiable_event);

        self::assertIsArray($notifiable_event['notification_configurations']);
        self::assertGreaterThanOrEqual(0, $notifiable_event['notification_configurations']);
        $notification_config = reset($notifiable_event['notification_configurations']);
        self::assertArrayHasKey('title', $notification_config);
    }

    /**
     * @return void
     */
    public function test_query_component_configurations_by_admin(): void {
        $this->setAdminUser();
        $result = $this->execute_query();

        self::assertNotEmpty($result);
        self::assertIsArray($result);

        // Currently it's only one custom notification event.
        self::assertGreaterThanOrEqual(1, $result);
        $component_configuration = reset($result);

        self::assertArrayHasKey('component', $component_configuration);
        self::assertArrayHasKey('notifiable_event_configurations', $component_configuration);

        $notifiable_event = reset($component_configuration['notifiable_event_configurations']);
        self::assertEquals(get_string('pluginname', explode('\\', $notifiable_event)[0]), $component_configuration['component']);
    }

    /**
     * @return void
     */
    public function test_query_component_configurations_by_authenticated_user(): void {
        $generator = $this->getDataGenerator();
        $user = $generator->create_user();

        $this->setUser($user);
        self::expectException('required_capability_exception');
        $this->execute_query();
    }

    /**
     * @param array|null $args
     * @return mixed|null
     */
    private function execute_query(?array $args = []) {
        return $this->resolve_graphql_query('totara_notification_component_configurations', $args);
    }
}