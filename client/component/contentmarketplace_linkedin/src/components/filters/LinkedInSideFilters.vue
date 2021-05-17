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

  @author Kevin Hottinger <kevin.hottinger@totaralearning.com>
  @package contentmarketplace_linkedin
-->

<template>
  <FilterSidePanel
    v-model="value"
    :skip-content-id="contentId"
    :title="$str('filters_title', 'contentmarketplace_linkedin')"
  >
    <!-- Search filter -->
    <SearchFilter
      v-model="value.search"
      :label="$str('a11y_search_filter', 'contentmarketplace_linkedin')"
      :placeholder="
        $str('search_filter_placeholder', 'contentmarketplace_linkedin')
      "
    />

    <div>
      <!-- Subject tree filter -->
      <Tree
        v-model="openBranches.subjects"
        :header-level="4"
        :separator="true"
        :tree-data="filters.subject"
      >
        <template v-slot:content="{ content, label }">
          <MultiSelectFilter
            v-model="value.subjects"
            :hidden-title="true"
            :options="content.items"
            :title="label"
          />
        </template>
      </Tree>

      <!-- Type tree filter -->
      <Tree
        v-model="openBranches.type"
        :header-level="4"
        :separator="true"
        :tree-data="filters.type"
      >
        <template v-slot:content="{ content, label }">
          <MultiSelectFilter
            v-model="value.type"
            :hidden-title="true"
            :options="content.items"
            :title="label"
          />
        </template>
      </Tree>

      <!-- Time tree filter -->
      <Tree
        v-model="openBranches.time"
        :header-level="4"
        :separator="true"
        :tree-data="filters.time"
      >
        <template v-slot:content="{ content, label }">
          <MultiSelectFilter
            v-model="value.time"
            :hidden-title="true"
            :options="content.items"
            :title="label"
          />
        </template>
      </Tree>

      <!-- Software tree filter -->
      <Tree
        v-model="openBranches.software"
        :header-level="4"
        :separator="true"
        :tree-data="filters.software"
      >
        <template v-slot:content="{ content, label }">
          <MultiSelectFilter
            v-model="value.software"
            :hidden-title="true"
            :options="content.items"
            :title="label"
          />
        </template>
      </Tree>
    </div>
  </FilterSidePanel>
</template>

<script>
import FilterSidePanel from 'tui/components/filters/FilterSidePanel';
import MultiSelectFilter from 'tui/components/filters/MultiSelectFilter';
import SearchFilter from 'tui/components/filters/SearchFilter';
import Tree from 'tui/components/tree/Tree';

export default {
  components: {
    FilterSidePanel,
    MultiSelectFilter,
    SearchFilter,
    Tree,
  },

  props: {
    contentId: String,
    filters: {
      type: Object,
      required: true,
    },
    openBranches: Object,
    value: Object,
  },
};
</script>

<lang-strings>
  {
    "contentmarketplace_linkedin": [
      "a11y_search_filter",
      "filters_title",
      "search_filter_placeholder"
    ]
  }
</lang-strings>
