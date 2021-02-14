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
    :initial-values="formInitialValues"
    input-width="full"
    class="tui-notificationPreferenceForm"
    @submit="submitForm"
  >
    <FormRow
      v-slot="{ id }"
      :label="$str('notification_title_label', 'totara_notification')"
      :required="
        !parentPreference ||
          !parentPreference.title ||
          parentPreference.title === ''
      "
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
      />
    </FormRow>

    <FormRow
      v-slot="{ id }"
      :label="$str('notification_subject_label', 'totara_notification')"
      :required="requiredSubject"
    >
      <!-- We are only requiring the field subject if the parent does not have one -->
      <FormText
        :id="id"
        :name="['subject', 'value']"
        :validations="v => (requiredSubject ? [v.required()] : [])"
      />
    </FormRow>
    <FormRow
      v-slot="{ id }"
      :required="requiredBody"
      :label="$str('notification_body_label', 'totara_notification')"
    >
      <FormField
        v-slot="{ value, update, name }"
        :name="['body', 'value']"
        :validate="validateEditor"
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
          variant="standard"
          @input="update"
        />
      </FormField>
    </FormRow>

    <FormRow>
      <ButtonGroup>
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
import { Uniform, FormField, FormText } from 'tui/components/uniform';
import FormRow from 'tui/components/form/FormRow';
import { Format } from 'tui/editor';
import Editor from 'tui/components/editor/Editor';
import ButtonGroup from 'tui/components/buttons/ButtonGroup';
import Button from 'tui/components/buttons/Button';
import Cancel from 'tui/components/buttons/Cancel';
import {
  validatePreferenceProp,
  getDefaultNotificationPreference,
} from '../../internal/notification_preference';
import { EditorContent } from 'tui/editor';

/**
 *
 * @param {Object} currentPreference
 * @param {Object|NULL} parentPreference
 *
 * @return {Object}
 */
function createFormValues(currentPreference, parentPreference) {
  const formValue = {
    subject: {
      value:
        !parentPreference || currentPreference.overridden_subject
          ? currentPreference.subject
          : parentPreference.subject,
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
        !parentPreference || !parentPreference.title
          ? currentPreference.title
          : parentPreference.title,
      type: 'text',
    },
  };

  if (!parentPreference || currentPreference.overridden_body) {
    formValue.body.value = new EditorContent({
      format: currentPreference.body_format
        ? currentPreference.body_format
        : Format.MOODLE,
      content: currentPreference.body,
    });
  } else {
    formValue.body.value = new EditorContent({
      format: parentPreference.body_format
        ? parentPreference.body_format
        : Format.MOODLE,
      content: parentPreference.body,
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
  },

  props: {
    contextId: {
      type: Number,
      required: true,
    },

    parentPreference: {
      type: Object,
      validator: validatePreferenceProp(),
      default() {
        return null;
      },
    },

    preference: {
      type: Object,
      validator: validatePreferenceProp(),
      default: getDefaultNotificationPreference(),
    },
  },

  data() {
    return {
      formInitialValues: createFormValues(
        this.preference,
        this.parentPreference
      ),
    };
  },

  computed: {
    /**
     * Whether the field title had been disabled or not.
     * @return {boolean}
     */
    disableTitleField() {
      if (!this.parentPreference) {
        return false;
      }

      return this.parentPreference.title && this.parentPreference.title !== '';
    },

    /**
     * Whether we need to require notification subject field or not.
     * @return {Boolean}
     */
    requiredSubject() {
      if (!this.parentPreference) {
        return true;
      }

      return (
        !this.parentPreference.subject || this.parentPreference.subject === ''
      );
    },

    /**
     * Whether we need to require the notification body field or not.
     * @return {Boolean}
     */
    requiredBody() {
      if (!this.parentPreference) {
        return true;
      }

      return !this.parentPreference.body || this.parentPreference.body === '';
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
     * @param {Object} currentValues
     */
    submitForm(currentValues) {
      const fields = Object.keys(currentValues);
      let parameters = {};

      fields.forEach(field => {
        let value = currentValues[field].value
          ? currentValues[field].value
          : null;
        if (value instanceof EditorContent) {
          // Storing the body_format instance.
          parameters['body_format'] = value.format;

          // Then convert the value to a string content.
          value = value.getContent();
        }

        parameters[field] = value;
      });

      this.$emit('submit', parameters);
    },
  },
};
</script>

<lang-strings>
  {
    "totara_notification": [
      "notification_body_label",
      "notification_title_label",
      "notification_subject_label"
    ],
    "totara_core": [
      "save"
    ],
    "core": [
      "required"
    ]
  }
</lang-strings>
