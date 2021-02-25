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
        @input="
          () => {
            if (errors && errors.title) errors.title.value = '';
          }
        "
      />
    </FormRow>

    <FormRow
      :label="$str('notification_subject_label', 'totara_notification')"
      :required="requiredSubject"
    >
      <template v-slot="{ id }">
        <ToggleSwitch
          v-if="showCustomCheckBoxes"
          :aria-label="$str('enable_custom_subject', 'totara_notification')"
          :value="customisation.subject"
          :toggle-only="true"
          @input="customisation.subject = $event"
        />

        <!-- We are only requiring the field subject if the parent does not have one -->
        <FormText
          :id="id"
          :name="['subject', 'value']"
          :validations="v => (requiredSubject ? [v.required()] : [])"
          :disabled="showCustomCheckBoxes && !customisation.subject"
          @input="
            () => {
              if (errors && errors.subject) errors.subject.value = '';
            }
          "
        />
      </template>
    </FormRow>

    <FormRow
      :label="$str('notification_schedule_label', 'totara_notification')"
      :required="!parentValue"
    >
      <template v-slot="{ id }">
        <ToggleSwitch
          v-if="showCustomCheckBoxes"
          :aria-label="$str('enable_custom_schedule', 'totara_notification')"
          :value="customisation.schedule"
          :toggle-only="true"
          @input="customisation.schedule = $event"
        />
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

    <FormRow
      :required="requiredBody"
      :label="$str('notification_body_label', 'totara_notification')"
    >
      <template v-slot="{ id }">
        <ToggleSwitch
          v-if="showCustomCheckBoxes"
          :aria-label="$str('enable_custom_body', 'totara_notification')"
          :value="customisation.body"
          :toggle-only="true"
          @input="customisation.body = $event"
        />
        <FormField
          v-slot="{ value, update }"
          :name="['body', 'value']"
          :validate="validateEditor"
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
            class="tui-notificationPreferenceForm__editor"
            variant="standard"
            @input="updateEditor($event, update)"
          />
        </FormField>
      </template>
    </FormRow>

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
  FormField,
  FormRadioGroup,
  FormRadioWithInput,
  FormText,
  Uniform,
} from 'tui/components/uniform';
import FormRow from 'tui/components/form/FormRow';
import Radio from 'tui/components/form/Radio';
import RadioNumberInput from 'tui/components/form/RadioNumberInput';
import { EditorContent, Format } from 'tui/editor';
import Editor from 'tui/components/editor/Editor';
import ButtonGroup from 'tui/components/buttons/ButtonGroup';
import Button from 'tui/components/buttons/Button';
import Cancel from 'tui/components/buttons/Cancel';
import {
  getDefaultNotificationPreference,
  SCHEDULE_TYPES,
  validatePreferenceProp,
} from '../../internal/notification_preference';
import ToggleSwitch from 'tui/components/toggle/ToggleSwitch';

// GraphQL queries
import validateNotificationPreferenceInput from 'totara_notification/graphql/validate_notification_preference_input';

/**
 *
 * @param {Object} currentPreference
 * @param {Object|NULL} parentValue
 *
 * @return {Object}
 */
function createFormValues(currentPreference, parentValue) {
  const formValue = {
    subject: {
      value:
        !parentValue || currentPreference.overridden_subject
          ? currentPreference.subject
          : parentValue.subject,
      type: 'text',
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
  };

  // Set the default offset values (it involves a little bit of looking at schedule_type).
  // We only want to change the active type away from defaults.
  if (formValue.schedule_type.value !== SCHEDULE_TYPES.ON_EVENT) {
    formValue.schedule_offset[formValue.schedule_type.value] =
      parentValue === null || currentPreference.overridden_schedule
        ? currentPreference.schedule_offset
        : parentValue.schedule_offset;
  }

  if (!parentValue || currentPreference.overridden_body) {
    formValue.body.value = new EditorContent({
      format: currentPreference.body_format
        ? currentPreference.body_format
        : Format.MOODLE,
      content: currentPreference.body,
    });
  } else {
    formValue.body.value = new EditorContent({
      format: parentValue.body_format ? parentValue.body_format : Format.MOODLE,
      content: parentValue.body,
    });
  }

  return formValue;
}

export default {
  components: {
    Uniform,
    FormField,
    FormRow,
    FormText,
    Editor,
    ButtonGroup,
    Button,
    Cancel,
    ToggleSwitch,
    FormRadioGroup,
    Radio,
    RadioNumberInput,
    FormRadioWithInput,
  },

  props: {
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
      validator: validatePreferenceProp(),
      default: getDefaultNotificationPreference(),
    },

    validScheduleTypes: {
      type: Array,
      required: true,
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
          preferenceForm.update(['subject', 'value'], this.parentValue.subject);
        }

        if (!toggle.body) {
          preferenceForm.update(
            ['body', 'value'],
            new EditorContent({
              content: this.parentValue.body,
              format: this.parentValue.body_format
                ? this.parentValue.body_format
                : Format.MOODLE,
            })
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
    validateEditor(content) {
      if (!this.requiredBody) {
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
          subject: formValue.subject.value || '',
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

      const parameters = {
        subject: !this.customisation.subject ? null : formValue.subject.value,
        title: formValue.title.value,
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
      };

      this.$emit('submit', parameters);
    },

    /**
     * Update method to reset the error on the body field if there is any.
     *
     * @param {*}        data
     * @param {Function} callback
     */
    updateEditor(data, callback) {
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
  },
};
</script>

<lang-strings>
{
  "totara_notification": [
    "notification_body_label",
    "notification_title_label",
    "notification_schedule_label",
    "notification_subject_label",
    "enable_custom_schedule",
    "enable_custom_subject",
    "enable_custom_body",
    "schedule_form_label_after_event",
    "schedule_form_label_before_event",
    "schedule_form_label_on_event",
    "schedule_label_before_event",
    "schedule_label_after_event"
  ],
  "totara_core": [
    "save"
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
}
</style>
