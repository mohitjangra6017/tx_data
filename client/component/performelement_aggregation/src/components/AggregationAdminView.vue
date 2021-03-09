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

  @module performelement_aggregation
-->

<template>
  <div class="tui-aggregationAdminView">
    <div v-if="hasExcludedValues">
      {{
        $str(
          'admin_view_blurb_with_exclusions',
          'performelement_aggregation',
          excludedValuesCsv
        )
      }}
    </div>
    <div v-else class="tui-aggregationAdminView__blurb">
      {{ $str('admin_view_blurb', 'performelement_aggregation') }}
    </div>
    <div>
      <template v-for="preview in aggregationTypesPreview">
        {{ preview }}
      </template>
    </div>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    data: Object,
  },
  computed: {
    hasExcludedValues() {
      return this.excludedValues.length > 0;
    },
    excludedValuesCsv() {
      return this.excludedValues.join(', ');
    },
    excludedValues() {
      if (!this.data.excludedValues) {
        return [];
      }

      // Remove any empty entries.
      return this.data.excludedValues.filter(
        value => value !== null && value.trim() !== ''
      );
    },
    aggregationTypesPreview() {
      return ['Average'].map(
        name =>
          `${name}: {${this.$str(
            'calculated_value_preview_placeholder',
            'performelement_aggregation'
          )}}`
      );
    },
  },
};
</script>

<lang-strings>
  {
    "performelement_aggregation": [
      "admin_view_blurb",
      "admin_view_blurb_with_exclusions",
      "calculated_value_preview_placeholder"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-aggregationAdminView {
  > * + * {
    margin-top: var(--gap-8);
  }
}
</style>
