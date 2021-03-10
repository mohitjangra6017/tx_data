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
 * @author Nathan Lewis <nathan.lewis@totaralearning.com>
 * @package totara_notification
 */
global $CFG;

use totara_comment\totara_notification\resolver\comment_created;
use totara_comment\totara_notification\resolver\comment_soft_deleted;
use totara_core\extended_context;
use totara_notification\entity\notifiable_event_preference as notification_event_preference_entity;
use totara_notification\model\notifiable_event_preference as notification_event_preference_model;
use totara_notification\model\notification_preference as notification_preference_model;
use totara_notification\testing\generator;

require_once("{$CFG->dirroot}/totara/notification/db/upgradelib.php");

class totara_notification_upgradelib_testcase extends advanced_testcase {

    private function create_new_notification_preference(): notification_preference_model {
        $generator = generator::instance();

        return $generator->create_notification_preference(comment_created::class);
    }

    private function set_legacy_preference_status(string $provider_name, string $provider_component, bool $enabled): void {
        $name = $provider_component . '_' . $provider_name . '_disabled';
        set_config($name, (int)(!$enabled), 'message');
    }

    private function set_legacy_preference_permissions(
        string $provider_name,
        string $provider_component,
        string $output,
        string $permitted // 'disallowed', 'permitted' or 'forced'
    ): void {
        $name = $output . '_provider_' . $provider_component . '_' . $provider_name . '_permitted';
        set_config($name, $permitted, 'message');
    }

    private function set_legacy_preference_default_outputs(
        string $provider_name,
        string $provider_component,
        string $outputs_enabled_online,
        string $outputs_enabled_offline
    ): void {
        $name = 'message_provider_' . $provider_component . '_' . $provider_name;
        set_config($name . '_loggedin', $outputs_enabled_online, 'message');
        set_config($name . '_loggedoff', $outputs_enabled_offline, 'message');
    }

    /**
     * Test that the correct outputs are enabled and disabled in the new notifiable event.
     */
    public function test_totara_notification_migrate_notifiable_event_prefs_with_existing_record(): void {
        $control_notifiable_event_entity = new notification_event_preference_entity();
        $control_notifiable_event_entity->resolver_class_name = comment_created::class;
        $control_notifiable_event_entity->context_id = context_system::instance()->id;
        $control_notifiable_event_entity->save();
        $control_notifiable_event = notification_event_preference_model::from_entity($control_notifiable_event_entity);
        $control_enabled_delivery_channels = $control_notifiable_event->get_default_delivery_channels();

        $target_notifiable_event_entity = new notification_event_preference_entity();
        $target_notifiable_event_entity->resolver_class_name = comment_soft_deleted::class;
        $target_notifiable_event_entity->context_id = context_system::instance()->id;
        $target_notifiable_event_entity->save();
        $target_notifiable_event = notification_event_preference_model::from_entity($target_notifiable_event_entity);

        $this->set_legacy_preference_default_outputs(
            'alert',
            'totara_message',
            'totara_alert,msteams',
            'totara_alert,email'
        );

        totara_notification_migrate_notifiable_event_prefs(
            $target_notifiable_event->resolver_class_name,
            'alert',
            'totara_message'
        );
        $target_notifiable_event->refresh();
        $target_delivery_channels_enabled = $target_notifiable_event->get_default_delivery_channels();

        // Check that the control is unaffected.
        $control_notifiable_event->refresh();
        self::assertEquals($control_enabled_delivery_channels, $control_notifiable_event->get_default_delivery_channels());

        // Case where both online and offline are off.
        self::assertFalse($target_delivery_channels_enabled['popup']->is_enabled);

        // Case where both online and offline are on.
        self::assertTrue($target_delivery_channels_enabled['totara_alert']->is_enabled);

        // Case where online is on and offline is off.
        self::assertTrue($target_delivery_channels_enabled['msteams']->is_enabled);

        // Case where online is off and offline is on.
        self::assertTrue($target_delivery_channels_enabled['email']->is_enabled);
    }

    public function test_totara_notification_migrate_notifiable_event_prefs_with_no_record(): void {
        $extended_context = extended_context::make_with_context(context_system::instance());

        $this->set_legacy_preference_default_outputs(
            'alert',
            'totara_message',
            'totara_alert,msteams',
            'totara_alert,email'
        );

        totara_notification_migrate_notifiable_event_prefs(
            comment_created::class,
            'alert',
            'totara_message'
        );
        $target_notifiable_event_entity = notification_event_preference_entity::repository()
            ->for_context(comment_created::class, $extended_context);
        $target_notifiable_event = notification_event_preference_model::from_entity($target_notifiable_event_entity);
        $target_delivery_channels_enabled = $target_notifiable_event->get_default_delivery_channels();

        // Check that the control is unaffected.
        $control_notifiable_event_entity = notification_event_preference_entity::repository()
            ->for_context(comment_soft_deleted::class, $extended_context);
        self::assertEmpty($control_notifiable_event_entity);

        // Case where both online and offline are off.
        self::assertFalse($target_delivery_channels_enabled['popup']->is_enabled);

        // Case where both online and offline are on.
        self::assertTrue($target_delivery_channels_enabled['totara_alert']->is_enabled);

        // Case where online is on and offline is off.
        self::assertTrue($target_delivery_channels_enabled['msteams']->is_enabled);

        // Case where online is off and offline is on.
        self::assertTrue($target_delivery_channels_enabled['email']->is_enabled);
    }

        /**
     * Test that the enabled/disabled legacy notification preference results in a new notification that is enabled or disabled.
     */
    public function test_totara_notification_migrate_notification_prefs_status(): void {
        $control_notif_preference = $this->create_new_notification_preference();
        $control_enabled = $control_notif_preference->get_enabled();

        $new_notif_preference = $this->create_new_notification_preference();

        // Case where legacy notification is enabled.
        $this->set_legacy_preference_status('alert', 'totara_message', true);
        totara_notification_migrate_notification_prefs(
            $new_notif_preference->get_id(),
            'alert',
            'totara_message'
        );
        $new_notif_preference->refresh();
        self::assertTrue($new_notif_preference->get_enabled());

        // The control is unaffected.
        $control_notif_preference->refresh();
        self::assertEquals($control_enabled, $control_notif_preference->get_enabled());

        // Case where legacy notification is disabled.
        $this->set_legacy_preference_status('alert', 'totara_message', false);
        totara_notification_migrate_notification_prefs(
            $new_notif_preference->get_id(),
            'alert',
            'totara_message'
        );
        $new_notif_preference->refresh();
        self::assertFalse($new_notif_preference->get_enabled());

        // The control is unaffected.
        $control_notif_preference->refresh();
        self::assertEquals($control_enabled, $control_notif_preference->get_enabled());
    }

    /**
     * Test that a legacy notification that is 'locked' results in forced delivery in the new notification.
     * Also tests that 'disallowed' and 'permitted' have no effect.
     */
    public function test_totara_notification_migrate_notification_prefs_permissions(): void {
        $control_notif_preference = $this->create_new_notification_preference();
        $control_forced_delivery_channels = $control_notif_preference->get_forced_delivery_channels();
        $new_notif_preference = $this->create_new_notification_preference();

        $this->set_legacy_preference_permissions(
            'alert',
            'totara_message',
            'totara_alert',
            'forced'
        );

        $this->set_legacy_preference_permissions(
            'alert',
            'totara_message',
            'email',
            'disallowed'
        );

        $this->set_legacy_preference_permissions(
            'alert',
            'totara_message',
            'popup',
            'permitted'
        );

        totara_notification_migrate_notification_prefs(
            $new_notif_preference->get_id(),
            'alert',
            'totara_message'
        );
        $new_notif_preference->refresh();
        $forcer_delivery_channels = $new_notif_preference->get_forced_delivery_channels();

        // The control is unaffected.
        $control_notif_preference->refresh();
        self::assertEquals($control_forced_delivery_channels, $control_notif_preference->get_forced_delivery_channels());

        // Check that a forced legacy output results in forced delivery in the new notification.
        self::assertContains('totara_alert', $forcer_delivery_channels);

        // Check that a permitted legacy output results in no forced delivery in the new notification.
        self::assertNotContains('email', $forcer_delivery_channels);

        // Check that a disabled legacy output results in no forced delivery in the new notification.
        self::assertNotContains('popup', $forcer_delivery_channels);
    }
}