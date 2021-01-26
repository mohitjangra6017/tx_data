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

  @author Kevin Hottinger <kevin.hottinger@totaralearning.com>
  @module mod_perform
-->
<template>
  <div
    class="tui-performElementParticipantHeader"
    :class="{
      'tui-performElementParticipantHeader--printedToDo': hasPrintedToDoIcon,
    }"
  >
    <h3 :id="id" class="tui-performElementParticipantHeader__title">
      <PrintedTodoIcon
        v-if="hasPrintedToDoIcon"
        class="tui-performElementParticipantHeader__printedTodo"
      />
      {{ title }}
      <RequiredOptionalIndicator v-if="isRespondable" :is-required="required" />
    </h3>
  </div>
</template>

<script>
import PrintedTodoIcon from 'tui/components/icons/PrintedTodo';
import RequiredOptionalIndicator from 'mod_perform/components/user_activities/RequiredOptionalIndicator';

export default {
  components: {
    PrintedTodoIcon,
    RequiredOptionalIndicator,
  },

  props: {
    hasPrintedToDoIcon: Boolean,
    id: String,
    isRespondable: Boolean,
    required: Boolean,
    title: String,
  },
};
</script>

<style lang="scss">
.tui-performElementParticipantHeader {
  &__title {
    margin: 0;
    @include tui-font-heading-x-small();
  }

  &--printedToDo {
    display: inline;
  }
}
</style>
