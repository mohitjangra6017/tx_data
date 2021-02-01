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
  @package tui
-->

<template>
  <Uniform
    v-if="initialValuesSet"
    :initial-values="initialValues"
    :errors="errorsForm"
    :validate="validate"
    input-width="full"
    @change="handleChange"
    @submit="submit"
  >
    <FormRowStack spacing="large">
      <Collapsible
        :label="$str('formemail_group_notifications', 'totara_tui')"
        :initial-state="true"
      >
        <FormRowStack spacing="large">
          <FormRow
            v-slot="{ id }"
            :label="
              $str('formemail_label_notificationshtmlheader', 'totara_tui')
            "
            :is-stacked="true"
          >
            <FormField
              v-slot="{ value, update }"
              :name="['formemail_field_notificationshtmlheader', 'value']"
              char-length="full"
            >
              <Editor
                :id="id"
                :value="value"
                :default-format="htmlFormat"
                :context-id="contextIdNumber"
                :usage-identifier="{
                  component: 'totara_tui',
                  area: 'formemail_notifications_htmlheader',
                }"
                variant="standard"
                @input="update"
              />
              <FormRowDetails
                :id="$id('formemail-notifications-htmlheader-details')"
              >
                {{
                  $str(
                    'formemail_details_notificationshtmlheader',
                    'totara_tui'
                  )
                }}
              </FormRowDetails>
            </FormField>
          </FormRow>

          <FormRow
            v-slot="{ id }"
            :label="
              $str('formemail_label_notificationshtmlfooter', 'totara_tui')
            "
          >
            <FormField
              v-slot="{ value, update }"
              :name="['formemail_field_notificationshtmlfooter', 'value']"
              char-length="full"
            >
              <Editor
                :id="id"
                :value="value"
                :default-format="htmlFormat"
                :context-id="contextIdNumber"
                :usage-identifier="{
                  component: 'totara_tui',
                  area: 'formemail_notifications_htmlfooter',
                }"
                variant="standard"
                @input="update"
              />
              <FormRowDetails
                :id="$id('formemail-notifications-htmlfooter-details')"
              >
                {{
                  $str(
                    'formemail_details_notificationshtmlfooter',
                    'totara_tui'
                  )
                }}
              </FormRowDetails>
            </FormField>
          </FormRow>

          <FormRow
            :label="
              $str('formemail_label_notificationstextfooter', 'totara_tui')
            "
            :is-stacked="true"
          >
            <FormTextarea
              :name="['formemail_field_notificationstextfooter', 'value']"
              spellcheck="false"
              :rows="rows('formemail_field_notificationstextfooter', 8, 30)"
              char-length="full"
              :aria-describedby="
                $id('formemail-notificationstextfooter-details')
              "
            />
            <FormRowDetails
              :id="$id('formemail-notifications-textfooter-details')"
            >
              {{
                $str('formemail_details_notificationstextfooter', 'totara_tui')
              }}
            </FormRowDetails>
          </FormRow>
          <FormRow>
            <div class="tui-settingsFormEmail__testEmailButtonGroup">
              <Button
                :styleclass="{ primary: false }"
                :text="$str('test_email_notification', 'totara_core')"
                :aria-label="
                  $str(
                    'test_email_notification',
                    'totara_core',
                    $str('tabemail', 'totara_tui') +
                      ' ' +
                      $str('settings', 'totara_core')
                  )
                "
                :disabled="isSending"
                @click="sendEmailNotification"
              />
              <InfoIconButton
                class="tui-settingsFormEmail__testEmailInfoButton"
                :is-help-for="$str('test_email_notification', 'totara_core')"
              >
                {{ $str('test_email_notification_help', 'totara_core') }}
              </InfoIconButton>
            </div>
          </FormRow>
        </FormRowStack>
      </Collapsible>

      <FormRow>
        <ButtonGroup>
          <Button
            :styleclass="{ primary: true }"
            :text="$str('save', 'totara_core')"
            :aria-label="
              $str(
                'saveextended',
                'totara_core',
                $str('tabemail', 'totara_tui') +
                  ' ' +
                  $str('settings', 'totara_core')
              )
            "
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
  FormField,
  FormTextarea,
} from 'tui/components/uniform';
import FormRowStack from 'tui/components/form/FormRowStack';
import FormRowDetails from 'tui/components/form/FormRowDetails';
import Button from 'tui/components/buttons/Button';
import ButtonGroup from 'tui/components/buttons/ButtonGroup';
import InfoIconButton from 'tui/components/buttons/InfoIconButton';
import Editor from 'tui/components/editor/Editor';
import { Format } from 'tui/editor';
import { notify } from 'tui/notifications';

// GraphQL
import tuiSendEmailNotification from 'core/graphql/theme_settings_send_email_notification';

export default {
  components: {
    Collapsible,
    Uniform,
    FormRow,
    FormRowStack,
    FormRowDetails,
    Button,
    ButtonGroup,
    InfoIconButton,
    Editor,
    FormField,
    FormTextarea,
  },

  props: {
    /**
     * Array of Objects, each describing the properties for fields that are part
     * of this Form. There is only an Object present in this Array if it came
     * from the server as it was previously saved
     */
    savedFormFieldData: {
      type: Array,
      default: function() {
        return [];
      },
    },
    /**
     * Object with keys present for each 'Flavour' of Totara possible on the
     * site, each key value is a Boolean representing whether that Flavour is
     * currently enabled. We use this to determine whether to show various
     * settings related to a given Flavour
     */
    flavoursData: {
      type: Object,
      default: function() {
        return {};
      },
    },
    /**
     * Saving state, controlled by parent component GraphQl mutation handling
     */
    isSaving: {
      type: Boolean,
      default: function() {
        return false;
      },
    },
    /**
     * Context ID.
     */
    contextId: {
      type: [Number, String],
      default: 0,
    },

    /**
     * Tenant ID or null if global/multi-tenancy not enabled.
     */
    selectedTenantId: Number,

    /**
     *  Customizable tenant settings
     */
    customizableTenantSettings: {
      type: [Array, String],
      required: false,
    },
  },

  data() {
    return {
      initialValues: {
        formemail_field_notificationshtmlheader: {
          value: '',
          type: 'html',
        },
        formemail_field_notificationshtmlfooter: {
          value: '',
          type: 'html',
        },
        formemail_field_notificationstextfooter: {
          value: '',
          type: 'text',
        },
      },
      editorFields: {
        formemail_field_notificationshtmlheader: {
          format: Format.HTML,
        },
        formemail_field_notificationshtmlfooter: {
          format: Format.HTML,
        },
      },
      initialValuesSet: false,
      htmlFormat: Format.HTML,
      contextIdNumber: parseInt(this.contextId),
      errorsForm: null,
      valuesForm: null,
      resultForm: null,
      theme_settings: theme_settings,
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
    // - then locally held state until (takes precedence until page is reloaded)
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
    this.initialValuesSet = true;
    this.$emit('mounted', { category: 'email', values: this.initialValues });
  },

  methods: {
    validate() {
      const errors = {};
      return errors;
    },

    handleChange(values) {
      this.valuesForm = values;
      if (this.errorsForm) {
        this.errorsForm = null;
      }
    },

    /**
     * Adjust the height of a textarea field as the user types, up to
     * a supplied limit, which then invokes a scrollbar
     **/
    rows(field, minLines, maxLines) {
      let text = '';
      if (this.valuesForm && field in this.valuesForm) {
        text = this.valuesForm[field].value;
      } else if (this.initialValues && field in this.initialValues) {
        text = this.initialValues[field].value;
      }
      let lines = (text.match(/\n/g) || []).length + 1;
      if (lines < minLines) {
        return minLines;
      }
      if (lines > maxLines) {
        return maxLines;
      }
      return lines;
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
        form: 'email',
        fields: [],
      };

      // Handle non-image upload form fields.
      Object.keys(currentValues).forEach(field => {
        let value;
        if (
          Object.keys(this.editorFields).find(
            editorField => editorField === field
          )
        ) {
          value = currentValues[field].value.getContent();
        } else {
          value = String(currentValues[field].value);
        }
        data.fields.push({
          name: field,
          type: currentValues[field].type,
          value: value,
        });
      });

      return data;
    },

    async sendEmailNotification() {
      this.isSending = true;
      const values = this.valuesForm || this.initialValues;

      try {
        const { data } = await this.$apollo.mutate({
          mutation: tuiSendEmailNotification,
          variables: {
            html_header: values[
              'formemail_field_notificationshtmlheader'
            ].value.getContent(),
            html_footer: values[
              'formemail_field_notificationshtmlfooter'
            ].value.getContent(),
            text_footer:
              values['formemail_field_notificationstextfooter'].value,
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
        console.error(e);
      }
      this.isSending = false;
    },
  },
};
</script>

<lang-strings>
{
  "totara_tui": [
    "formemail_group_notifications",
    "formemail_label_notificationshtmlheader",
    "formemail_label_notificationshtmlfooter",
    "formemail_label_notificationstextfooter",
    "formemail_details_notificationshtmlheader",
    "formemail_details_notificationshtmlfooter",
    "formemail_details_notificationstextfooter",
    "form_details_default",
    "tabemail",
    "settings_email_send_success",
    "settings_email_send_error"
  ],
  "totara_core": [
    "save",
    "saveextended",
    "settings",
    "enabled",
    "test_email_notification",
    "test_email_notification_help"
  ]
}
</lang-strings>

<style lang="scss">
.tui-settingsFormEmail__testEmailButtonGroup {
  display: flex;
}
.tui-settingsFormEmail__testEmailInfoButton {
  margin: auto 0 auto var(--gap-2);
}
</style>
