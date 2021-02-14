<!--
  This file is part of Totara Enterprise Extensions.

  Copyright (C) 2021 onwards Totara Learning Solutions LTD

  Totara Enterprise Extensions is provided only to Totara
  Learning Solutions LTD’s customers and partners, pursuant to
  the terms and conditions of a separate agreement with Totara
  Learning Solutions LTD or its affiliate.

  If you do not have an agreement with Totara Learning Solutions
  LTD, you may not access, use, modify, or distribute this software.
  Please contact [licensing@totaralearning.com] for more information.

  @author Kian Nguyen <kian.nguyen@totaralearning.com>
  @module totara_notification
-->
<template>
  <div class="tui-notifiableEventAction">
    <ModalPresenter :open="modal.create" @request-close="modal.create = false">
      <NotificationPreferenceModal
        :title="
          $str(
            'create_custom_notification_title',
            'totara_notification',
            eventName
          )
        "
        :context-id="contextId"
        @submit="createCustomNotification"
      />
    </ModalPresenter>

    <div class="tui-notifiableEventAction__actionButtons">
      <!-- Quick add button icon -->
      <ButtonIcon
        :styleclass="{ small: true, transparentNoPadding: true }"
        :aria-label="$str('create_notification', 'totara_notification')"
        @click="modal.create = true"
      >
        <AddIcon :size="300" />
      </ButtonIcon>

      <!-- Drop down button -->
      <Dropdown>
        <template v-slot:trigger="{ toggle, isOpen }">
          <ButtonIcon
            :styleclass="{ small: true, transparentNoPadding: true }"
            :aria-expanded="isOpen ? 'true' : 'false'"
            :aria-label="$str('more', 'core')"
            @click="toggle"
          >
            <MoreIcon :size="300" />
          </ButtonIcon>
        </template>

        <DropdownItem @click="modal.create = true">
          {{ $str('create_notification', 'totara_notification') }}
        </DropdownItem>
      </Dropdown>
    </div>
  </div>
</template>

<script>
import Dropdown from 'tui/components/dropdown/Dropdown';
import DropdownItem from 'tui/components/dropdown/DropdownItem';
import ButtonIcon from 'tui/components/buttons/ButtonIcon';
import MoreIcon from 'tui/components/buttons/MoreIcon';
import ModalPresenter from 'tui/components/modal/ModalPresenter';
import NotificationPreferenceModal from 'totara_notification/components/modal/NotificationPreferenceModal';
import AddIcon from 'tui/components/icons/Add';
import { notify } from 'tui/notifications';

// Graphql Mutations
import createCustomNotification from 'totara_notification/graphql/create_custom_notification_preference';

export default {
  components: {
    DropdownItem,
    Dropdown,
    ButtonIcon,
    MoreIcon,
    AddIcon,
    ModalPresenter,
    NotificationPreferenceModal,
  },

  props: {
    contextId: {
      type: Number,
      required: true,
    },

    eventName: {
      type: String,
      required: true,
    },

    eventClassName: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      modal: {
        create: false,
      },
    };
  },

  methods: {
    /**
     *
     * @param {String} subject
     * @param {String} title
     * @param {String} body
     * @param {Number} body_format
     */
    async createCustomNotification({ subject, title, body, body_format }) {
      try {
        const {
          data: { notification_preference },
        } = await this.$apollo.mutate({
          mutation: createCustomNotification,
          variables: {
            context_id: this.contextId,
            event_class_name: this.eventClassName,
            subject,
            title,
            body,
            body_format,
          },
        });

        this.$emit('created-custom-notification', notification_preference);
        this.modal.create = false;
      } catch (e) {
        await notify({
          type: 'error',
          message: this.$str(
            'error_cannot_create_custom_notification',
            'totara_notification'
          ),
        });
      }
    },
  },
};
</script>

<lang-strings>
  {
    "core": [
      "more"
    ],
    "totara_notification": [
      "create_custom_notification_title",
      "create_notification",
      "error_cannot_create_custom_notification"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-notifiableEventAction {
  &__actionButtons {
    display: flex;
  }
}
</style>
