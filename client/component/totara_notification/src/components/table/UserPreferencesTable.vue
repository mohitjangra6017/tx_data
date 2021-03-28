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

  @author Matthias Bonk <matthias.bonk@totaralearning.com>
  @module totara_notification
-->

<template>
  <div class="tui-userPreferencesTable">
    <Table :data="eventResolvers" :expandable-rows="true" :hover-off="true">
      <template v-slot:header-row>
        <HeaderCell>
          {{ $str('notifications', 'totara_notification') }}
        </HeaderCell>
        <HeaderCell>
          {{ $str('delivery_channels', 'totara_notification') }}
        </HeaderCell>
        <HeaderCell>
          {{ $str('enabled', 'totara_notification') }}
        </HeaderCell>
        <HeaderCell />
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
          :border-top-hidden="true"
          :border-bottom-hidden="true"
          :hover-off="true"
        >
          <template
            v-slot:row="{ expand, expandState, row: resolverPreference }"
          >
            <Cell>
              {{ resolverPreference.name }}
            </Cell>
            <Cell />
            <Cell>
              <ToggleSwitch
                :aria-label="
                  $str(
                    'enabled_status',
                    'totara_notification',
                    resolverPreference.name
                  )
                "
                :value="resolverPreference.enabled"
                :toggle-only="true"
                @input="toggleEnabled($event, resolverPreference)"
              />
            </Cell>
            <Cell>
              ...
            </Cell>
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
import ToggleSwitch from 'tui/components/toggle/ToggleSwitch';

export default {
  components: {
    Cell,
    ExpandCell,
    HeaderCell,
    Table,
    ToggleSwitch,
  },

  props: {
    eventResolvers: {
      type: Array,
      default: () => [],
      validator(prop) {
        return prop.every(preference => {
          return (
            'component' in preference &&
            'plugin_name' in preference &&
            'resolvers' in preference
          );
        });
      },
    },
  },

  methods: {
    /**
     * @param {Boolean} value
     * @param {Object} updatedResolver
     */
    toggleEnabled(value, updatedResolver) {
      this.$emit('toggle-enabled', {
        userId: updatedResolver.user_id,
        resolverClassname: updatedResolver.resolver_class_name,
        isEnabled: value,
        userPreferenceId: updatedResolver.user_preference_id,
      });
    },
  },
};
</script>
<lang-strings>
{
  "totara_notification": [
    "delivery_channels",
    "enabled",
    "enabled_status",
    "notifications"
  ]
}
</lang-strings>
