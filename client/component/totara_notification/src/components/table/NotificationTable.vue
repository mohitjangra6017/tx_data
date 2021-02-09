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
          <HeaderCell valign="center">
            {{ $str('recipient', 'totara_notification') }}
          </HeaderCell>
          <HeaderCell valign="center">
            {{ $str('schedule', 'totara_notification') }}
          </HeaderCell>
          <HeaderCell valign="center">
            {{ $str('delivery_channels', 'totara_notification') }}
          </HeaderCell>
          <HeaderCell valign="center">
            {{ $str('enabled', 'totara_notification') }}
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
                <Cell>
                  'replace me with recipient'
                </Cell>
                <Cell>
                  'replace me with schedule'
                </Cell>
                <Cell>
                  'replace me with delivery channels'
                </Cell>
                <Cell>
                  <div class="tui-notificationTable__action">
                    <ToggleSwitch
                      :toggle-first="true"
                      :aria-label="$str('disable', 'totara_notification')"
                    />
                    <Dropdown :actions="defaultActions" :title="row.title">
                      <template v-slot:icon>
                        <ButtonIcon
                          :aria-label="
                            $str('create_notification', 'totara_notification')
                          "
                          :styleclass="{
                            small: true,
                            transparentNoPadding: true,
                          }"
                          @click="openModal(row.title)"
                        >
                          <AddIcon size="300" />
                        </ButtonIcon>
                      </template>
                    </Dropdown>
                  </div>
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
    <ModalPresenter :open="modalOpen" @request-close="modalOpen = false">
      <NotificationModal :title="getModalTitle">
        <template v-slot:form>
          <CreateNotificationForm @cancel="modalOpen = $event" />
        </template>
      </NotificationModal>
    </ModalPresenter>
    <Loader :loading="$apollo.loading" />
  </div>
</template>

<script>
import Cell from 'tui/components/datatable/Cell';
import ExpandCell from 'tui/components/datatable/ExpandCell';
import HeaderCell from 'tui/components/datatable/HeaderCell';
import Table from 'tui/components/datatable/Table';
import Loader from 'tui/components/loading/Loader';
import ToggleSwitch from 'tui/components/toggle/ToggleSwitch';
import Dropdown from 'totara_notification/components/dropdown/NotificationDropdown';
import ButtonIcon from 'tui/components/buttons/ButtonIcon';
import AddIcon from 'tui/components/icons/Add';
import ModalPresenter from 'tui/components/modal/ModalPresenter';
import NotificationModal from 'totara_notification/components/modal/NotificationModal';
import CreateNotificationForm from 'totara_notification/components/form/NotificationCreationForm';

// GraphQL queries
import getComponentConfigurations from 'totara_notification/graphql/component_configurations';

export default {
  components: {
    HeaderCell,
    Cell,
    Table,
    ExpandCell,
    Loader,
    ToggleSwitch,
    Dropdown,
    ButtonIcon,
    AddIcon,
    ModalPresenter,
    NotificationModal,
    CreateNotificationForm,
  },

  apollo: {
    configurations: {
      query: getComponentConfigurations,
      fetchPolicy: 'network-only',
    },
  },

  data() {
    return {
      modalOpen: false,
      modalTitle: '',
    };
  },

  computed: {
    getModalTitle() {
      return this.$str(
        'create_notification_modal_title',
        'totara_notification',
        this.modalTitle
      );
    },
    defaultActions() {
      return [
        {
          label: this.$str('create_notification', 'totara_notification'),
          action: this.openModal,
        },
      ];
    },
  },
  methods: {
    openModal(title) {
      this.modalOpen = true;
      this.modalTitle = title;
    },
  },
};
</script>
<lang-strings>
  {
    "totara_notification": [
      "create_notification",
      "create_notification_modal_title",
      "events_and_notifications",
      "recipient",
      "schedule",
      "delivery_channels",
      "disable",
      "enabled"
    ]
  }
</lang-strings>
<style lang="scss">
.tui-notificationTable {
  &__action {
    display: flex;
    justify-content: space-between;
  }
}
</style>
