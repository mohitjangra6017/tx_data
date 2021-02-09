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

  @author Qingyang.liu <qingyang.liu@totaralearning.com>
  @module totara_notification
-->

<template>
  <Uniform
    v-slot="{ getSubmitting }"
    class="tui-notificationCreationForm"
    @submit="submitForm"
  >
    <div
      aria-hidden="true"
      class="tui-notificationCreationForm__title-required"
    >
      <span class="tui-notificationCreationForm__title-requiredStar">
        *
      </span>
      {{ $str('required', 'totara_notification') }}
    </div>
    <FormRow label="Name" required>
      <FormText name="Name" char-length="30" />
    </FormRow>

    <FormRow label="Subject" required>
      <FormText name="Subject" char-length="50" />
    </FormRow>

    <FormRow label="Body">
      <FormTextarea name="Body" char-length="50" />
    </FormRow>

    <FormRowActionButtons
      class="tui-notificationCreationForm__buttons"
      :submitting="getSubmitting()"
      @cancel="$emit('cancel', false)"
    />
  </Uniform>
</template>

<script>
import {
  Uniform,
  FormRow,
  FormText,
  FormTextarea,
} from 'tui/components/uniform';
import FormRowActionButtons from 'tui/components/form/FormRowActionButtons';

export default {
  components: {
    FormText,
    Uniform,
    FormRow,
    FormTextarea,
    FormRowActionButtons,
  },

  methods: {
    cancel() {},
    submitForm(values) {
      console.log(values, 'ddddd');
    },
  },
};
</script>
<lang-strings>
{
  "totara_notification": [
    "required"
  ]
}
</lang-strings>

<style lang="scss">
.tui-notificationCreationForm {
  &__title {
    &-required {
      @include tui-font-heavy;
    }

    &-requiredStar {
      color: var(--color-prompt-alert);
    }
  }

  &__buttons {
    .tui-formBtnGroup {
      justify-content: flex-end;
    }
  }
}
</style>
