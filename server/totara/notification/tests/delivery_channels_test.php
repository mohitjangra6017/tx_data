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
 * @author Cody Finegan <cody.finegan@totaralearning.com>
 * @package totara_notification
 */

use core_phpunit\testcase;
use totara_notification\entity\notifiable_event_preference as entity;
use totara_notification\loader\delivery_channel_loader;
use totara_notification\manager\notification_queue_manager;
use totara_notification\model\notifiable_event_preference as model;
use totara_notification\testing\generator;
use totara_notification_mock_delivery_channel as mock_channel;
use totara_notification_mock_delivery_channel_second as mock_channel_second;
use totara_notification_mock_delivery_channel_third as mock_channel_third;
use totara_notification_mock_notifiable_event_resolver as mock_resolver;

/**
 * @group totara_notification
 */
class totara_notification_delivery_channels_testcase extends testcase {
    /**
     * @var model
     */
    protected $model;

    /**
     * @var entity
     */
    protected $entity;

    /**
     * Test delivery channel values stored in the entity are converted back to objects.
     */
    public function test_default_delivery_channels_convert_from_entity(): void {
        // Single entry
        $this->entity->default_delivery_channels = ',second,';
        $results = $this->model->default_delivery_channels;

        self::assertIsArray($results);
        self::assertCount(3, $results);

        // Check that all are disabled except email
        foreach ($results as $component => $result) {
            if ($component === 'second') {
                self::assertTrue($result->is_enabled);
            } else {
                self::assertFalse($result->is_enabled, $component);
            }
        }

        // Multiple enabled
        $this->entity->default_delivery_channels = ',second,third,';
        $results = $this->model->default_delivery_channels;

        self::assertIsArray($results);
        self::assertCount(3, $results);

        // Check that all are disabled except msteams & popup
        foreach ($results as $component => $result) {
            if ($component === 'second' || $component === 'third') {
                self::assertTrue($result->is_enabled);
            } else {
                self::assertFalse($result->is_enabled);
            }
        }

        // Default entry
        $overrides = [
            mock_channel_second::set_default(true),
            mock_channel_third::set_default(false),
        ];
        mock_resolver::set_default_delivery_channels($overrides);
        $default_channels = delivery_channel_loader::get_for_event_resolver(mock_resolver::class);
        $this->entity->default_delivery_channels = null;
        $results = $this->model->default_delivery_channels;

        self::assertIsArray($results);
        self::assertCount(3, $results);

        foreach ($results as $component => $result) {
            self::assertArrayHasKey($component, $default_channels);
            $default = $default_channels[$component];
            self::assertEquals($default->is_enabled, $result->is_enabled);
        }

        // Check that an override is processed
        $this->entity->default_delivery_channels = ',first,';
        $results = $this->model->default_delivery_channels;

        // Check that all are disabled except popup
        foreach ($results as $component => $result) {
            if ($component === 'first') {
                self::assertTrue($result->is_enabled);
            } else {
                self::assertFalse($result->is_enabled);
            }
        }
    }

    /**
     * Test delivery channel values given to the model are converted to the string in the entity
     */
    public function test_default_delivery_channels_convert_from_model(): void {
        // Single entry
        $channels = delivery_channel_loader::get_defaults();
        foreach ($channels as $component => $channel) {
            $channel->set_enabled($component === 'third');
        }
        $this->model->set_default_delivery_channels($channels);
        self::assertSame(',third,', $this->entity->default_delivery_channels);

        // Multiple entries
        $channels = delivery_channel_loader::get_defaults();
        foreach ($channels as $component => $channel) {
            $channel->set_enabled($component === 'third' || $component === 'first');
        }
        $this->model->set_default_delivery_channels($channels);
        self::assertSame(',first,third,', $this->entity->default_delivery_channels);

        // Default entry
        $this->model->set_default_delivery_channels(null);
        self::assertNull($this->entity->default_delivery_channels);
    }

    /**
     * Test the message queue is filtered by the resolver's set delivery channels
     */
    public function test_expected_message_outputs_filtered(): void {
        $mock_message_providers = [
            'first' => [],
            'second' => [],
            'third' => [],
        ];

        $channels = [
            mock_channel::set_default(true), // component = first
            mock_channel_second::set_default(true), // component = second
        ];
        mock_resolver::set_default_delivery_channels($channels);

        // Test by loading the get_active_message_processors, and check what's returned
        // We should only see email & msteams here as the rest should have been removed.
        $resolver = new mock_resolver([]);

        // As this method is private, to test it we'll need to reflect our way inside. It also means
        // we can pass in an array of mock message_output data, as we only want to test our filter works.
        $method = new ReflectionMethod(
            notification_queue_manager::class,
            'filter_message_processors_by_delivery_channel'
        );
        $method->setAccessible(true);
        $results = $method->invoke(new notification_queue_manager(), $resolver, $mock_message_providers);

        self::assertCount(2, $results);
        self::assertArrayHasKey('first', $results);
        self::assertArrayHasKey('second', $results);
        self::assertArrayNotHasKey('third', $results);

        // Disable popup & rerun
        $channels = [
            mock_channel::set_default(true), // component = first
            mock_channel_second::set_default(false), // component = second
        ];
        mock_resolver::set_default_delivery_channels($channels);

        $results = $method->invoke(new notification_queue_manager(), $resolver, $mock_message_providers);

        self::assertCount(1, $results);
        self::assertArrayHasKey('first', $results);
        self::assertArrayNotHasKey('second', $results);
    }

    /**
     * Test the static reading of properties works correctly.
     */
    public function test_delivery_channel_property_reads(): void {
        mock_channel::set_attribute('label', 'my test label');

        $channel = mock_channel::make(true);
        self::assertTrue($channel->is_enabled);
        self::assertEquals('my test label', $channel->label);
        self::assertNull($channel->not_a_property);
    }

    /**
     * Create a mock model to test against
     */
    protected function setUp(): void {
        $generator = generator::instance();
        $generator->include_mock_notifiable_event_resolver();
        $generator->include_mock_delivery_channels();

        // Always reset the delivery channels back to nothing
        mock_resolver::set_default_delivery_channels([]);

        $this->entity = new entity(null, false, true);
        $this->entity->resolver_class_name = mock_resolver::class;
        $this->model = model::from_entity($this->entity);

        // This lets us test the delivery channels without creating a dependency on message_* plugins
        delivery_channel_loader::set_definitions([
            mock_channel::class,
            mock_channel_second::class,
            mock_channel_third::class,
        ]);
    }

    /**
     * Remove the hanging models
     */
    protected function tearDown(): void {
        $this->model = null;
        $this->entity = null;
        mock_resolver::set_default_delivery_channels([]);
        mock_channel::clear();
        mock_channel_second::clear();
        mock_channel_third::clear();
        delivery_channel_loader::set_definitions(null);
    }
}