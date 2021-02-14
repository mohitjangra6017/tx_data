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
    <Table :data="notifiableEvents" :expandable-rows="true" :hover-off="true">
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
          :data="row.events"
          :expandable-rows="true"
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
            <HeaderCell align="center">
              {{ $str('status', 'core') }}
            </HeaderCell>
            <HeaderCell>
              <span class="sr-only">
                {{ $str('actions', 'core') }}
              </span>
            </HeaderCell>
          </template>
          <template v-slot:row="{ expand, expandState, row: notifiableEvent }">
            <ExpandCell
              v-if="notifiableEvent.notification_preferences.length"
              :aria-label="notifiableEvent.name"
              :expand-state="expandState"
              @click="expand()"
            />
            <Cell v-else />
            <Cell>
              {{ notifiableEvent.name }}
            </Cell>
            <Cell>
              {{ $str('delivery_channels', 'totara_notification') }}
            </Cell>
            <Cell align="center">
              <!-- Toggle Switch goes here !!! -->
              {{ $str('enabled', 'totara_notification') }}
            </Cell>
            <Cell align="center">
              <NotifiableEventAction
                :event-name="notifiableEvent.name"
                @create-custom-notification="
                  $emit('create-custom-notification', {
                    eventName: notifiableEvent.name,
                    eventClassName: notifiableEvent.class_name,
                  })
                "
              />
            </Cell>
          </template>

          <template v-slot:expand-content="{ row: notifiableEvent }">
            <Table
              :data="notifiableEvent.notification_preferences"
              :expandable-rows="false"
              :border-top-hidden="true"
              :border-bottom-hidden="true"
              :hover-off="true"
              :get-id="(unused, index) => index"
            >
              <template v-slot:header-row>
                <HeaderCell>
                  {{ $str('notifications', 'totara_notification') }}
                </HeaderCell>
                <HeaderCell>
                  {{ $str('recipient', 'totara_notification') }}
                </HeaderCell>
                <HeaderCell>
                  {{ $str('schedule', 'totara_notification') }}
                </HeaderCell>
                <HeaderCell>
                  {{ $str('status', 'core') }}
                </HeaderCell>
                <HeaderCell>
                  <span class="sr-only">
                    {{ $str('actions', 'core') }}
                  </span>
                </HeaderCell>
              </template>
              <template v-slot:row="{ row: notificationPreference }">
                <Cell>
                  {{ notificationPreference.title }}
                </Cell>

                <Cell>
                  {{ $str('recipient', 'totara_notification') }}
                </Cell>

                <Cell>
                  {{ $str('schedule', 'totara_notification') }}
                </Cell>

                <Cell>
                  {{ $str('enabled', 'totara_notification') }}
                </Cell>

                <Cell align="center">
                  <!-- Notification action -->
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
import NotifiableEventAction from 'totara_notification/components/action/NotifiableEventAction';

export default {
  components: {
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

    notifiableEvents: {
      type: Array,
      default: [],
      validator(prop) {
        return prop.every(preference => {
          return 'component' in preference && 'events' in preference;
        });
      },
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
