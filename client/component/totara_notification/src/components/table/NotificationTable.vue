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
  <div>
    <CollapsibleGroupToggle
      v-model="expanded"
      :align-end="false"
      :transparent="false"
    />
    <div class="tui-notificationTable">
      <Collapsible
        v-for="eventResolver in eventResolvers"
        :key="eventResolver.component"
        v-model="expanded[eventResolver.component]"
        :label="eventResolver.plugin_name"
        :indent-contents="true"
      >
        <Table
          :data="eventResolver.resolvers"
          :expandable-rows="true"
          :expand-multiple-rows="true"
          :border-bottom-hidden="true"
          :hover-off="true"
          :indent-expanded-contents="true"
          :stealth-expanded="true"
        >
          <template v-slot:header-row>
            <ExpandCell :hidden="true" :header="true" />
            <HeaderCell size="4">
              {{ $str('notifiable_events', 'totara_notification') }}
            </HeaderCell>
            <HeaderCell size="4">
              <div class="tui-notificationTable__header">
                {{ $str('delivery_channels', 'totara_notification') }}
                <InfoIconButton
                  :aria-label="
                    $str(
                      'delivery_preferences_helptext_aria',
                      'totara_notification'
                    )
                  "
                >
                  {{
                    $str(
                      'default_delivery_preferences_helptext',
                      'totara_notification'
                    )
                  }}
                </InfoIconButton>
              </div>
            </HeaderCell>

            <HeaderCell align="start" size="2">
              <div class="tui-notificationTable__header">
                {{ $str('enabled', 'totara_notification') }}
                <InfoIconButton
                  :aria-label="
                    $str('enabled_helptext_aria', 'totara_notification')
                  "
                >
                  {{ $str('enabled_helptext', 'totara_notification') }}
                </InfoIconButton>
              </div>
            </HeaderCell>
            <HeaderCell size="1">
              <span class="sr-only">
                {{ $str('actions', 'core') }}
              </span>
            </HeaderCell>
          </template>
          <template v-slot:row="{ expand, expandState, row: resolver }">
            <ExpandCell
              :aria-label="resolver.name"
              :expand-state="expandState"
              @click="expand()"
            />
            <Cell
              size="4"
              :column-header="$str('notifiable_events', 'totara_notification')"
            >
              {{ resolver.name }}
            </Cell>
            <Cell
              size="4"
              :column-header="$str('delivery_channels', 'totara_notification')"
            >
              {{
                display_delivery_channels(resolver.default_delivery_channels)
              }}
            </Cell>
            <Cell
              align="start"
              size="2"
              :column-header="$str('enabled', 'totara_notification')"
            >
              <ToggleSwitch
                :aria-label="
                  $str('enable_status', 'totara_notification', resolver.name)
                "
                :value="resolver.status.is_enabled"
                :toggle-only="true"
                @input="onStatusToggle($event, resolver)"
              />
            </Cell>
            <Cell align="end" size="1">
              <NotifiableEventAction
                :resolver-name="resolver.name"
                :show-delivery-preference-option="showDeliveryPreferenceOption"
                @create-custom-notification="
                  $emit('create-custom-notification', {
                    resolverClassName: resolver.class_name,
                    scheduleTypes: resolver.valid_schedules,
                    recipients: resolver.recipients,
                    deliveryChannels: resolver.default_delivery_channels,
                    additionalCriteriaComponent:
                      resolver.additional_criteria_component,
                  })
                "
                @update-delivery-preferences="
                  $emit('update-delivery-preferences', {
                    resolverClassName: resolver.class_name,
                    resolverLabel: resolver.name,
                    defaultDeliveryChannels: resolver.default_delivery_channels,
                    additionalCriteriaComponent:
                      resolver.additional_criteria_component,
                  })
                "
              />
            </Cell>
          </template>

          <template v-slot:expand-content="{ row: resolver }">
            <Table
              v-if="resolver.notification_preferences.length"
              :data="resolver.notification_preferences"
              :expandable-rows="false"
              :expand-multiple-rows="true"
              :hover-off="true"
              :get-id="(unused, index) => index"
              :indent-expanded-contents="true"
              :stealth-expanded="true"
              :class="{
                'tui-notificationTable__subTable': !resolver.status.is_enabled,
              }"
            >
              <template v-slot:header-row>
                <HeaderCell align="start" size="4">
                  {{ $str('notifications', 'totara_notification') }}
                </HeaderCell>
                <HeaderCell align="start" size="4">
                  {{ $str('type', 'totara_notification') }}
                </HeaderCell>
                <HeaderCell align="start" size="4">
                  {{ $str('recipient', 'totara_notification') }}
                </HeaderCell>
                <HeaderCell align="start" size="3">
                  {{ $str('schedule', 'totara_notification') }}
                </HeaderCell>
                <HeaderCell align="start" size="2">
                  {{ $str('status', 'core') }}
                </HeaderCell>
                <HeaderCell size="1">
                  <span class="sr-only">
                    {{ $str('actions', 'core') }}
                  </span>
                </HeaderCell>
              </template>
              <template v-slot:row="{ row: notificationPreference }">
                <Cell
                  align="start"
                  size="4"
                  :column-header="$str('notifications', 'totara_notification')"
                >
                  {{ notificationPreference.title }}
                </Cell>

                <Cell
                  align="start"
                  size="4"
                  :column-header="$str('type', 'totara_notification')"
                >
                  <template
                    v-if="
                      notificationPreference.is_custom &&
                        !notificationPreference.ancestor_id
                    "
                  >
                    {{ $str('custom', 'totara_notification') }}
                  </template>
                  <template
                    v-else-if="
                      !notificationPreference.is_custom &&
                        !notificationPreference.ancestor_id
                    "
                  >
                    {{ $str('factory', 'totara_notification') }}
                  </template>
                  <template v-else>
                    {{ $str('inherited', 'totara_notification') }}
                  </template>
                </Cell>

                <Cell
                  align="start"
                  size="4"
                  :column-header="$str('recipient', 'totara_notification')"
                >
                  {{ notificationPreference.recipient.name }}
                </Cell>

                <Cell
                  align="start"
                  size="3"
                  :column-header="$str('schedule', 'totara_notification')"
                >
                  {{ notificationPreference.schedule_label }}
                </Cell>

                <Cell
                  align="start"
                  size="2"
                  :column-header="$str('status', 'core')"
                >
                  <template v-if="resolver.status.is_enabled">
                    <template v-if="notificationPreference.enabled">
                      {{ $str('enabled', 'totara_notification') }}
                    </template>
                    <template v-if="!notificationPreference.enabled">
                      {{ $str('disabled', 'totara_notification') }}
                    </template>
                  </template>
                  <template v-else>
                    <template v-if="notificationPreference.enabled">
                      <del>{{ $str('enabled', 'totara_notification') }}</del>
                    </template>
                    <template v-if="!notificationPreference.enabled">
                      <del>{{ $str('disabled', 'totara_notification') }}</del>
                    </template>
                  </template>
                </Cell>

                <Cell align="end" size="1">
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
                        resolver.recipients,
                        resolver.default_delivery_channels,
                        resolver.additional_criteria_component
                      )
                    "
                    @delete-notification="
                      $emit('delete-notification', notificationPreference)
                    "
                  />
                </Cell>
              </template>
            </Table>

            <template v-else>
              {{ $str('no_notifications', 'totara_notification') }}

              <Button
                :text="$str('create_notification', 'totara_notification')"
                :aria-label="
                  $str(
                    'create_notification_for_event',
                    'totara_notification',
                    resolver.name
                  )
                "
                @click="
                  $emit('create-custom-notification', {
                    resolverClassName: resolver.class_name,
                    scheduleTypes: resolver.valid_schedules,
                    recipients: resolver.recipients,
                    deliveryChannels: resolver.default_delivery_channels,
                    additionalCriteriaComponent:
                      resolver.additional_criteria_component,
                  })
                "
              />
            </template>
          </template>
        </Table>
      </Collapsible>
    </div>
  </div>
</template>

<script>
import Button from 'tui/components/buttons/Button';
import Collapsible from 'tui/components/collapsible/Collapsible';
import CollapsibleGroupToggle from 'tui/components/collapsible/CollapsibleGroupToggle';
import Cell from 'tui/components/datatable/Cell';
import ExpandCell from 'tui/components/datatable/ExpandCell';
import HeaderCell from 'tui/components/datatable/HeaderCell';
import Table from 'tui/components/datatable/Table';
import NotificationAction from 'totara_notification/components/action/NotificationAction';
import NotifiableEventAction from 'totara_notification/components/action/NotifiableEventAction';
import ToggleSwitch from 'tui/components/toggle/ToggleSwitch';
import InfoIconButton from 'tui/components/buttons/InfoIconButton';

export default {
  components: {
    Button,
    Collapsible,
    CollapsibleGroupToggle,
    Cell,
    ExpandCell,
    HeaderCell,
    Table,
    NotificationAction,
    NotifiableEventAction,
    ToggleSwitch,
    InfoIconButton,
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

  data() {
    const expanded = {};
    this.eventResolvers.forEach(
      eventResolver => (expanded[eventResolver.component] = false)
    );

    return { expanded };
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

    onStatusToggle(value, resolver) {
      const properties = {
        value: value,
        resolver: resolver,
      };
      this.$emit('status-toggle', properties);
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
    "custom",
    "delivery_channels",
    "disable",
    "disabled",
    "delivery_preferences_helptext_aria",
    "default_delivery_preferences_helptext",
    "enabled",
    "enabled_helptext",
    "enabled_helptext_aria",
    "enable_status",
    "factory",
    "inherited",
    "notifications",
    "create_notification",
    "create_notification_for_event",
    "no_notifications",
    "type"
  ],
  "core": [
    "actions",
    "status"
  ]
}
</lang-strings>

<style lang="scss">
.tui-notificationTable {
  margin-top: var(--gap-4);
  &__header {
    display: flex;
  }

  &__subTable {
    background-color: var(--color-text-disabled);
  }
}

.tui-collapsible {
  &__header {
    --collapsible-header-border-color: var(--color-neutral-1);
  }
}
</style>
