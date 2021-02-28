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

  @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
  @author Marco Song <marco.song@totaralearning.com>
  @module performelement_linked_review
-->

<template>
  <div class="tui-linkedReviewSelectedContent">
    <!-- Preview of selected content -->
    <div class="tui-linkedReviewSelectedContent__items">
      <div
        v-for="content in selectedContent"
        :key="content.id"
        class="tui-linkedReviewSelectedContent__item"
      >
        <Card
          class="tui-linkedReviewSelectedContent__item-card"
          :no-border="true"
        >
          <div class="tui-linkedReviewSelectedContent__item-cardContent">
            <slot name="content-preview" :content="content" />
          </div>

          <div class="tui-linkedReviewSelectedContent__item-cardActions">
            <CloseButton
              :aria-label="$str('remove', 'core')"
              :size="300"
              @click="deleteContent(content.id)"
            />
          </div>
        </Card>
      </div>
    </div>

    <!-- Content adder  -->
    <div v-if="canShowAdder">
      <ButtonIcon
        :aria-label="addBtnText"
        :text="addBtnText"
        @click="adderOpen"
      >
        <AddIcon />
      </ButtonIcon>

      <component
        :is="adder"
        :open="showAdder"
        :existing-items="selectedIds"
        :user-id="userId"
        @added="adderUpdate"
        @cancel="adderClose"
      />
    </div>
    <div v-else>
      {{ cantAddText }}
    </div>

    <!-- Confirm selection button -->
    <div
      v-if="selectedContent.length > 0"
      class="tui-linkedReviewSelectedContent__confirm"
    >
      <Button
        :text="$str('confirm_selection', 'mod_perform')"
        :styleclass="{ primary: true }"
        @click="confirmSelectedIds"
      />
    </div>

    <!-- Display validation error when field is required and no selection made -->
    <FormField :name="$id('contentAdder')" :validations="validations" />
  </div>
</template>

<script>
// tui
import AddIcon from 'tui/components/icons/Add';
import Button from 'tui/components/buttons/Button';
import ButtonIcon from 'tui/components/buttons/ButtonIcon';
import Card from 'tui/components/card/Card';
import CloseButton from 'tui/components/buttons/CloseIcon';
import { FormField } from 'tui/components/uniform';
import { notify } from 'tui/notifications';
import { v as validation } from 'tui/validation';

// GraphQL
import updateReviewContentMutation from 'performelement_linked_review/graphql/update_linked_review_content';

export default {
  components: {
    AddIcon,
    Button,
    ButtonIcon,
    Card,
    CloseButton,
    FormField,
  },

  props: {
    addBtnText: {
      type: String,
      required: true,
    },
    adder: Object,
    canShowAdder: {
      type: Boolean,
      required: true,
    },
    cantAddText: {
      type: String,
      required: true,
    },
    isDraft: Boolean,
    participantInstanceId: {
      type: [String, Number],
      required: true,
    },
    required: Boolean,
    sectionElementId: String,
    userId: Number,
  },

  data() {
    return {
      selectedContent: [],
      selectedIds: [],
      showAdder: false,
    };
  },

  computed: {
    /**
     * An array of validation rules for the element.
     * The rules returned depend on if we are saving as draft or if a response is required or not.
     *
     * @return {(function|object)[]}
     */
    validations() {
      if (!this.required || this.isDraft) {
        return [];
      }
      return [validation.required()];
    },
  },

  mounted() {
    // Confirm navigation away if user is currently editing.
    window.addEventListener('beforeunload', this.unloadHandler);
  },

  methods: {
    /**
     * Close the adder
     */
    adderClose() {
      this.showAdder = false;
    },

    /**
     * Open the adder
     */
    adderOpen() {
      this.showAdder = true;
    },

    /**
     * Update selected content items
     *
     * @param {Object} selection selected data returned from adder
     */
    adderUpdate(selection) {
      this.selectedContent = selection.data;
      this.selectedIds = selection.ids;
      this.adderClose();
    },

    /**
     * Save selected content
     */
    async confirmSelectedIds() {
      try {
        await this.saveContent();
        this.selectedIds = [];
      } catch (e) {
        this.showMutationErrorNotification();
      } finally {
        this.$emit('update');
      }
    },

    /**
     * Remove item from selected content
     *
     * @param {Number} contentId ID of item to be removed
     */
    deleteContent(contentId) {
      this.selectedContent = this.selectedContent.filter(
        item => item.id !== contentId
      );
      this.selectedIds = this.selectedIds.filter(e => e !== contentId);
    },

    /**
     * Save selected content in the repository
     */
    async saveContent() {
      await this.$apollo.mutate({
        mutation: updateReviewContentMutation,
        variables: {
          input: {
            content_ids: this.selectedContent.map(content => content.id),
            participant_instance_id: this.participantInstanceId,
            section_element_id: this.sectionElementId,
          },
        },
        refetchAll: false, // Don't refetch all the data again
      });
    },

    /**
     * Show a generic saving error toast.
     */
    showMutationErrorNotification() {
      notify({
        message: this.$str('error', 'core'),
        type: 'error',
      });
    },

    /**
     * Displays a warning message if the user tries to navigate away without saving.
     * @param {Event} e
     * @returns {String|void}
     */
    unloadHandler(e) {
      if (!this.selectedIds.length) {
        return;
      }
      // For older browsers that still show custom message.
      const discardUnsavedChanges = this.$str(
        'unsaved_changes_warning',
        'mod_perform'
      );
      e.preventDefault();
      e.returnValue = discardUnsavedChanges;
      return discardUnsavedChanges;
    },
  },
};
</script>

<style lang="scss">
.tui-linkedReviewSelectedContent {
  & > * + * {
    margin-top: var(--gap-4);
  }

  &__items {
    & > * + * {
      margin-top: var(--gap-4);
    }
  }

  &__item {
    & > * + * {
      margin-top: var(--gap-4);
    }

    &-card {
      max-width: 1200px;
      background: var(--color-neutral-3);
    }

    &-cardContent {
      width: 100%;
      padding: var(--gap-4);
    }

    &-cardActions {
      display: flex;
      align-items: flex-start;
      width: var(--gap-9);
      margin-top: var(--gap-2);
    }
  }
}
</style>

<lang-strings>
{
  "core": [
    "error",
    "remove"
  ],
  "mod_perform": [
    "confirm_selection",
    "unsaved_changes_warning"
  ]
}
</lang-strings>
