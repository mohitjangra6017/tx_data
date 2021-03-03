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
  @module performelement_linked_review
-->

<template>
  <div class="tui-linkedReviewAdminEdit">
    <PerformAdminCustomElementEdit
      ref="form"
      :initial-values="initialValues"
      :settings="settings"
      @cancel="$emit('display')"
      @change="updateForm"
      @update="handleSubmit"
    >
      <FormRow
        :label="$str('content_type', 'performelement_linked_review')"
        :required="true"
      >
        <FormSelect
          :id="$id('select-content-type')"
          char-length="20"
          name="content_type"
          :options="contentTypes"
          :validations="v => [v.required()]"
        />
      </FormRow>

      <FormRow
        :label="
          $str(
            'content_selection_relationships',
            'performelement_linked_review'
          )
        "
        :required="true"
      >
        <FormCheckboxGroup
          :validations="v => [v.required()]"
          name="selection_relationships"
        >
          <Checkbox
            v-for="relationship in coreRelationships"
            :key="relationship.id"
            :value="relationship.id"
            :checked="
              selectionRelationships.some(
                relationship_id => relationship_id === relationship.id
              )
            "
          >
            {{ relationship.name }}
          </Checkbox>
        </FormCheckboxGroup>
      </FormRow>

      <!-- For additional custom fields based on selected content type -->
      <FormScope
        v-if="selectedContentTypeSettingsComponent"
        path="content_type_settings"
      >
        <component
          :is="selectedContentTypeSettingsComponent"
          ref="typeSettings"
        />
      </FormScope>
    </PerformAdminCustomElementEdit>
  </div>
</template>

<script>
import PerformAdminCustomElementEdit from 'mod_perform/components/element/PerformAdminCustomElementEdit';
import FormScope from 'tui/components/reform/FormScope';
import { FormCheckboxGroup, FormRow, FormSelect } from 'tui/components/uniform';
// GraphQL
import contentTypesQuery from 'performelement_linked_review/graphql/content_types';
import Checkbox from 'tui/components/form/Checkbox';

export default {
  components: {
    Checkbox,
    FormCheckboxGroup,
    FormRow,
    FormScope,
    FormSelect,
    PerformAdminCustomElementEdit,
  },

  inheritAttrs: false,

  props: {
    identifier: String,
    isRequired: Boolean,
    rawData: Object,
    rawTitle: String,
    settings: Object,
    section: Object,
    sectionId: [Number, String],
  },

  data() {
    return {
      contentTypes: [
        {
          id: null,
          label: this.$str(
            'please_select_content_type',
            'performelement_linked_review'
          ),
        },
      ],
      initialValues: Object.assign(
        {
          content_type: null,
          content_type_settings: {},
        },
        this.rawData,
        {
          rawTitle: this.rawTitle,
          identifier: this.identifier,
          // The linked review question is always required
          responseRequired: true,
        }
      ),
      selectedTypeId: this.rawData.content_type || null,
      selectionRelationships: this.rawData.selection_relationships || [],
    };
  },

  apollo: {
    contentTypes: {
      query: contentTypesQuery,
      variables() {
        return {
          section_id: this.sectionId,
        };
      },
      update({ types }) {
        return [
          {
            id: null,
            label: this.$str(
              'please_select_content_type',
              'performelement_linked_review'
            ),
          },
        ].concat(types);
      },
    },
  },

  computed: {
    /**
     * The core relationships linked with the section.
     *
     * @return {Array}
     */
    coreRelationships() {
      return this.section.section_relationships.map(
        section_relationship => section_relationship.core_relationship
      );
    },

    /**
     * Gets the vue component that defines additional settings that are required for the selected content type.
     *
     * @return {function}
     */
    selectedContentTypeSettingsComponent() {
      if (
        this.$apollo.loading ||
        this.selectedType == null ||
        this.selectedType.admin_settings_component == null
      ) {
        return null;
      }
      return tui.asyncComponent(this.selectedType.admin_settings_component);
    },

    /**
     * The content type object that is selected.
     *
     * @return {Object}
     */
    selectedType() {
      return this.contentTypes.find(type => type.id === this.selectedTypeId);
    },
  },

  methods: {
    /**
     * Handle submission.
     *
     * @param values {Object}
     */
    handleSubmit(values) {
      let data = Object.assign({}, values);
      this.$emit('update', data);
    },

    /**
     * Update form fields based on type selection
     *
     * @param values {Object}
     */
    updateForm(values) {
      const newType = values.content_type;
      const typeUpdated = this.selectedTypeId !== newType;

      if (!typeUpdated) {
        return;
      }

      this.selectedTypeId = values.content_type;

      // The content type has changed, so the settings currently saved may not be valid.
      // Reset any additional settings to the component defaults, so nothing strange gets saved.
      let settings = {};
      if (this.selectedTypeId != null) {
        settings = JSON.parse(this.selectedType.available_settings);
      }
      this.$refs.form.$refs.form.update('content_type_settings', settings);
    },
  },
};
</script>

<lang-strings>
  {
    "performelement_linked_review": [
      "content_selection_relationships",
      "content_type",
      "please_select_content_type"
    ]
  }
</lang-strings>
