<!--
  This file is part of Totara Enterprise Extensions.

  Copyright (C) 2021 onwards Totara Learning Solutions LTD

  Totara Enterprise Extensions is provided only to Totara
  Learning Solutions LTD’s customers and partners, pursuant to
  the terms and conditions of a separate agreement with Totara
  Learning Solutions LTD or its affiliate.

  If you do not have an agreement with Totara Learning Solutions
  LTD, you may not access, use, modify, or distribute this software.
  Please contact [licensing@totaralearning.com] for more information.

  @author Kian Nguyen <kian.nguyen@totaralearning.com>
  @module totara_notification
-->
<template>
  <Uniform
    v-if="formInitialValues"
    ref="preferenceForm"
    :initial-values="formInitialValues"
    :errors="errors"
    input-width="full"
    class="tui-notificationPreferenceForm"
    @submit="submitForm"
  >
    <!-- Start of title field -->
    <FormRow
      v-slot="{ id }"
      :label="$str('notification_title_label', 'totara_notification')"
      :required="!parentValue || !parentValue.title || parentValue.title === ''"
    >
      <!--
        We are requiring the field title if it is a new custom notification.
        Otherwise we will disabled the field, lock it down to the parent's value
        and will not require it.
      -->
      <FormText
        :id="id"
        :name="['title', 'value']"
        :disabled="disableTitleField"
        :validations="v => [v.required()]"
        @input="resetErrorFieldTitle"
      />
    </FormRow>
    <!-- End of title field -->

    <!-- Start of recipient field -->
    <FormRow
      :label="$str('recipient', 'totara_notification')"
      :required="!parentValue"
    >
      <template v-slot="{ id }">
        <Checkbox
          v-if="showCustomCheckBoxes"
          :aria-label="$str('enable_custom_recipient', 'totara_notification')"
          :checked="customisation.recipient"
          :value="customisation.recipient ? '1' : '0'"
          name="override_recipient"
          @change="customisation.recipient = $event"
        >
          {{ $str('override', 'totara_notification') }}
        </Checkbox>
        <FormSelect
          :aria-labelledby="id"
          name="recipient"
          :options="recipientOptions"
          :disabled="showCustomCheckBoxes && !customisation.recipient"
          :validations="v => [v.required()]"
        />
      </template>
    </FormRow>
    <!-- End of recipient field -->

    <!-- Start of schedule offset field -->
    <FormRow
      :label="$str('notification_schedule_label', 'totara_notification')"
      :required="!parentValue"
    >
      <template v-slot="{ id }">
        <Checkbox
          v-if="showCustomCheckBoxes"
          :aria-label="$str('enable_custom_schedule', 'totara_notification')"
          :checked="customisation.schedule"
          :value="customisation.schedule ? '1' : '0'"
          name="override_schedule"
          @change="customisation.schedule = $event"
        >
          {{ $str('override', 'totara_notification') }}
        </Checkbox>
        <FormRadioGroup
          :aria-labelledby="id"
          :disabled="showCustomCheckBoxes && !customisation.schedule"
          :validations="v => [v.required()]"
          :name="['schedule_type', 'value']"
          @input="updateSchedule($event)"
        >
          <Radio v-if="showScheduleOnEvent" :value="scheduleTypes.ON_EVENT">
            {{ $str('schedule_form_label_on_event', 'totara_notification') }}
          </Radio>
          <FormRadioWithInput
            v-if="showScheduleBeforeEvent"
            v-slot="{
              disabledRadio,
              nameLabel,
              setAccessibleLabel,
              update,
              value,
            }"
            :disabled="showCustomCheckBoxes && !customisation.schedule"
            :name="['schedule_offset', scheduleTypes.BEFORE_EVENT]"
            :value="scheduleTypes.BEFORE_EVENT"
            :text="
              $str('schedule_form_label_before_event', 'totara_notification')
            "
          >
            <RadioNumberInput
              :disabled="disabledRadio"
              :name="nameLabel"
              :value="value"
              @input="updateSchedule($event, update)"
              @accessible-change="
                a =>
                  setAccessibleLabel(
                    $str(
                      'schedule_label_before_event',
                      'totara_notification',
                      a
                    )
                  )
              "
            />
          </FormRadioWithInput>
          <FormRadioWithInput
            v-if="showScheduleAfterEvent"
            v-slot="{
              disabledRadio,
              nameLabel,
              setAccessibleLabel,
              update,
              value,
            }"
            :disabled="showCustomCheckBoxes && !customisation.schedule"
            :name="['schedule_offset', scheduleTypes.AFTER_EVENT]"
            :value="scheduleTypes.AFTER_EVENT"
            :text="
              $str('schedule_form_label_after_event', 'totara_notification')
            "
          >
            <RadioNumberInput
              :disabled="disabledRadio"
              :name="nameLabel"
              :value="value"
              @input="updateSchedule($event, update)"
              @accessible-change="
                a =>
                  setAccessibleLabel(
                    $str('schedule_label_after_event', 'totara_notification', a)
                  )
              "
            />
          </FormRadioWithInput>
        </FormRadioGroup>
      </template>
    </FormRow>
    <!-- End of schedule offset field -->

    <!-- Start of delivery channel table/field -->
    <FormRow :label="$str('delivery_label', 'totara_notification')">
      <template v-slot="{ id }">
        <Checkbox
          v-if="showCustomCheckBoxes"
          :value="customisation.forcedDeliveryChannels ? '1' : '0'"
          name="override_forced_delivery_channels"
          :checked="customisation.forcedDeliveryChannels"
          :aria-label="
            $str(
              'enable_custom_forced_delivery_channels',
              'totara_notification'
            )
          "
          @change="customisation.forcedDeliveryChannels = $event"
        >
          {{ $str('override', 'totara_notification') }}
        </Checkbox>

        <FormField :name="['forced_delivery_channels', 'value']">
          <template v-slot="{ value, update }">
            <ForceDeliveryChannels
              :aria-labelledby="id"
              :default-delivery-channels="defaultDeliveryChannels"
              :forced-delivery-channels="value"
              :disabled="
                showCustomCheckBoxes && !customisation.forcedDeliveryChannels
              "
              @update-forced-delivery-channels="update"
            />
          </template>
        </FormField>
      </template>
    </FormRow>
    <!-- End of delivery channel table/field -->

    <!-- Start of the subject field -->
    <FormRow
      :label="$str('notification_subject_label', 'totara_notification')"
      :required="requiredSubject"
    >
      <template v-slot="{ id }">
        <Checkbox
          v-if="showCustomCheckBoxes"
          :aria-label="$str('enable_custom_subject', 'totara_notification')"
          :checked="customisation.subject"
          :value="customisation.subject ? '1' : '0'"
          name="override_subject"
          @change="customisation.subject = $event"
        >
          {{ $str('override', 'totara_notification') }}
        </Checkbox>

        <!-- We are only requiring the field subject if the parent does not have one -->
        <FormField
          v-slot="{ value, update }"
          :name="['subject', 'value']"
          :validate="validateSubjectEditor"
          :disabled="showCustomCheckBoxes && !customisation.subject"
          char-length="full"
        >
          <!-- We are only requiring the field body if the parent does not have one -->
          <Editor
            :id="id"
            :value="value"
            :context-id="contextId"
            :usage-identifier="{
              component: 'totara_notification',
              area: 'notification_subject',
            }"
            :extra-extensions="[
              {
                name: 'weka_notification_placeholder_extension',
                options: {
                  resolver_class_name: resolverClassName,
                },
              },
            ]"
            class="tui-notificationPreferenceForm__subjectEditor"
            variant="description"
            :compact="true"
            @fix-editor-content="updateSubjectEditor($event, update)"
            @input="updateSubjectEditor($event, update)"
          />
        </FormField>
      </template>
    </FormRow>
    <!-- End of the subject field -->

    <!-- Start of notification body field -->
    <FormRow
      :required="requiredBody"
      :label="$str('notification_body_label', 'totara_notification')"
    >
      <template v-slot="{ id }">
        <Checkbox
          v-if="showCustomCheckBoxes"
          :aria-label="$str('enable_custom_body', 'totara_notification')"
          :checked="customisation.body"
          :value="customisation.body ? '1' : '0'"
          name="override_body"
          @change="customisation.body = $event"
        >
          {{ $str('override', 'totara_notification') }}
        </Checkbox>
        <FormField
          v-slot="{ value, update }"
          :name="['body', 'value']"
          :validate="validateBodyEditor"
          :disabled="showCustomCheckBoxes && !customisation.body"
          char-length="full"
        >
          <!-- We are only requiring the field body if the parent does not have one -->
          <Editor
            :id="id"
            :value="value"
            :context-id="contextId"
            :usage-identifier="{
              component: 'totara_notification',
              area: 'notification_body',
            }"
            :extra-extensions="[
              {
                name: 'weka_notification_placeholder_extension',
                options: {
                  resolver_class_name: resolverClassName,
                },
              },
            ]"
            class="tui-notificationPreferenceForm__bodyEditor"
            variant="description"
            @fix-editor-content="updateBodyEditor($event, update)"
            @input="updateBodyEditor($event, update)"
          />
        </FormField>
      </template>
    </FormRow>
    <!-- End of notification body field -->

    <!-- Start of notification status field -->
    <FormRow
      v-slot="{ id }"
      :label="$str('notification_status_label', 'totara_notification')"
    >
      <Checkbox
        v-if="showCustomCheckBoxes"
        :aria-label="$str('enable_custom_status', 'totara_notification')"
        :checked="customisation.enabled"
        :value="customisation.enabled ? '1' : '0'"
        name="override_enabled"
        @change="customisation.enabled = $event"
      >
        {{ $str('override', 'totara_notification') }}
      </Checkbox>

      <FormCheckbox
        :id="id"
        :name="['enabled', 'value']"
        :disabled="showCustomCheckBoxes && !customisation.enabled"
      >
        {{ $str('enabled', 'totara_core') }}
      </FormCheckbox>
    </FormRow>
    <!-- End of notification status field -->

    <FormRow>
      <ButtonGroup class="tui-notificationPreferenceForm__buttonGroup">
        <Button
          :styleclass="{ primary: true }"
          :text="$str('save', 'totara_core')"
          :aria-label="$str('save', 'totara_core')"
          type="submit"
        />

        <Cancel @click="$emit('cancel')" />
      </ButtonGroup>
    </FormRow>
  </Uniform>
</template>

<script>
import {
  FormCheckbox,
  FormField,
  FormRadioGroup,
  FormRadioWithInput,
  FormText,
  FormSelect,
  Uniform,
} from 'tui/components/uniform';
import FormRow from 'tui/components/form/FormRow';
import Radio from 'tui/components/form/Radio';
import RadioNumberInput from 'tui/components/form/RadioNumberInput';
import { EditorContent } from 'tui/editor';
import Editor from 'tui/components/editor/Editor';
import ButtonGroup from 'tui/components/buttons/ButtonGroup';
import Button from 'tui/components/buttons/Button';
import Cancel from 'tui/components/buttons/Cancel';
import {
  getDefaultNotificationPreference,
  SCHEDULE_TYPES,
  validatePreferenceProp,
  validateAvailableRecipientsProp,
  validateDefaultDeliveryChannelsProp,
} from '../../internal/notification_preference';
import ForceDeliveryChannels from 'totara_notification/components/form/field/ForceDeliveryChannels';
import Checkbox from 'tui/components/form/Checkbox';

// GraphQL queries
import validateNotificationPreferenceInput from 'totara_notification/graphql/validate_notification_preference_input';

/**
 * This function is here because it does not fit in the component's functionalities.
 * It is just a helper do all sort of business logic before the initialisation of component state.
 *
 * @param {Object} currentPreference
 * @param {Object|NULL} parentValue
 *
 * @return {Object}
 */
function createFormValues(currentPreference, parentValue) {
  const formValue = {
    subject: {
      // At this initial state, we keep it undefined and definitely the
      // lower part of this function will have to populate this field.
      value: undefined,
      type: EditorContent,
    },
    body: {
      // At this initial state, we keep it undefined and definitely the
      // lower part of this function will have to populate this field.
      value: undefined,
      type: EditorContent,
    },
    title: {
      value:
        !parentValue || !parentValue.title
          ? currentPreference.title
          : parentValue.title,
      type: 'text',
    },
    schedule_type: {
      value:
        !parentValue || currentPreference.overridden_schedule
          ? currentPreference.schedule_type
          : parentValue.schedule_type,
      type: 'text',
    },
    schedule_offset: {
      // Sensible defaults are set here, while the real defaults are set below
      [SCHEDULE_TYPES.BEFORE_EVENT]: 1,
      [SCHEDULE_TYPES.AFTER_EVENT]: 1,
      [SCHEDULE_TYPES.ON_EVENT]: 0,
      type: 'number',
    },
    recipient: currentPreference.recipient
      ? currentPreference.recipient.class_name
      : null,
    enabled: {
      value:
        !parentValue || currentPreference.overridden_enabled
          ? currentPreference.enabled
          : parentValue.enabled,
    },

    // At the initial state, we keep it as empty array and the lower part of this function will
    // populate the value of this field.
    forced_delivery_channels: {
      value:
        !parentValue || currentPreference.forced_delivery_channels
          ? currentPreference.forced_delivery_channels
          : parentValue.forced_delivery_channels || [],
      type: 'array',
    },
  };

  // Set the default offset values (it involves a little bit of looking at schedule_type).
  // We only want to change the active type away from defaults.
  if (formValue.schedule_type.value !== SCHEDULE_TYPES.ON_EVENT) {
    formValue.schedule_offset[formValue.schedule_type.value] =
      parentValue === null || currentPreference.overridden_schedule
        ? currentPreference.schedule_offset
        : parentValue.schedule_offset;
  }

  // Overridden subject initializing values.
  if (!parentValue || currentPreference.overridden_subject) {
    formValue.subject.value = new EditorContent({
      format: currentPreference.subject_format,
      content: currentPreference.subject,
    });
  } else {
    formValue.subject.value = new EditorContent({
      format: parentValue.subject_format,
      content: parentValue.subject,
    });
  }

  // Overridden body initializing values.
  if (!parentValue || currentPreference.overridden_body) {
    formValue.body.value = new EditorContent({
      format: currentPreference.body_format,
      content: currentPreference.body,
    });
  } else {
    formValue.body.value = new EditorContent({
      format: parentValue.body_format,
      content: parentValue.body,
    });
  }

  return formValue;
}

export default {
  components: {
    Uniform,
    FormCheckbox,
    FormField,
    FormRow,
    FormText,
    Editor,
    ButtonGroup,
    Button,
    Cancel,
    FormRadioGroup,
    Radio,
    RadioNumberInput,
    FormRadioWithInput,
    FormSelect,
    ForceDeliveryChannels,
    Checkbox,
  },

  props: {
    resolverClassName: {
      type: String,
      required: true,
    },

    contextId: {
      type: Number,
      required: true,
    },

    parentValue: {
      type: Object,
      validator: validatePreferenceProp(),
      default: () => null,
    },

    preference: {
      type: Object,
      validator: validatePreferenceProp(['body_content', 'subject_content']),
      default: getDefaultNotificationPreference({
        body_content: '',
        subject_content: '',
      }),
    },

    validScheduleTypes: {
      type: Array,
      required: true,
    },

    availableRecipients: {
      type: Array,
      required: true,
      validator: validateAvailableRecipientsProp(),
    },

    defaultDeliveryChannels: {
      type: Array,
      required: true,
      validator: validateDefaultDeliveryChannelsProp(),
    },
  },

  data() {
    return {
      customisation: {
        body: this.preference.overridden_body || Boolean(!this.parentValue),
        subject:
          this.preference.overridden_subject || Boolean(!this.parentValue),
        schedule:
          this.preference.overridden_schedule || Boolean(!this.parentValue),
        recipient:
          this.preference.overridden_recipient || Boolean(!this.parentValue),
        enabled:
          this.preference.overridden_enabled || Boolean(!this.parentValue),
        forcedDeliveryChannels:
          this.preference.overridden_forced_delivery_channels ||
          Boolean(!this.parentValue),
      },
      errors: null,
      formInitialValues: createFormValues(this.preference, this.parentValue),
      scheduleTypes: SCHEDULE_TYPES,
    };
  },

  computed: {
    /**
     * Whether the field title had been disabled or not.
     * @return {boolean}
     */
    disableTitleField() {
      if (!this.parentValue) {
        return false;
      }

      return this.parentValue.title && this.parentValue.title !== '';
    },

    /**
     * Whether we need to require notification subject field or not.
     * @return {Boolean}
     */
    requiredSubject() {
      if (!this.parentValue) {
        return true;
      }

      return !this.parentValue.subject || this.parentValue.subject === '';
    },

    /**
     * Whether we need to require the notification body field or not.
     * @return {Boolean}
     */
    requiredBody() {
      if (!this.parentValue) {
        return true;
      }

      return !this.parentValue.body || this.parentValue.body === '';
    },

    /**
     * @return {Boolean}
     */
    showCustomCheckBoxes() {
      return !!this.parentValue;
    },

    /**
     * @return {Boolean}
     */
    showScheduleOnEvent() {
      return this.validScheduleTypes.indexOf(SCHEDULE_TYPES.ON_EVENT) >= 0;
    },

    /**
     * @return {Boolean}
     */
    showScheduleBeforeEvent() {
      return this.validScheduleTypes.indexOf(SCHEDULE_TYPES.BEFORE_EVENT) >= 0;
    },

    /**
     * @return {Boolean}
     */
    showScheduleAfterEvent() {
      return this.validScheduleTypes.indexOf(SCHEDULE_TYPES.AFTER_EVENT) >= 0;
    },

    recipientOptions() {
      const options = this.availableRecipients.map(recipient => {
        return { id: recipient.class_name, label: recipient.name };
      });

      return this.preference.recipient
        ? options
        : [
            {
              id: null,
              label: this.$str(
                'create_notification_select_placeholder',
                'totara_notification'
              ),
            },
          ].concat(options);
    },
  },

  watch: {
    customisation: {
      deep: true,
      handler(toggle) {
        if (!this.showCustomCheckBoxes || !this.$refs.preferenceForm) {
          return;
        }

        if (!this.parentValue) {
          throw new Error(
            'Cannot toggle the customisation when the parent ' +
              'preference is not passed down to the form'
          );
        }

        const { preferenceForm } = this.$refs;

        if (!toggle.subject) {
          preferenceForm.update(
            ['subject', 'value'],
            new EditorContent({
              content: this.parentValue.subject,
              format: this.parentValue.subject_format
                ? this.parentValue.subject_format
                : this.formInitialValues.subject.value.format,
            })
          );
        }

        if (!toggle.body) {
          preferenceForm.update(
            ['body', 'value'],
            new EditorContent({
              content: this.parentValue.body,
              format: this.parentValue.body_format
                ? this.parentValue.body_format
                : this.formInitialValues.body.value.format,
            })
          );
        }

        if (!toggle.forcedDeliveryChannels) {
          preferenceForm.update(
            ['forced_delivery_channels', 'value'],
            this.parentValue.forced_delivery_channels || []
          );
        }
      },
    },
  },

  methods: {
    /**
     * @param {EditorContent} content
     * @return {String}
     */
    validateBodyEditor(content) {
      if (!this.requiredBody) {
        return '';
      }

      return !content || content.isEmpty ? this.$str('required', 'core') : '';
    },

    validateSubjectEditor(content) {
      if (!this.requiredSubject) {
        return '';
      }

      return !content || content.isEmpty ? this.$str('required', 'core') : '';
    },

    /**
     *
     * @param {Object} formValue
     */
    async submitForm(formValue) {
      if (!this.errors) {
        this.errors = null;
      }

      const {
        data: { result },
      } = await this.$apollo.mutate({
        mutation: validateNotificationPreferenceInput,
        variables: {
          title: formValue.title.value || '',
          subject: formValue.subject.value.getContent() || '',
          body: formValue.body.value.getContent() || '',
          schedule_type: formValue.schedule_type.value || '',
          schedule_offset:
            formValue.schedule_offset[formValue.schedule_type.value] || 0,
        },
      });

      if (result.length) {
        this.errors = {};
        result.forEach(({ field_name, error_message }) => {
          this.errors[field_name] = {
            value: error_message,
          };
        });

        return;
      }

      // NOTE: Do NOT pass the preference extended context variables to the form, it has
      // nothing to do with the form's values, and it MUST be controlled by the page.
      const parameters = {
        title: formValue.title.value,
        subject: !this.customisation.subject
          ? null
          : formValue.subject.value.getContent(),
        subject_format: !this.customisation.subject
          ? null
          : formValue.subject.value.format,
        body: !this.customisation.body
          ? null
          : formValue.body.value.getContent(),
        body_format: !this.customisation.body
          ? null
          : formValue.body.value.format,
        schedule_type: !this.customisation.schedule
          ? null
          : formValue.schedule_type.value,
        schedule_offset: !this.customisation.schedule
          ? null
          : formValue.schedule_offset[formValue.schedule_type.value],
        recipient: formValue.recipient,
        enabled: !this.customisation.enabled ? null : formValue.enabled.value,
        forced_delivery_channels: !this.customisation.forcedDeliveryChannels
          ? null
          : formValue.forced_delivery_channels.value,
      };

      this.$emit('submit', parameters);
    },

    /**
     * Update method to reset the error on the body field if there is any.
     *
     * @param {*}        data
     * @param {Function} callback
     */
    updateBodyEditor(data, callback) {
      if (this.errors && this.errors.body) {
        this.errors.body.value = '';
      }

      callback(data);
    },

    /**
     * Update method to reset the error on the schedule_type field if there are any.
     *
     * @param {*}        data
     * @param {Function} callback
     */
    updateSchedule(data, callback = null) {
      if (this.errors && this.errors.schedule_type) {
        this.errors.schedule_type.value = '';
      }
      if (callback) {
        callback(data);
      }
    },

    /**
     * Update method to reset the error on the subject field if there is any.
     *
     * @param {*}        data
     * @param {Function} callback
     */
    updateSubjectEditor(data, callback) {
      if (this.errors && this.errors.subject) {
        this.errors.subject.value = '';
      }

      callback(data);
    },

    /**
     * Resetting the field title's value.
     */
    resetErrorFieldTitle() {
      if (this.errors && this.errors.title) {
        this.errors.title.value = '';
      }
    },
  },
};
</script>

<lang-strings>
{
  "totara_notification": [
    "create_notification_select_placeholder",
    "notification_body_label",
    "notification_title_label",
    "notification_schedule_label",
    "notification_status_label",
    "notification_subject_label",
    "enable_custom_schedule",
    "enable_custom_subject",
    "enable_custom_body",
    "schedule_form_label_after_event",
    "schedule_form_label_before_event",
    "schedule_form_label_on_event",
    "schedule_label_before_event",
    "schedule_label_after_event",
    "enable_custom_body",
    "enable_custom_recipient",
    "enable_custom_status",
    "recipient",
    "delivery_label",
    "enable_custom_forced_delivery_channels",
    "override"
  ],
  "totara_core": [
    "save",
    "enabled"
  ],
  "core": [
    "required"
  ]
}
</lang-strings>

<style lang="scss">
.tui-notificationPreferenceForm {
  &__buttonGroup {
    display: flex;
    justify-content: flex-end;
  }

  &__bodyEditor {
    height: 400px;
  }
}
</style>
