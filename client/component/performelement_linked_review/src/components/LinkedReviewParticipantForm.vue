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
      v-if="
        selectedContent.items &&
          selectedContent.items.length === 0 &&
          participantInstanceId
      "
      :is-draft="isDraft"
      :is-external-participant="isExternalParticipant"
      :can-show-adder="canSelectContent"
      :core-relationship="element.data.selection_relationships_display"
      :participant-instance-id="participantInstanceId"
      :preview-component="
        getComponent(element.data.components.participant_content)
      "
      :required="element.is_required"
      :section-element-id="sectionElement.id"
      :settings="contentSettings"
      :user-id="userId"
      @update="refetch"
    />

    <template v-else>
      <!-- Overview of who selected the content -->
      <div class="tui-linkedReviewParticipantForm__selectedBy">
        <template v-if="selectedContent.items[0]">
          {{
            $str('items_selected_by', 'mod_perform', {
              date: selectedContent.items[0].created_at_date,
              user: selectedContent.items[0].selector.fullname,
            })
          }}
        </template>
        <template v-else>
          {{ $str('no_items_selected', 'mod_perform') }}
        </template>
      </div>

      <div class="tui-linkedReviewParticipantForm__items">
        <!-- Iterate thought selected content -->
        <div
          v-for="(item, itemIndex) in selectedContent.items"
          :key="item.id"
          class="tui-linkedReviewParticipantForm__item"
        >
          <!-- Card summary of selected content item-->
          <Card
            class="tui-linkedReviewParticipantForm__item-card"
            :no-border="true"
          >
            <div class="tui-linkedReviewParticipantForm__item-cardContent">
              <component
                :is="getComponent(element.data.components.participant_content)"
                :content="getContent(item.content)"
                :created-at="item.created_at_date"
                :from-print="fromPrint"
                :settings="contentSettings"
              />
            </div>
            <div
              v-if="!fromPrint"
              class="tui-linkedReviewParticipantForm__item-cardActions"
            />
          </Card>

          <!-- Display for each respondable question within the group -->
          <div class="tui-linkedReviewParticipantForm__questions">
            <div
              v-for="(childElement, childElementIndex) in element.children"
              :key="item.id + '-' + childElement.id"
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

              <div class="tui-linkedReviewParticipantForm__questions-content">
                <FormScope
                  :path="contentPath(itemIndex)"
                  :process="contentResponsesProcessor(item)"
                >
                  <ChildElementFormScope
                    :key="childElementIndex"
                    :element="childElement"
                    :child-element-index="childElementIndex"
                  >
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
                      :from-print="fromPrint"
                      :participant-instance-id="participantInstanceId"
                      :path="'response_data'"
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
                  </ChildElementFormScope>
                </FormScope>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import Card from 'tui/components/card/Card';
import ChildElementFormScope from 'mod_perform/components/element/ChildElementFormScope';
import FormScope from 'tui/components/reform/FormScope';
import Loader from 'tui/components/loading/Loader';
import ResponseHeader from 'mod_perform/components/element/ElementParticipantResponseHeader';
import selectedContentItemsQuery from 'performelement_linked_review/graphql/content_items';
import selectedContentItemsQueryExternal from 'performelement_linked_review/graphql/content_items_nosession';

export default {
  components: {
    Card,
    ChildElementFormScope,
    FormScope,
    Loader,
    ResponseHeader,
  },

  props: {
    activeSectionIsClosed: Boolean,
    anonymousResponses: Boolean,
    checkboxGroupId: String,
    coreRelationshipId: [String, Number],
    element: Object,
    error: String,
    fromPrint: Boolean,
    hasPrintedToDoIcon: Boolean,
    isDraft: Boolean,
    isExternalParticipant: Boolean,
    participantCanAnswer: Boolean,
    participantInstanceId: {
      type: [String, Number],
      required: false,
    },
    path: {
      type: [String, Array],
      default: '',
    },
    sectionElement: Object,
    showOtherResponse: Boolean,
    subjectUser: {
      required: true,
      type: Object,
    },
    subjectInstanceId: {
      type: Number,
      required: true,
    },
    token: String,
    viewOnlyReportMode: Boolean,
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

    canSelectContent() {
      return (
        this.coreRelationshipId == this.element.data.selection_relationships &&
        !this.fromPrint &&
        !this.isExternalParticipant
      );
    },
  },

  methods: {
    /**
     * Generates content path.
     *
     * @param itemIndex index of item.
     */
    contentPath(itemIndex) {
      let contentPath = [];

      if (this.path instanceof String) {
        contentPath.push(this.path);
      }

      if (this.path instanceof Array) {
        this.path.forEach(pathItem => contentPath.push(pathItem));
      }
      contentPath.push('response', itemIndex);

      return contentPath;
    },

    /**
     * Parses the content responses.
     */
    contentResponsesProcessor(item) {
      const contentItem = item;

      return value => {
        // remove later.
        if (!contentItem) {
          return value;
        }
        value.content_id = 'content id at index';

        return value;
      };
    },

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

    /**
     * @param {string} content
     */
    getContent(content) {
      return content ? JSON.parse(content) : {};
    },
  },

  apollo: {
    selectedContent: {
      query() {
        return this.isExternalParticipant
          ? selectedContentItemsQueryExternal
          : selectedContentItemsQuery;
      },
      variables() {
        return {
          input: {
            subject_instance_id: this.subjectInstanceId,
            section_element_id: this.sectionElement.id,
            token: this.token ? this.token : null,
          },
        };
      },
      update({ performelement_linked_review_content_items }) {
        return performelement_linked_review_content_items;
      },
    },
  },
};
</script>

<lang-strings>
  {
    "mod_perform": [
      "items_selected_by",
      "no_items_selected"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-linkedReviewParticipantForm {
  & > * + * {
    margin-top: var(--gap-4);
  }

  &__items {
    & > * + * {
      margin-top: var(--gap-8);
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

  &__questions {
    & > * + * {
      margin-top: var(--gap-8);
    }

    &-content {
      margin-top: var(--gap-8);

      & > * + * {
        margin-top: var(--gap-8);
      }
    }
  }
}
</style>
