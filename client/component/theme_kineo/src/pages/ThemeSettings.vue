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

  @author Johannes Cilliers <johannes.cilliers@totaralearning.com>
  @author Dave Wallace <dave.wallace@totaralearning.com>
  @module tui
-->

<template>
  <div class="tui-themeSettings">
    <PageHeading :title="pageTitle" />

    <BackupAndRestoreButtons
        v-show="!selectedTenantId || (selectedTenantId && tenantOverridesEnabled)"
        :selected-tenant-id="getSelectedTenant"
        :theme-name="theme"
    />

    <div class="tui-themeSettings__content">
      <Loader :loading="!allDataLoaded">
        <div
          v-if="dataIsReady && embeddedFormData.formFieldData.brand"
          class="tui-themeSettings__forms"
        >
          <Uniform
            v-if="initialValues && selectedTenantId"
            :initial-values="initialValues"
            :errors="errorsForm"
            :validate="validate"
            @change="autoSubmitTenantForm"
          >
            <FormRow
              v-slot="{ label }"
              :label="$str('formtenant_label_tenant', 'totara_tui')"
              :is-stacked="true"
            >
              <FormToggleSwitch
                :name="['formtenant_field_tenant', 'value']"
                :toggle-first="true"
                :aria-label="label"
              />
              <FormRowDetails>
                {{ $str('formtenant_details_tenant', 'totara_tui') }}
              </FormRowDetails>
            </FormRow>
          </Uniform>

          <Tabs
            v-show="
              !selectedTenantId || (selectedTenantId && tenantOverridesEnabled)
            "
            data-selected="0"
            content-spacing="large"
          >
            <Tab
              :id="settingTab.identifier"
              :name="settingTab.name"
              :always-render="true"
              v-for="(settingTab) in themeSettings.tabs"
              v-bind:key="settingTab.identifier"
              v-if="themeSettings.settings.filter(setting => setting.tab === settingTab.identifier).length > 0"
            >
              <article
                v-if="settingTab.description.length > 0"
                class="settings-tab-description"
                v-html="settingTab.description"
              />

              <SettingsFormGeneric
                :is-saving="isSaving"
                :context-id="embeddedFormData.contextId"
                :settings="getSettingsInTab(settingTab.identifier)"
                :all-theme-variables="allThemeVariables"
                :active-tab="settingTab"
                :headings="settingTab.headings"
                :saved-form-field-data="embeddedFormData.formFieldData[settingTab.identifier]"
                :merged-default-css-variable-data="embeddedFormData.mergedDefaultCSSVariableData"
                :merged-processed-css-variable-data="embeddedFormData.mergedProcessedCSSVariableData"
                :file-form-field-data="embeddedFormData.fileData"
                @submit="submit"
                @mounted="setInitialTenantCategoryValues"
                @updateAllThemeVariables="updateAllThemeVariables"
              >
              </SettingsFormGeneric>
            </Tab>
          </Tabs>

        </div>
      </Loader>
    </div>
  </div>
</template>

<script>
import Loader from 'tui/components/loading/Loader';
import Tab from 'tui/components/tabs/Tab';
import Tabs from 'tui/components/tabs/Tabs';
import { Uniform, FormRow, FormToggleSwitch } from 'tui/components/uniform';
import FormRowDetails from 'tui/components/form/FormRowDetails';
import PageHeading from 'tui/components/layouts/PageHeading';
import tuiQueryThemesWithVariables from 'totara_tui/graphql/themes_with_variables';
import tuiQueryThemeSettings from 'core/graphql/get_theme_settings';
import tuiUpdateThemeSettings from 'core/graphql/update_theme_settings';
import ButtonGroup from 'tui/components/buttons/ButtonGroup';
import { notify } from 'tui/notifications';
import theme_settings from 'tui/lib/theme_settings';
import SettingsFormGeneric from "theme_kineo/components/theme_settings/SettingsFormGeneric";
import BackupAndRestoreButtons from "../components/theme_settings/BackupAndRestoreButtons";
import {customValidation as cv} from 'theme_kineo/customValidation';

export default {
  components: {
    Loader,
    Tabs,
    Tab,
    Uniform,
    FormRow,
    FormRowDetails,
    FormToggleSwitch,
    PageHeading,
    SettingsFormGeneric,
    ButtonGroup,
    BackupAndRestoreButtons
  },

  props: {
    /**
     * Theme to change settings for.
     */
    theme: {
      type: String,
      required: true,
    },

    themeName: String,

    /**
     * Tenant ID or null if global/multi-tenancy not enabled.
     */
    selectedTenantId: Number,

    /**
     * Tenant Name or null if global/multi-tenancy not enabled.
     */
    selectedTenantName: String,

    themeSettings: Object,

    /**
     * Customizable tenant settings
     */
    customizableTenantSettings: Object,
  },

  data() {

    let initialValues = {
      formtenant_field_tenant: {
        value: false,
        type: 'boolean',
      },
    };
    this.themeSettings.settings.forEach(setting => {
      if (setting.type !== 'image') {
        initialValues[setting.identifier] = {
          value: setting.default,
          type: 'value'
        }
      }
    });

    return {
      tenantOverridesEnabled: false,
      query: tuiQueryThemeSettings,
      theme_settings: theme_settings,
      initialValues: initialValues,
      embeddedFormData: {
        flavours: null,
        formFieldData: {
          // TODO: For now, we have to add each tab as its own key.
          tenant: [],
          elements: [],
          fonts: [],
          navigation: [],
          brand: [],
          pages: [],
          blocks: [],
          isotope: [],
          custom: [],
          images: []
        },
        mergedDefaultCSSVariableData: [],
        fileData: [],
      },
      customCSSEnabled: true,
      errorsForm: null,
      valuesForm: null,
      resultForm: null,
      // data is merged and resolved
      dataIsReady: false,
      // mutation has executed
      isSaving: false,
      // raw CSS variable data, this is handled via fetch, not GraphQL
      rawCSSVariableData: null,
      // Categories to add when tenant is enabled
      tenantCategories: {},
      allThemeVariables: {}
    };
  },

  computed: {
    pageTitle() {
      if (!this.selectedTenantName) {
        // Editing current theme
        return this.$str(
          'edittheme',
          'totara_core',
          this.themeName || this.theme
        );
      } else {
        // Editing current Theme Settings, but for a Tenant
        return this.$str(
          'editthemetenant',
          'totara_core',
          this.selectedTenantName
        );
      }
    },
    allFetchesLoaded() {
      return !!this.rawCSSVariableData;
    },
    allQueriesLoaded() {
      return (
        !!this.core_get_theme_settings &&
        !!this.totara_tui_themes_with_variables
      );
    },
    allDataLoaded() {
      return (
        !!this.core_get_theme_settings &&
        !!this.totara_tui_themes_with_variables &&
        !!this.rawCSSVariableData
      );
    },

    getSelectedTenant() {
      if (!this.selectedTenantId) {
        return 0;
      }
      return this.selectedTenantId;
    }
  },

  watch: {
    allQueriesLoaded(val) {
      if (val) {
        this.loadCSSVariableData();
      }
    },
    allDataLoaded() {
      this.transformQueryAndFetchData(
        this.core_get_theme_settings,
        this.rawCSSVariableData
      );
      this.setTenantFormValues();
    },
  },

  methods: {
    /**
     * @param {String} tab
     */
    getSettingsInTab(tab) {
      return this.themeSettings.settings.filter(setting => setting.tab === tab);
    },

    /**
     * Prepare data for consumption within this component's Uniform
     **/
    setTenantFormValues() {
      let mergedFormData = this.theme_settings.mergeFormData(
          this.initialValues,
          [this.embeddedFormData.formFieldData.tenant]
      );
      this.initialValues = this.theme_settings.getResolvedInitialValues(
          mergedFormData
      );

      // we some data properties to be reactive, based off query data or initial
      // form values
      this.tenantOverridesEnabled = this.initialValues.formtenant_field_tenant.value;
    },

    /**
     * Fetches JSON CSS Variable mappings from multiple TUI-based themes in the
     * inheritance chain.
     **/
    async loadCSSVariableData() {
      let result = await Promise.all(
          this.totara_tui_themes_with_variables.map(async theme => {
            const response = await fetch(
                this.$url('/totara/tui/json.php', {
                  bundle: 'theme_' + theme,
                  file: 'css_variables',
                })
            );
            const data = await response.json();
            if (!data) {
              console.error(
                  `[ThemeSettings] Configuration error: 'css_variables' file is missing for the selected theme.` +
                  `Please follow the TUI build instructions in order to generate the required file.`
              );
              return {};
            }
            return data.vars;
          })
      );
      this.rawCSSVariableData = result.filter(response => response !== null);
    },

    /**
     * Performs several transforms on data coming from multiple sources, this
     * transformed data is then passed as props to embedded Uniform components
     *
     * @param {object} queryData // GraphQL query data
     * @param {array} fetchData // JSON fetch CSS variable data from TUI Themes
     **/
    transformQueryAndFetchData(queryData, fetchData) {
      // embedded Forms need to know what "Flavours" of Totara are enabled, to
      // deteermine whether we render settings for those Flavours
      this.embeddedFormData.flavours = queryData.flavours;

      // separate file upload data needs merging into the generic form field
      // Object mapping, GraphQL query needed to be structured differently
      // from basic form field data. We'll pass this into Forms that need file
      // uploading
      this.embeddedFormData.fileData = queryData.files;

      // file uploading needs to know the context of the user otherwise access
      // will be blocked when trying to access system context when not admin.
      this.embeddedFormData.contextId = parseInt(queryData.context_id);

      // previously saved, non-default Form data to be used as initialValues
      // within each embedded Form
      const unresolvedFormData = queryData.categories;
      for (let i = 0; i < unresolvedFormData.length; i++) {
        this.embeddedFormData.formFieldData[unresolvedFormData[i].name] =
          unresolvedFormData[i].properties;
      }

      // Get all our default variables out the JSON files so we can merge with the CSS defaults.
      let settingDefaults = {};
      this.themeSettings.settings.forEach(setting => {
        let defaultSetting = {};
        if (typeof setting.default === 'string' && setting.default.indexOf('@') === 0) {
          defaultSetting.type = 'var';
          defaultSetting.value = setting.default.substring(1);
        } else {
          defaultSetting.type = 'value';
          defaultSetting.value = setting.default;
        }
        settingDefaults[setting.identifier] = defaultSetting;
      });

      // merge all theme CSS variable data in the theme inheritance chain
      let mergedDefaultThemeVariableData = this.theme_settings.mergeCSSVariableData(
        fetchData.concat(settingDefaults)
      );

      let mergedProcessedCSSVariableData = this.theme_settings.processCSSVariableData(
        mergedDefaultThemeVariableData
      );

      // finally set the merged and value-resolved CSS Variable data, ready for
      // passing as a prop to Forms that need it
      this.embeddedFormData.mergedDefaultCSSVariableData = mergedDefaultThemeVariableData;
      this.embeddedFormData.mergedProcessedCSSVariableData = mergedProcessedCSSVariableData;

      // Set up our allThemeVariables data which is needed for validation.
      mergedProcessedCSSVariableData.forEach(item => this.allThemeVariables[item.name] = {
        "type": item.type,
        "value": item.value
      });
      cv.initSettings(this.allThemeVariables);

      // we're ready to go
      this.dataIsReady = true;

      return;
    },

    validate() {
      const errors = {};
      return errors;
    },

    /**
     * Check whether the specific category can be customized
     *
     * @param {String} category
     * @return {Boolean}
     */
    canEditCategory(category) {
      return (
          !this.selectedTenantId ||
          (this.customizableTenantSettings &&
              !!this.customizableTenantSettings[category])
      );
    },

    /**
     * Return customizable settings in a specific category
     *
     * @param {String} category
     * @return {String|Object|null}
     */
    customizableTenantCategorySettings(category) {
      if (
          !this.customizableTenantSettings ||
          !this.customizableTenantSettings[category]
      ) {
        return null;
      }

      return this.customizableTenantSettings[category];
    },

    /**
     * Takes Form field data and formats it to meet GraphQL mutation expectations
     *
     * @param {Object} currentValues The submitted form data.
     * @return {Object}
     **/
    formatDataForMutation(currentValues) {
      let data = {
        form: 'tenant',
        fields: [
          {
            name: 'formtenant_field_tenant',
            type: 'boolean',
            value: String(currentValues['formtenant_field_tenant'].value)
          }
        ],
      };

      return data;
    },

    /**
     * Handle immediate submission of the single-field Tenant Form in this
     * component.
     *
     * @param {Object} currentValues The submitted form data.
     */
    autoSubmitTenantForm(currentValues) {
      this.valuesForm = currentValues;
      if (this.errorsForm) {
        this.errorsForm = null;
      }
      this.resultForm = currentValues;

      // update form-based reactive toggles
      if (typeof currentValues.formtenant_field_tenant !== 'undefined') {
        this.tenantOverridesEnabled =
          currentValues.formtenant_field_tenant.value;
      }

      let dataToMutate = this.formatDataForMutation(currentValues);
      // If enabling custom tenant override, we also pass brand and colours
      this.submit(dataToMutate, this.selectedTenantId && this.tenantOverridesEnabled);
    },

    /**
     * Set initial tenant category data to add when custom configuration is enabled
     *
     * @param {Object} categoryData
     */
    setInitialTenantCategoryValues(categoryData) {
      let category = categoryData.category;

      if (
          this.customizableTenantSettings &&
          !!this.customizableTenantSettings[category]
      ) {
        this.tenantCategories[category] = categoryData.values;
      }
    },

    /**
     * Handle submission of either top level or embedded forms. Each submission
     * sends in only its related form data, to replace only that chunk during
     * the mutation.
     *
     * @param {Object} payload The submitted form data expressed in full data
     *                          structure expected by mutation.
     * @param {Boolean} addTenantDefaults
     */
    async submit(payload, addTenantDefaults) {
      let categoryData = [
        {
          name: payload.form,
          properties: payload.fields,
        },
      ];

      if (addTenantDefaults) {
        Object.keys(this.tenantCategories).forEach(
            function (category) {
              if (this.tenantCategories[category].form) {
                categoryData.push({
                  name: this.tenantCategories[category].form,
                  properties: this.tenantCategories[category].fields,
                });
              }
            },
            this
        );
      }

      let fileData = [];
      if (payload.files) {
        fileData = payload.files.map(file => {
          return {
            ui_key: file.ui_key,
            draft_id: file.file_area.draft_id,
            action: file.action || 'SAVE',
          };
        });
      }

      this.isSaving = true;

      try {
        await this.$apollo.mutate({
          mutation: tuiUpdateThemeSettings,
          variables: {
            theme: this.theme,
            tenant_id: this.selectedTenantId,
            categories: categoryData,
            files: fileData,
          },
          update: (cache, { data }) => {
            cache.writeQuery({
              query: tuiQueryThemeSettings,
              variables: {
                theme: this.theme,
                tenant_id: this.selectedTenantId,
              },
              data: {
                core_get_theme_settings: data.core_update_theme_settings,
              },
            });
            this.transformQueryAndFetchData(
                data.core_update_theme_settings,
                this.rawCSSVariableData
            );
          },
        });

        notify({
          message: this.$str('settings_success_save', 'totara_tui'),
          type: 'success',
        });
      } catch (e) {
        notify({
          message: this.$str('settings_error_save', 'totara_tui'),
          type: 'error',
        });
        console.error(e);
      }

      this.isSaving = false;

    },

    updateAllThemeVariables(values) {
      Object.keys(values).forEach(key => {
        if (this.allThemeVariables.hasOwnProperty(key)) {
          this.allThemeVariables[key].value = values[key].value;
        }
      });
    }

  },
  apollo: {
    core_get_theme_settings: {
      query: tuiQueryThemeSettings,
      variables() {
        return {
          theme: this.theme,
          tenant_id: this.selectedTenantId,
        };
      },
    },
    totara_tui_themes_with_variables: {
      query: tuiQueryThemesWithVariables,
      variables() {
        return {
          theme: this.theme,
        };
      },
    },
  },
};
</script>

<lang-strings>
{
  "totara_tui": [
    "formtenant_label_tenant",
    "formtenant_details_tenant",
    "settings_error_save",
    "settings_success_save",
    "tabbrand",
    "tabcolours",
    "tabimages",
    "tabcustom"
  ],
  "totara_core": [
    "edittheme",
    "editthemetenant",
    "save",
    "saveextended",
    "settings"
  ]
}
</lang-strings>

<style lang="scss">
.tui-themeSettings {
  &__content {
    margin-top: var(--gap-8);
  }

  &__forms {
    @include tui-stack-vertical(var(--gap-8));
  }
  .settings-tab-description {
    margin-bottom: var(--gap-8);
    font-weight: bold;
  }
}
</style>
