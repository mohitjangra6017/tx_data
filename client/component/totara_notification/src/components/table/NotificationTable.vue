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
    <template v-if="!$apollo.loading">
      <Table :data="configurations" :expandable-rows="true" :hover-off="true">
        <template v-slot:header-row>
          <ExpandCell :header="true" />
          <HeaderCell valign="center">
            {{ $str('events_and_notifications', 'totara_notification') }}
          </HeaderCell>
        </template>

        <template v-slot:row="{ row, expand, expandState }">
          <ExpandCell
            :aria-label="row.component"
            :expand-state="expandState"
            @click="expand()"
          />
          <Cell>
            {{ row.component }}
          </Cell>
        </template>

        <template v-slot:expand-content="{ row }">
          <Table
            :data="row.notifiable_event_configurations"
            :expandable-rows="true"
            :border-top-hidden="true"
            :border-bottom-hidden="true"
            :hover-off="true"
          >
            <template v-slot:row="{ row, expand, expandState }">
              <template v-if="row.notification_configurations.length">
                <ExpandCell
                  :aria-label="row.title"
                  :expand-state="expandState"
                  @click="expand()"
                />
                <Cell>
                  {{ row.title }}
                </Cell>
              </template>
              <template v-else>
                <Cell>
                  {{ row.title }}
                </Cell>
              </template>
            </template>

            <template v-slot:expand-content="{ row }">
              <Table
                :data="row.notification_configurations"
                :expandable-rows="false"
                :border-top-hidden="true"
                :border-bottom-hidden="true"
                :hover-off="true"
              >
                <template v-slot:row="{ row }">
                  <Cell>
                    {{ row.title }}
                  </Cell>
                </template>
              </Table>
            </template>
          </Table>
        </template>
      </Table>
    </template>
    <Loader :loading="$apollo.loading" />
  </div>
</template>

<script>
import Cell from 'tui/components/datatable/Cell';
import ExpandCell from 'tui/components/datatable/ExpandCell';
import HeaderCell from 'tui/components/datatable/HeaderCell';
import Table from 'tui/components/datatable/Table';
import Loader from 'tui/components/loading/Loader';

// GraphQL queries
import getComponentConfigurations from 'totara_notification/graphql/component_configurations';

export default {
  components: {
    HeaderCell,
    Cell,
    Table,
    ExpandCell,
    Loader,
  },

  apollo: {
    configurations: {
      query: getComponentConfigurations,
      fetchPolicy: 'network-only',
    },
  },
};
</script>
<lang-strings>
  {
    "totara_notification": [
      "events_and_notifications"
    ]
  }
</lang-strings>
