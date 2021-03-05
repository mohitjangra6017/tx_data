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
  <div class="tui-preferencesTable">
    <Table :data="notifiableEvents" :expandable-rows="true" :hover-off="true">
      <template v-slot:header-row>
        <HeaderCell>
          {{ $str('notifications', 'totara_notification') }}
        </HeaderCell>
        <HeaderCell>
          {{ $str('delivery_channels', 'totara_notification') }}
        </HeaderCell>
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
          <template v-slot:row="{ expand, expandState, row: notifiableEvent }">
            <Cell>
              {{ notifiableEvent.name }}
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

export default {
  components: {
    HeaderCell,
    Cell,
    Table,
    ExpandCell,
  },

  props: {
    contextId: {
      type: Number,
      required: true,
    },

    notifiableEvents: {
      type: Array,
      default: () => [],
      validator(prop) {
        return prop.every(preference => {
          return (
            'component' in preference &&
            'events' in preference &&
            'recipients' in preference &&
            'plugin_name' in preference
          );
        });
      },
    },
  },
};
</script>
<lang-strings>
{
  "totara_notification": [
    "delivery_channels",
    "notifications"
  ]
}
</lang-strings>
