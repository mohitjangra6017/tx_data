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

  @author Dave Wallace <dave.wallace@totaralearning.com>
  @package tui
-->

<template>
  <Uniform
    v-if="initialValuesSet"
    :initial-values="initialValues"
    :errors="errorsForm"
    :validate="validate"
    :input-width="(activeTab.identifier === 'custom') ? 'full' : 'limited'"
    @change="handleChange"
    @submit="submit"
  >

    <SettingsFormRow
      v-if="getSettingsInHeading(null).length > 0"
      :settings="getSettingsInHeading(null)"
      :context-id="contextId"
      :all-theme-variables="allThemeVariables"
      :file-data="fileData"
      @updateImage="saveImage"
      @deleteImage="resetImage"
    />

    <FormRowStack spacing="large">
      <Collapsible
        v-for="(headingName, headingIdentifier) in getHeadings()"
        v-bind:key="headingIdentifier"
        :label="headingName"
      >
        <SettingsFormRow
          v-if="getSettingsInHeading(headingIdentifier).length > 0"
          :settings="getSettingsInHeading(headingIdentifier)"
          :context-id="contextId"
          :all-theme-variables="allThemeVariables"
          :file-data="fileData"
          @updateImage="saveImage"
          @deleteImage="resetImage"
        />

        <FormRow v-if="headingIdentifier === 'email-notifications'">
          <InputSet>
            <Button
              :styleclass="{ primary: false }"
              :text="$str('test_email_notification', 'totara_core')"
              :disabled="isSending"
              @click="sendEmailNotification"
            />
            <InfoIconButton
              class="tui-settingsFormBrand__testEmailInfoButton"
              :is-help-for="$str('test_email_notification', 'totara_core')"
            >
              {{ $str('test_email_notification_help', 'totara_core') }}
            </InfoIconButton>
          </InputSet>
        </FormRow>
      </Collapsible>

      <FormRow>
        <ButtonGroup>
          <Button
              :styleclass="{ primary: 'true' }"
              :text="getSaveButtonLabel()"
              :aria-label="getSaveButtonLabel()"
              :disabled="isSaving"
              type="submit"
          />
        </ButtonGroup>
      </FormRow>
    </FormRowStack>
  </Uniform>
</template>

<script>
import theme_settings from 'tui/lib/theme_settings';
import Collapsible from 'tui/components/collapsible/Collapsible';
import {
  Uniform,
  FormRow,
} from 'tui/components/uniform';
import FormRowStack from 'tui/components/form/FormRowStack';
import Button from 'tui/components/buttons/Button';
import ButtonGroup from 'tui/components/buttons/ButtonGroup';
import SettingsFormRow from "theme_kineo/components/theme_settings/SettingsFormRow";
import FileMixin from 'tui/mixins/settings_form_file_mixin';
import {EditorContent, Format} from "tui/editor";
import {notify} from 'tui/notifications';
import InfoIconButton from "tui/components/buttons/InfoIconButton";
import InputSet from "tui/components/form/InputSet";

// GraphQL
import tuiSendEmailNotification from 'core/graphql/theme_settings_send_email_notification';

export default {
  components: {
    SettingsFormRow,
    Collapsible,
    Uniform,
    EditorContent,
    Format,
    FormRow,
    FormRowStack,
    Button,
    ButtonGroup,
    InputSet,
    InfoIconButton
  },

  mixins: [FileMixin],

  props: {
    // Array of Objects, each describing the properties for fields that are part
    // of this Form. There is only an Object present in this Array if it came
    // from the server as it was previously saved
    savedFormFieldData: {
      type: Array,
      default: function() {
        return [];
      },
    },
    // Array of Objects, each describing the properties for fields that are part
    // of this Form. There is only an Object present in this Array if it was
    // present in Theme JSON data mapping (not GraphQL query), and the values
    // within each Object are defaults, not previously saved data.
    mergedDefaultCssVariableData: {
      type: Object,
      default: function() {
        return {};
      },
    },
    // Array of Objects, each describing the properties for fields that are part
    // of this Form. There is only an Object present in this Array if it was
    // present in Theme JSON data mapping (not GraphQL query), and the values
    // within each Object have processed/resolved values.
    mergedProcessedCssVariableData: {
      type: Array,
      default: function() {
        return [];
      },
    },
    // Saving state, controlled by parent component GraphQl mutation handling
    isSaving: {
      type: Boolean,
      default: function() {
        return false;
      },
    },
    // Array of settings to be displayed in this form.
    settings: {
      type: Array,
      default: function () {
        return [];
      }
    },

    // Array of Objects, each describing the properties for specifically file
    // upload fields that are part of this Form.
    fileFormFieldData: {
      type: Array,
      default: function() {
        return [];
      },
    },

    // Context ID.
    contextId: Number,

    headings: Object,

    activeTab: Object,

    allThemeVariables: Object

  },

  data() {
    let initialValues = {};
    let fileData = {};
    let editorFields = {};

    this.settings.forEach(setting => {
      switch (setting.type) {
        case 'editor':
          initialValues[setting.identifier] = {
            value: new EditorContent({
              format: setting.options.type,
              content: setting.default,
            }),
            type: 'value',
          };
          editorFields[setting.identifier] = {format: setting.options.type};
          break;

        case 'image':
          fileData[setting.identifier] = null;
          break;

        default:
          initialValues[setting.identifier] = {value: setting.default, type: 'value'}
      }
    });

    return {
      initialValues: initialValues,
      editorFields: editorFields,
      initialValuesSet: false,
      colourOverridesEnabled: false,
      errorsForm: null,
      valuesForm: null,
      resultForm: null,
      theme_settings: theme_settings,
      embeddedFormData: {
        flavours: null,
        mergedDefaultCSSVariableData: [],
        fileData: [],
      },
      fileData: fileData,
      isSending: false,
    };
  },

  /**
   * Prepare data for consumption within Uniform
   **/
  mounted() {
    // Set the data for this Form based on (in order):
    // - use previously saved Form data from GraphQL query
    // - missing field data then supplied by Theme JSON mapping data
    // - then locally held state (takes precedence until page is reloaded)
    let mergedFormData = this.theme_settings.mergeFormData(this.initialValues, [
      this.savedFormFieldData,
      this.valuesForm || [],
    ]);

    this.initialValues = this.theme_settings.getResolvedInitialValues(
        mergedFormData
    );

    this.initialValues = this.theme_settings.resolveEditorContentFields(
      this.initialValues,
      this.editorFields
    );

    // Update allThemeVariables with the most up-to-date values for the current form.
    Object.keys(this.initialValues).forEach((key) => {
      this.allThemeVariables[key].value = this.initialValues[key].value;
    });

    // handle fileuploader setup independently of Uniform and initialValues
    // because file uploading doesn't really work in a way that Uniform can
    // fully support
    for (let i = 0; i < this.fileFormFieldData.length; i++) {
      let fileData = this.fileFormFieldData[i];
      this.fileData[fileData.ui_key] = fileData;
    }

    // reactive data hook for override toggle
    this.colourOverridesEnabled = false;

    this.initialValuesSet = true;
    this.$emit(
        'mounted',
        {
          category: this.activeTab.identifier,
          values: this.formatDataForMutation(this.initialValues),
        }
    );
  },

  methods: {
    getHeadings() {
      let filteredHeadings = {};
      for (const headingsKey in this.headings) {
        if (this.getSettingsInHeading(headingsKey).length > 0) {
          filteredHeadings[headingsKey] = this.headings[headingsKey];
        }
      }
      return filteredHeadings;
    },

    getSettingsInHeading(heading) {
      return this.settings.filter(setting => setting.heading === heading);
    },

    validate() {
      const errors = {};
      return errors;
    },

    handleChange(values) {
      this.valuesForm = values;

      // Emit back up the ThemeSettings so we can run updateAllThemeVariables.
      this.$emit('updateAllThemeVariables', values);

      if (this.errorsForm) {
        this.errorsForm = null;
      }

      // update form-based reactive toggles
      if (typeof values.formcolours_field_useoverrides !== 'undefined') {
        this.colourOverridesEnabled =
            values.formcolours_field_useoverrides.value;
      }
    },

    /**
     * Handle submission of an embedded form.
     *
     * @param {Object} currentValues The submitted form data.
     */
    submit(currentValues) {
      if (this.errorsForm) {
          this.errorsForm = null;
        }
        this.resultForm = currentValues;

        let dataToMutate = this.formatDataForMutation(currentValues);
        this.$emit('submit', dataToMutate);
    },

    /**
     * Takes Form field data and formats it to meet GraphQL mutation expectations
     *
     * @param {Object} currentValues The submitted form data.
     * @return {Object}
     **/
    formatDataForMutation(currentValues) {
      let data = {
        form: this.activeTab.identifier,
        fields: [],
        files: [],
      };

      Object.keys(currentValues).forEach(field => {
        let value;
        if (
          Object.keys(this.editorFields).find(
            editorField => editorField === field
          )
        ) {
          value = currentValues[field].value.getContent();
          // If the form element is never viewed, then we don't get a clean string.
          // Force this to null so we don't try and push an object, or something else.
          if (typeof value !== 'string') {
            value = null;
          }
          value = value ? value : '';
        } else {
          value = String(currentValues[field].value);
        }
        data.fields.push({
          name: field,
          type: currentValues[field].type,
          value: value,
        });
      });

      Object.keys(this.fileData).forEach(file => {
        if (this.fileData[file]) {
          data.files.push(this.fileData[file]);
        }
      });

      return data;
    },

    /**
     * Checks if there are state related CSS variables for all current Form
     * fields exposed in the UI, for example hover states, and resolves a value
     * for each one found.
     *
     * @param {Object} currentValues
     * @param {Array} nonDefaultFields
     * @param {Array} states
     * @return {Array}
     **/
    resolveDerivedFields(currentValues, nonDefaultFields, states) {
      let derivedFields = [];
      nonDefaultFields.map(field => {
        let variableName = field.name;
        states.map(state => {
          let stateObj = this.mergedDefaultCssVariableData[
          variableName + '-' + state
              ];
          if (stateObj) {
            let variableData = Object.assign(stateObj, {});
            variableData.transform.type = 'value';
            variableData.transform.source = currentValues[variableName].value;
            // add a resolved variable object for the given state to our Array
            derivedFields.push({
              name: variableName + '-' + state,
              type: 'value',
              value: String(
                  this.theme_settings.resolveCSSVariableValue(
                      variableData,
                      currentValues
                  )
              ),
            });
            this.addSelectorState(nonDefaultFields, variableName, state);
          }
        });
      });
      return derivedFields;
    },

    /**
     * Some fields, like switches, controls other properties. The properties
     * they control are defined in the selectors array and we need to update
     * the selectors in case any selector is state related.
     *
     * @param {Array} nonDefaultFields
     * @param {String} selector
     * @param {String} state
     */
    addSelectorState(nonDefaultFields, selector, state) {
      nonDefaultFields.forEach(field => {
        if (field.selectors) {
          const selectorState = selector + '-' + state;
          if (
              !field.selectors.some(s => s === selectorState) &&
              field.selectors.some(s => s === selector)
          ) {
            field.selectors.push(selectorState);
          }
        }
      });
    },

    getSaveButtonLabel() {
      return this.$str('save', 'totara_core') + ' ' + this.activeTab.name.replace('*', '');
    },

    // This function is taken from core wholesale.
    // There is an assumption here that the 3 fields are present (they should be hardcoded in SettingsResolver.php)
    async sendEmailNotification() {
      this.isSending = true;
      const values = this.valuesForm || this.initialValues;

      try {
        const {data} = await this.$apollo.mutate({
          mutation: tuiSendEmailNotification,
          variables: {
            html_header: values['formbrand_field_notificationshtmlheader'].value.getContent(),
            html_footer: values['formbrand_field_notificationshtmlfooter'].value.getContent(),
            text_footer: values['formbrand_field_notificationstextfooter'].value,
            tenant_id: this.selectedTenantId,
          },
        });

        if (data['core_theme_settings_send_email_notification']) {
          notify({
            message: this.$str('settings_email_send_success', 'totara_tui'),
            type: 'success',
          });
        } else {
          notify({
            message: this.$str('settings_email_send_error', 'totara_tui'),
            type: 'error',
          });
        }
      } catch (e) {
        notify({
          message: this.$str('settings_email_send_error', 'totara_tui'),
          type: 'error',
        });
      }
      this.isSending = false;
    },

  },
};
</script>

<lang-strings>
{
"totara_tui": [
"settings_email_send_error",
"settings_email_send_success"
],
"totara_core": [
"save",
"saveextended",
"settings",
"test_email_notification",
"test_email_notification_help"
]
}
</lang-strings>