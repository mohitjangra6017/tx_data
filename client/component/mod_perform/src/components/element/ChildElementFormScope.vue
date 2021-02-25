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
-->

<template>
  <FormScope
    :path="['childElementResponses', childElementIndex]"
    :process="childElementProcessor"
  >
    <slot />
  </FormScope>
</template>

<script>
import FormScope from 'tui/components/reform/FormScope';

export default {
  name: 'ChildElementFormScope',

  components: {
    FormScope,
  },

  props: {
    childElementIndex: Number,
    element: Object,
  },

  methods: {
    childElementProcessor(value) {
      value.element_id = this.element.id;
      return value;
    },
  },
};
</script>
