<!--
  This file is part of Totara Enterprise Extensions.

  Copyright (C) 2020 onwards Totara Learning Solutions LTD

  Totara Enterprise Extensions is provided only to Totara
  Learning Solutions LTD's customers and partners, pursuant to
  the terms and conditions of a separate agreement with Totara
  Learning Solutions LTD or its affiliate.

  If you do not have an agreement with Totara Learning Solutions
  LTD, you may not access, use, modify, or distribute this software.
  Please contact [licensing@totaralearning.com] for more information.

  @author Kian Nguyen <kian.nguyen@totaralearning.com>
  @module totara_engage
-->

<template>
  <div class="tui-accessSetting">
    <ModalPresenter
      :open="showAccessModal"
      @request-close="showAccessModal = false"
    >
      <AccessModal
        :item-id="itemId"
        :component="component"
        :selected-access="accessValue"
        :selected-options="selectedOptions"
        :restricted-disabled="restrictedDisabled"
        :private-disabled="privateDisabled"
        :selected-time-view="selectedTimeView"
        :enable-time-view="enableTimeView"
        :submitting="submitting"
        :has-non-public-resources="hasNonPublicResources"
        :is-private="isPrivate"
        :is-restricted="isRestricted"
        @done="submit"
        @warning-privatetorestrictedorpublic="warnPrivateToRestrictedOrPublic"
        @warning-restrictedtopublic="warnRestrictedToPublic"
      />
    </ModalPresenter>

    <ModalPresenter
      :open="showWarningModal"
      @request-close="showWarningModal = false"
    >
      <EngagePrivacyWarningModal
        :title="$str('privacywarningtitle', 'totara_playlist')"
        :message-content="privacyWarningMessage"
        :privacy-warning="privacyWarningEvent"
        @cancel="cancelPrivacyChange"
        @confirm="submit(privacyWarningEvent)"
      />
    </ModalPresenter>

    <AccessDisplay
      slot="content"
      :access-value="accessValue"
      :topics="topics"
      :time-view="selectedTimeView"
      @request-open="showAccessModal = true"
    />
  </div>
</template>

<script>
import AccessModal from 'totara_engage/components/modal/AccessModal';
import EngagePrivacyWarningModal from 'totara_engage/components/modal/EngagePrivacyWarningModal';
import ModalPresenter from 'tui/components/modal/ModalPresenter';
import { AccessManager } from 'totara_engage/index';
import AccessDisplay from 'totara_engage/components/sidepanel/access/AccessDisplay';

export default {
  components: {
    AccessModal,
    EngagePrivacyWarningModal,
    ModalPresenter,
    AccessDisplay,
  },

  props: {
    submitting: {
      type: Boolean,
      default: false,
    },

    itemId: {
      type: [Number, String],
      required: true,
    },

    component: {
      type: String,
      required: true,
    },

    accessValue: {
      type: String,
      required: true,
      validator(prop) {
        return AccessManager.isValid(prop);
      },
    },

    topics: {
      type: Array,
      default() {
        return [];
      },
    },

    shares: {
      type: Array,
      default() {
        return [];
      },
    },

    openAccessModal: Boolean,

    openWarningModal: Boolean,

    selectedTimeView: {
      type: String,
      default: null,
    },

    enableTimeView: {
      type: Boolean,
      default: false,
    },

    hasNonPublicResources: Boolean,
  },

  data() {
    return {
      showAccessModal: this.openAccessModal,
      showWarningModal: this.openWarningModal,
      privacyWarningEvent: null,
      privacyWarningMessage: null,
    };
  },

  computed: {
    /**
     * @return {Object}
     */
    selectedOptions() {
      return {
        shares: this.shares,
        topics: this.topics,
      };
    },

    /**
     *
     * @returns {boolean}
     */
    restrictedDisabled() {
      return AccessManager.isPublic(this.accessValue);
    },

    privateDisabled() {
      return (
        AccessManager.isPublic(this.accessValue) ||
        AccessManager.isRestricted(this.accessValue)
      );
    },

    isPrivate() {
      return AccessManager.isPrivate(this.accessValue);
    },

    isRestricted() {
      return AccessManager.isRestricted(this.accessValue);
    },
  },

  watch: {
    showAccessModal(value) {
      if (!value) {
        this.$emit('close-modal');
      }
    },

    openAccessModal(value) {
      this.showAccessModal = value;
    },

    showWarningModal(value) {
      if (!value) {
        this.$emit('close-modal');
      }
    },

    openWarningModal(value) {
      this.showWarningModal = value;
    },
  },

  methods: {
    /**
     *
     * @param {String} access
     * @param {Array} topics
     * @param {Array} shares
     * @param {String} timeView
     */
    submit({ access, topics, shares, timeView }) {
      this.showAccessModal = false;
      this.showWarningModal = false;
      this.$emit('access-update', { access, topics, shares, timeView });
    },

    warnPrivateToRestrictedOrPublic(event) {
      this.showAccessModal = false;

      // open warningmodal with the event data
      this.privacyWarningMessage = this.$str(
        'privacychangeprivatetorestrictedorpublic',
        'totara_playlist'
      );
      this.privacyWarningEvent = event;
      this.showWarningModal = true;
    },

    warnRestrictedToPublic(event) {
      this.showAccessModal = false;

      // open warningmodal with the event data
      this.privacyWarningMessage = this.$str(
        'privacychangerestrictedtopublic',
        'totara_playlist'
      );
      this.privacyWarningEvent = event;
      this.showWarningModal = true;
    },

    cancelPrivacyChange() {
      this.showWarningModal = false;
      this.showAccessModal = true;
    },
  },
};
</script>

<lang-strings>
  {
    "totara_playlist": [
      "privacywarningtitle",
      "privacychangeprivatetorestrictedorpublic",
      "privacychangerestrictedtopublic"
    ]
  }
</lang-strings>
