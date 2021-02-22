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

  @author Jaron Steenson <jaron.steenson@totaralearning.com>
  @module mod_perform
-->
<template>
  <div class="tui-participantFormResponseDisplay">
    <template v-if="responseData && Array.isArray(responseData)">
      <div v-for="(dataLine, i) in responseData" :key="i">
        {{ dataLine }}
      </div>
    </template>
    <template v-else-if="responseData">
      {{ responseData }}
    </template>
    <NoResponseSubmitted v-else />
  </div>
</template>

<script>
import NoResponseSubmitted from 'mod_perform/components/element/participant_form/NoResponseSubmitted';

export default {
  components: { NoResponseSubmitted },
  props: {
    data: Array,
  },

  computed: {
    /**
     * Return correct data type (array/string) for value
     *
     * @return {String||Array}
     */
    responseData() {
      if (!this.data || !this.data[0]) {
        return false;
      }

      if (this.data.length > 1) {
        return this.data;
      } else {
        let result;
        try {
          result = JSON.parse(this.data[0]);
        } catch (e) {
          result = this.data[0];
        }
        return result;
      }
    },
  },
};
</script>
