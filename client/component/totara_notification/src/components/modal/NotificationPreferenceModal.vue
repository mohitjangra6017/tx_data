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
  <Modal
    size="large"
    :aria-labelledby="$id('title')"
    class="tui-notificaitonCreationModal"
    :dismissable="{
      esc: true,
      backdropClick: false,
      overlayClose: false,
    }"
  >
    <ModalContent
      class="tui-notificaitonCreationModal_content"
      :title="title"
      :title-id="$id('title')"
      :close-button="showCloseButton"
    >
      <NotificationPreferenceForm
        :context-id="contextId"
        :preference="preference"
        :parent-preference="parentPreference"
        @submit="$emit('submit', $event)"
        @cancel="$emit('request-close')"
      />
    </ModalContent>
  </Modal>
</template>

<script>
import Modal from 'tui/components/modal/Modal';
import ModalContent from 'tui/components/modal/ModalContent';
import NotificationPreferenceForm from 'totara_notification/components/form/NotificationPreferenceForm';
import {
  getDefaultNotificationPreference,
  validatePreferenceProp,
} from '../../internal/notification_preference';

export default {
  components: {
    Modal,
    ModalContent,
    NotificationPreferenceForm,
  },

  props: {
    title: {
      type: String,
      required: true,
    },

    contextId: {
      type: Number,
      required: true,
    },

    showCloseButton: {
      type: Boolean,
      default: true,
    },

    preference: {
      type: Object,
      validator: validatePreferenceProp(),
      default: getDefaultNotificationPreference(),
    },

    parentPreference: {
      type: Object,
      validator: validatePreferenceProp(),
      default() {
        return null;
      },
    },
  },
};
</script>
