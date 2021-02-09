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
    :initial-values="formInitialValues"
    input-width="full"
  >
    <FormRow
      v-slot="{ id }"
      :label="$str('notification_title_label', 'totara_notification')"
      :required="true"
    >
      <FormText
        :id="id"
        :name="['title', 'value']"
        :disabled="disableTitleField"
      />
    </FormRow>

    <FormRow
      v-slot="{ id }"
      :label="$str('notification_subject_label', 'totara_notification')"
      :required="true"
    >
      <FormText
        :id="id"
        :name="['subject', 'value']"
      />
    </FormRow>
    <FormRow
      v-slot="{ id }"
      :required="true"
      :label="
        $str('notification_body_label', 'totara_notification')
      "
    >
      <FormField
        v-slot="{value, update}"
        :name="['subject ', 'value']"
        char-length="full"
      >
        <Editor
          :id="id"
          :value="value"
          :default-format="bodyEditorFormat"
          :context-id="contextId"
          :usage-identifier="{
            component: 'totara_notification',
            area: 'notification_body'
          }"
          variant="standard"
          @input="update"
        />
      </FormField>
    </FormRow>

  </Uniform>
</template>

<script>
import { Uniform, FormField, FormText } from "tui/components/uniform";
import FormRow from "tui/components/form/FormRow";
import {Format} from 'tui/editor';
import Editor from "tui/components/editor/Editor";

/**
 * Validator function for the notification preference props.
 *
 * @param {Array} extraKeys
 * @return {Function}
 */
function validatePreferenceProp(extraKeys = []) {
  const keys = extraKeys.concat([
    'subject', 'body',
    'bodyFormat', 'title'
  ]);

  return (prop) => {
    const result = keys.filter(
      (key) => {
        return !(key in prop);
      }
    );

    return 0 === result.length;
  }
}

export default {
  components: {
    Uniform,
    FormField,
    FormRow,
    FormText,
    Editor
  },

  props: {
    contextId: {
      type: Number,
      required: true
    },

    parentPreference: {
      type: Object,
      validator: validatePreferenceProp(['id']),
      default() {
        return {
          subject: null,
          body: null,
          title: null,
          bodyFormat: Format.MOODLE
        };
      }
    },

    preference: {
      type: Object,
      validator:validatePreferenceProp(),
      default() {
        // We are default to FORMAT_MOODLE for the body
        return {
          subject: '',
          body: '',
          title: null,
          bodyFormat: Format.MOODLE
        };
      }
    }
  },

  data() {
    return {
      formInitialValues: {
        subject: {
          value: this.preference.subject ? this.preference.subject : this.parentPreference.subject,
          type: 'text'
        },
        body: {
          value: this.preference.body ? this.preference.body : this.parentPreference.body,
          type: 'text'
        },
        title: {
          value: this.preference.title ? this.preference.title : this.parentPreference.title,
          type: 'text'
        }
      }
    }
  },

  computed: {
    /**
     * Whether the field title had been disabled or not.
     * @return {boolean}
     */
    disableTitleField() {
      return !!this.parentPreference.title;
    },

    /**
     *
     * @return {Number}
     */
    bodyEditorFormat() {
      if (this.preference.bodyFormat) {
        return this.preference.bodyFormat
      }

      if (this.parentPreference.bodyFormat) {
        return this.parentPreference.bodyFormat;
      }

      return Format.MOODLE;
    }
  }
};
</script>

<lang-strings>
  {
    "totara_notification": [
      "notification_body_label",
      "notification_title_label",
      "notification_subject_label"
    ]
  }
</lang-strings>