<!--
  This file is part of Totara Enterprise Extensions.

  Copyright (C) 2021 onwards Totara Learning Solutions LTD

  Totara Enterprise Extensions is provided only to Totara
  Learning Solutions LTD's customers and partners, pursuant to
  the terms and conditions of a separate agreement with Totara
  Learning Solutions LTD or its affiliate.

  If you do not have an agreement with Totara Learning Solutions
  LTD, you may not access, use, modify, or distribute this software.
  Please contact [licensing@totaralearning.com] for more information.

  @author Qingyang.liu <qingyang.liu@totaralearning.com>
  @module totara_notification
-->

<template>
  <div class="tui-notificationTable">
    <Table
      :data="eventResolvers"
      :expandable-rows="true"
      :expand-multiple-rows="true"
      :hover-off="true"
    >
      <template v-slot:header-row>
        <ExpandCell :header="true" />
      </template>

      <template v-slot:row="{ row, expand, expandState }">
        <ExpandCell
          :aria-label="row.plugin_name"
          :expand-state="expandState"
          @click="expand()"
        />
        <Cell>
          {{ row.plugin_name }}
        </Cell>
      </template>

      <template v-slot:expand-content="{ row }">
        <Table
          :data="row.resolvers"
          :expandable-rows="true"
          :expand-multiple-rows="true"
          :border-top-hidden="true"
          :border-bottom-hidden="true"
          :hover-off="true"
        >
          <template v-slot:header-row>
            <ExpandCell :header="true" />
            <HeaderCell>
              {{ $str('notifiable_events', 'totara_notification') }}
            </HeaderCell>
            <HeaderCell>
              {{ $str('delivery_channels', 'totara_notification') }}
            </HeaderCell>
            <HeaderCell align="start">
              {{ $str('status', 'core') }}
            </HeaderCell>
            <HeaderCell>
              <span class="sr-only">
                {{ $str('actions', 'core') }}
              </span>
            </HeaderCell>
          </template>
          <template v-slot:row="{ expand, expandState, row: resolver }">
            <ExpandCell
              v-if="resolver.notification_preferences.length"
              :aria-label="resolver.name"
              :expand-state="expandState"
              @click="expand()"
            />
            <Cell v-else />
            <Cell>
              {{ resolver.name }}
            </Cell>
            <Cell>
              {{
                display_delivery_channels(resolver.default_delivery_channels)
              }}
            </Cell>
            <Cell align="start">
              <!-- Toggle Switch goes here !!! -->
              {{ $str('enabled', 'totara_notification') }}
            </Cell>
            <Cell align="end">
              <NotifiableEventAction
                :resolver-name="resolver.name"
                :show-delivery-preference-option="showDeliveryPreferenceOption"
                @create-custom-notification="
                  $emit('create-custom-notification', {
                    resolverClassName: resolver.class_name,
                    scheduleTypes: resolver.valid_schedules,
                    recipients: resolver.recipients,
                  })
                "
                @update-delivery-preferences="
                  $emit('update-delivery-preferences', {
                    resolverClassName: resolver.class_name,
                    resolverLabel: resolver.name,
                    defaultDeliveryChannels: resolver.default_delivery_channels,
                  })
                "
              />
            </Cell>
          </template>

          <template v-slot:expand-content="{ row: resolver }">
            <Table
              :data="resolver.notification_preferences"
              :expandable-rows="false"
              :expand-multiple-rows="true"
              :border-top-hidden="true"
              :border-bottom-hidden="true"
              :hover-off="true"
              :get-id="(unused, index) => index"
            >
              <template v-slot:header-row>
                <HeaderCell align="start">
                  {{ $str('notifications', 'totara_notification') }}
                </HeaderCell>
                <HeaderCell align="start">
                  {{ $str('recipient', 'totara_notification') }}
                </HeaderCell>
                <HeaderCell align="start">
                  {{ $str('schedule', 'totara_notification') }}
                </HeaderCell>
                <HeaderCell align="start">
                  {{ $str('status', 'core') }}
                </HeaderCell>
                <HeaderCell>
                  <span class="sr-only">
                    {{ $str('actions', 'core') }}
                  </span>
                </HeaderCell>
              </template>
              <template v-slot:row="{ row: notificationPreference }">
                <Cell align="start">
                  {{ notificationPreference.title }}
                </Cell>

                <Cell align="start">
                  {{ notificationPreference.recipient.name }}
                </Cell>

                <Cell align="start">
                  {{ notificationPreference.schedule_label }}
                </Cell>

                <Cell align="start">
                  {{ $str('enabled', 'totara_notification') }}
                </Cell>

                <Cell align="end">
                  <NotificationAction
                    :preference-title="notificationPreference.title"
                    :is-deletable="
                      notificationPreference.is_custom &&
                        notificationPreference.parent_id == null
                    "
                    @edit-notification="
                      $emit(
                        'edit-notification',
                        notificationPreference,
                        resolver.valid_schedules,
                        resolver.recipients
                      )
                    "
                    @delete-notification="
                      $emit('delete-notification', notificationPreference)
                    "
                  />
                </Cell>
              </template>
            </Table>
          </template>
        </Table>
      </template>
    </Table>
  </div>
</template>

<script>
import Cell from 'tui/components/datatable/Cell';
import ExpandCell from 'tui/components/datatable/ExpandCell';
import HeaderCell from 'tui/components/datatable/HeaderCell';
import Table from 'tui/components/datatable/Table';
import NotificationAction from 'totara_notification/components/action/NotificationAction';
import NotifiableEventAction from 'totara_notification/components/action/NotifiableEventAction';

export default {
  components: {
    NotificationAction,
    HeaderCell,
    Cell,
    Table,
    ExpandCell,
    NotifiableEventAction,
  },

  props: {
    contextId: {
      type: Number,
      required: true,
    },

    eventResolvers: {
      type: Array,
      default: () => [],
      validator(prop) {
        return prop.every(preference => {
          return (
            'component' in preference &&
            'resolvers' in preference &&
            'recipients' in preference &&
            'plugin_name' in preference
          );
        });
      },
    },

    showDeliveryPreferenceOption: Boolean,
  },

  methods: {
    /**
     * Squash the collection of delivery channel labels into a string
     *
     * @param {Array} channels
     * @returns {string}
     */
    display_delivery_channels(channels) {
      return channels
        .filter(({ is_enabled }) => is_enabled)
        .map(({ label }) => label)
        .join('; ');
    },
  },
};
</script>
<lang-strings>
{
  "totara_notification": [
    "notifiable_events",
    "recipient",
    "schedule",
    "delivery_channels",
    "disable",
    "enabled",
    "notifications"
  ],
  "core": [
    "actions",
    "status"
  ]
}
</lang-strings>
