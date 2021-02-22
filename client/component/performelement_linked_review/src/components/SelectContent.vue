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
    <slot name="content-picker" />

    <div class="tui-linkedReviewSelectedContent__itemGroup">
      <div
        v-for="content in selectedContent"
        :key="content.id"
        class="tui-linkedReviewSelectedContent__itemGroup-item"
      >
        <Collapsible label="">
          <template v-slot:label-extra>
            <slot name="content-title" :content="content" />
          </template>

          <template v-slot:collapsible-side-content>
            <ButtonIcon
              :aria-label="$str('delete', 'core')"
              :styleclass="{
                small: true,
                transparent: true,
              }"
              @click="deleteContent(content.id)"
            >
              <DeleteIcon />
            </ButtonIcon>
          </template>

          <template v-slot:expanded>
            <slot name="content-detail" :content="content" />
          </template>
        </Collapsible>
      </div>
    </div>

    <div
      v-if="selectedContent.length > 0"
      class="tui-linkedReviewSelectedContent__confirm"
    >
      <slot name="confirm" :confirm="confirmSelectedIds" />
    </div>
  </div>
</template>

<script>
// tui
import ButtonIcon from 'tui/components/buttons/ButtonIcon';
import Collapsible from 'tui/components/collapsible/Collapsible';
import DeleteIcon from 'tui/components/icons/Delete';
import { notify } from 'tui/notifications';

// GraphQL
import updateReviewContentMutation from 'performelement_linked_review/graphql/update_linked_review_content';

export default {
  components: {
    ButtonIcon,
    Collapsible,
    DeleteIcon,
  },

  props: {
    selectedContent: {
      type: Array,
      required: false,
    },
  },

  methods: {
    /**
     * Save selected content
     */
    async confirmSelectedIds() {
      try {
        await this.saveContent();
        this.showMutationSuccessNotification();
      } catch (e) {
        this.showMutationErrorNotification();
      } finally {
        this.$emit('update');
      }
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
            section_element_id: this.$parent.$parent.sectionElementId,
            participant_instance_id: this.$parent.$parent.participantInstanceId,
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
        message: this.$str('error'),
        type: 'error',
      });
    },

    /**
     * Show a generic success toast.
     */
    showMutationSuccessNotification() {
      notify({
        message: this.$str('success'),
        type: 'success',
      });
    },

    /**
     * Delete a content from list
     */
    deleteContent(event) {
      this.$emit('delete-content', event);
    },
  },
};
</script>

<style lang="scss">
.tui-linkedReviewSelectedContent {
  &__itemGroup {
    margin-top: var(--gap-4);

    &-item {
      margin-top: var(--gap-2);
    }
  }

  &__confirm {
    margin-top: var(--gap-4);
  }
}
</style>

<lang-strings>
{
  "core": [
    "delete",
    "success"
  ]
}
</lang-strings>
