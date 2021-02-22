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
  <div>
    <Loader :loading="loading" />

    <div v-if="!loading">
      <!-- User selects what content they want to review -->
      <component
        :is="component(element.data.components.participant_picker)"
        v-if="selectedContent.length === 0"
        :settings="contentSettings"
        @update="refetch"
      />

      <!-- Actual way of responding to content -->
      <div
        v-for="(item, index) in selectedContent"
        v-else
        :key="item.id"
        class="tui-performLinkedReview__questionsGroup"
      >
        <Separator v-if="index > 0" />

        <div class="tui-performLinkedReview__questionsGroup-content">
          <component
            :is="component(element.data.components.participant_content)"
            :content="item"
            :settings="contentSettings"
          />
        </div>

        <!-- Display response elements -->
        <div
          v-for="childElement in element.children"
          :key="item.id + '-' + childElement.id"
          class="tui-performLinkedReview__questionsGroup-response"
        >
          <h3
            v-if="childElement.title"
            :id="$id('title')"
            class="tui-participantContent__sectionItem-contentHeader"
          >
            {{ childElement.title }}
            <RequiredOptionalIndicator
              v-if="childElement.is_respondable"
              :is-required="childElement.is_required"
            />
          </h3>
          <component
            :is="
              component(childElement.element_plugin.participant_form_component)
            "
            :path="path.concat([item.id + '-' + childElement.id])"
            :element="childElement"
            :section-element-id="sectionElementId"
            :participant-instance-id="participantInstanceId"
            :is-external-participant="isExternalParticipant"
            class="tui-performLinkedReview__questionsGroup-response-element"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { FormField, FormScope } from 'tui/components/uniform';
import { v as validation } from 'tui/validation';
import Loader from 'tui/components/loading/Loader';
import RequiredOptionalIndicator from 'mod_perform/components/user_activities/RequiredOptionalIndicator';
import Separator from 'tui/components/decor/Separator';

export default {
  components: {
    FormField,
    FormScope,
    Loader,
    RequiredOptionalIndicator,
    Separator,
  },

  props: {
    disabled: Boolean,
    path: {
      type: [String, Array],
      default: '',
    },
    error: String,
    isDraft: Boolean,
    element: Object,
    isExternalParticipant: Boolean,
    sectionElementId: {
      type: [String, Number],
      required: true,
    },
    participantInstanceId: {
      type: [String, Number],
      required: true,
    },
  },

  data() {
    return {
      selectedContent: [],
      contentSettings: this.element.data.content_type_settings,
      loading: true,
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
      const rules = [validation.maxLength(1024)];

      if (this.isDraft) {
        return rules;
      }

      if (this.element && this.element.is_required) {
        return [validation.required(), ...rules];
      }

      return rules;
    },
  },

  /**
   * Dynamically load the query defined by the content type's participant content display component.
   */
  created() {
    tui
      .import(this.element.data.components.participant_content)
      .then(component => {
        this.$apollo.addSmartQuery('selectedContent', {
          query: component.query,
          variables() {
            return {
              participant_instance_id: this.participantInstanceId,
              section_element_id: this.sectionElementId,
            };
          },
          update(data) {
            this.loading = false;
            return data.items;
          },
        });
      });
  },

  methods: {
    /**
     * @param {String} componentPath Vue component path
     * @return {Function}
     */
    component(componentPath) {
      return tui.asyncComponent(componentPath);
    },

    /**
     * Process the form values.
     *
     * @param {Object} value
     * @return {Object|null}
     */
    process(value) {
      // TODO: Do something a bit smarter here.
      return value;
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
.tui-performLinkedReview {
  &__questionsGroup {
    &-content {
      padding: var(--gap-4);
      border: var(--border-width-thin) solid var(--card-border-color);
      border-radius: var(--card-border-radius);
    }

    &-response {
      margin-top: var(--gap-4);

      &-element {
        margin-top: var(--gap-2);
      }
    }
  }
}
</style>
