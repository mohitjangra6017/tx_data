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

  @author Kevin Hottinger <kevin.hottinger@totaralearning.com>
  @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
  @module performelement_linked_review
-->

<template>
  <Loader v-if="$apollo.loading" :loading="$apollo.loading" />
  <div v-else class="tui-linkedReviewParticipantForm">
    <!-- User selects what content they want to review -->
    <component
      :is="getComponent(element.data.components.content_picker)"
      v-if="selectedContent.length === 0 && participantInstanceId"
      :participant-instance-id="participantInstanceId"
      :section-element-id="sectionElement.id"
      :settings="contentSettings"
      :user-id="userId"
      @update="refetch"
    />

    <!-- Respondable card for each group of questions -->
    <Card v-for="(item, index) in selectedContent" v-else :key="item.id">
      <div class="tui-linkedReviewParticipantForm__group">
        <!-- Display of review content -->
        <component
          :is="getComponent(element.data.components.participant_content)"
          :content="item"
          :settings="contentSettings"
        />

        <!-- Display for each respondable question within the group -->
        <div
          v-for="(childElement, elementIndex) in element.children"
          :key="item.id + '-' + childElement.id"
          class="tui-linkedReviewParticipantForm__groupQuestion"
        >
          <ResponseHeader
            v-if="childElement.title"
            :id="$id('title')"
            :has-printed-to-do-icon="
              hasPrintedToDoIcon && childElement.is_respondable
            "
            :is-respondable="childElement.is_respondable"
            :required="childElement.is_required"
            :title="childElement.title"
          />

          <div class="tui-linkedReviewParticipantForm__groupQuestion-content">
            <!-- Load child component here -->
            <component
              :is="
                getComponent(
                  childElement.element_plugin.participant_form_component
                )
              "
              v-bind="$attrs"
              :element="childElement"
              :element-components="childElement.element_plugin"
              :participant-instance-id="participantInstanceId"
              :path="[
                'sectionElements',
                sectionElement.id + '-' + index + '-' + elementIndex,
              ]"
              :section-element="sectionElement"
              :active-section-is-closed="activeSectionIsClosed"
              :anonymous-responses="anonymousResponses"
              :error="error"
              :group-id="checkboxGroupId"
              :is-draft="isDraft"
              :is-external-participant="isExternalParticipant"
              :participant-can-answer="participantCanAnswer"
              :subject-instance-id="subjectInstanceId"
              :show-other-response="showOtherResponse"
              :view-only="viewOnlyReportMode"
              :token="token"
            />
          </div>
        </div>
      </div>
    </Card>
  </div>
</template>

<script>
import Card from 'tui/components/card/Card';
import Loader from 'tui/components/loading/Loader';
import ResponseHeader from 'mod_perform/components/element/ElementParticipantResponseHeader';

export default {
  components: {
    Card,
    Loader,
    ResponseHeader,
  },

  props: {
    element: Object,
    hasPrintedToDoIcon: Boolean,
    isExternalParticipant: Boolean,
    participantInstanceId: {
      type: [String, Number],
      required: false,
    },
    path: {
      type: [String, Array],
      default: '',
    },
    sectionElement: Object,
    subjectUser: {
      required: true,
      type: Object,
    },
    subjectInstanceId: {
      type: Number,
      required: true,
    },
    error: String,
    token: String,
    isDraft: Boolean,
    viewOnlyReportMode: Boolean,
    showOtherResponse: Boolean,
    participantCanAnswer: Boolean,
    checkboxGroupId: String,
    anonymousResponses: Boolean,
    activeSectionIsClosed: Boolean,
  },

  data() {
    return {
      contentSettings: this.element.data.content_type_settings,
      groupId: this.$id('label'),
      selectedContent: [],
    };
  },

  computed: {
    userId() {
      return parseInt(this.subjectUser.id);
    },
  },

  /**
   * Dynamically load the query defined by the content type's participant content display component.
   */
  created() {
    tui
      .import(this.element.data.components.participant_content)
      .then(component => {
        if (!component.query || !component.query_external) {
          throw new Error(
            'The component for the selected review type does not provide the necessary queries.'
          );
        }
        let query = this.isExternalParticipant
          ? component.query_external
          : component.query;

        this.$apollo.addSmartQuery('selectedContent', {
          query: query,
          variables() {
            return {
              subject_instance_id: this.subjectInstanceId,
              section_element_id: this.sectionElement.id,
              token: this.token ? this.token : null,
            };
          },
          update(data) {
            return data.items;
          },
        });
      });
  },

  methods: {
    /**
     * Get dynamic component
     *
     * @param {String} componentPath Vue component path
     * @return {function}
     */
    getComponent(componentPath) {
      return tui.asyncComponent(componentPath);
    },

    /**
     * Fetch the content display data after confirming the selection.
     */
    refetch() {
      this.loading = true;
      this.$apollo.queries.selectedContent.refetch();
    },
  },
};
</script>

<style lang="scss">
.tui-linkedReviewParticipantForm {
  & > * + * {
    margin-top: var(--gap-4);
  }

  &__group {
    width: 100%;
    padding: var(--gap-4);
  }

  &__groupQuestion {
    margin-top: var(--gap-12);

    &-content {
      margin-top: var(--gap-8);

      & > * + * {
        margin-top: var(--gap-8);
      }
    }
  }
}
</style>
