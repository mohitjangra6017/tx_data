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

  @author Kunle Odusan <kunle.odusan@totaralearning.com>
  @module performelement_competency_rating
-->

<template>
  <div class="tui-competencyRatingAdminView">
    <Form input-width="full" :vertical="true">
      <FormRow>
        <RadioGroup v-model="tempVal" :aria-label="title">
          <Radio
            v-for="item in options"
            :key="item.name"
            :name="item.name"
            :value="item.value"
          >
            {{ item.value }}
          </Radio>
        </RadioGroup>
      </FormRow>
    </Form>
  </div>
</template>

<script>
import Form from 'tui/components/form/Form';
import FormRow from 'tui/components/form/FormRow';
import Radio from 'tui/components/form/Radio';
import RadioGroup from 'tui/components/form/RadioGroup';

export default {
  components: {
    Form,
    FormRow,
    Radio,
    RadioGroup,
  },

  inheritAttrs: false,

  props: {
    title: String,
  },

  data() {
    return {
      tempVal: false,

      /**
       * Sample selection options.
       *
       * @return {Array}
       */
      options: [
        {
          value: this.$str('rating_value', 'totara_competency', 1),
          name: '1',
        },
        {
          value: this.$str('rating_value', 'totara_competency', 2),
          name: '2',
        },
        {
          value: this.$str('rating_value', 'totara_competency', 3),
          name: '3',
        },
      ],
    };
  },
};
</script>

<lang-strings>
  {
    "totara_competency": [
      "rating_value"
    ]
  }
</lang-strings>
