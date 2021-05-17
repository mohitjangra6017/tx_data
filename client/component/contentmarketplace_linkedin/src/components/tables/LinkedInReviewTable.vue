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
  <div class="tui-linkedInReviewTable">
    <SelectTable
      :border-bottom-hidden="true"
      :data="items"
      :hover-off="true"
      :no-label-offset="true"
      row-label-key="title"
      :selected-highlight-off="true"
      :value="selectedItems"
      @input="$emit('update', $event)"
    >
      <template v-slot:row="{ row, checked }">
        <Cell size="12">
          <Card :data="row" :small="true" :unselected="!checked">
            <template
              v-if="selectedItemCategories[row.id] && checked"
              v-slot:side-content
            >
              <!-- Display currently assigned category -->
              <div class="tui-linkedInReviewTable__category">
                <div>
                  {{ $str('category_label', 'contentmarketplace_linkedin') }}
                </div>
                <div class="tui-linkedInReviewTable__category-current">
                  {{ selectedItemCategories[row.id].label }}
                </div>

                <!-- Add popover triggered by edit icon -->
                <CategoryPopover
                  :category-options="categoryOptions"
                  :course-id="row.id"
                  :current-category="selectedItemCategories[row.id].id"
                  :disabled="!checked"
                  @change-course-category="
                    $emit('change-course-category', $event)
                  "
                />
              </div>
            </template>
          </Card>
        </Cell>
      </template>
    </SelectTable>
  </div>
</template>

<script>
// Components
import Card from 'contentmarketplace_linkedin/components/tables/LinkedInTableCard';
import CategoryPopover from 'contentmarketplace_linkedin/components/categories/LinkedInCategoryPopover';
import Cell from 'tui/components/datatable/Cell';
import SelectTable from 'tui/components/datatable/SelectTable';

export default {
  components: {
    Card,
    CategoryPopover,
    Cell,
    SelectTable,
  },

  props: {
    // Available category options
    categoryOptions: Array,
    // Course data
    items: {
      type: Array,
      required: true,
    },
    // Selected category data for each item
    selectedItemCategories: {
      type: Object,
      required: true,
    },
    // List of selected item ID's
    selectedItems: {
      type: Array,
      required: true,
    },
  },
};
</script>

<lang-strings>
  {
    "contentmarketplace_linkedin": [
      "category_label"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-linkedInReviewTable {
  &__category {
    display: flex;

    &-current {
      @include tui-font-heavy();
      margin-left: var(--gap-1);
      color: var(--color-text);
    }
  }
}
</style>
