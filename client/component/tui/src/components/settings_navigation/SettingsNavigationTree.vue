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
  @package tui
-->

<template>
  <Tree
    class="tui-settingsNavigationTree"
    :tree-data="treeData"
    :label-type="labelType"
    :no-padding="noPadding"
    :value="value"
    @input="$emit('input', $event)"
  >
    <template
      v-if="$scopedSlots['custom-label']"
      v-slot:custom-label="{ label, linkUrl, topLevel }"
    >
      <slot
        name="custom-label"
        :label="label"
        :link-url="linkUrl"
        :top-level="topLevel"
      />
    </template>
    <template
      v-else-if="isDropdown"
      v-slot:custom-label="{ label, linkUrl, topLevel }"
    >
      <div
        class="tui-settingsNavigationTree__popover-contentsLabel"
        :class="{
          'tui-settingsNavigationTree__popover-topBranch': topLevel,
          'tui-settingsNavigationTree__spacing': !topLevel,
        }"
      >
        <DropdownItem v-if="linkUrl" :href="linkUrl" no-padding>
          {{ label }}
        </DropdownItem>
        <template v-else>
          {{ label }}
        </template>
      </div>
    </template>
  </Tree>
</template>

<script>
import DropdownItem from 'tui/components/dropdown/DropdownItem';
import Tree from 'tui/components/tree/Tree';

export default {
  components: {
    DropdownItem,
    Tree,
  },

  props: {
    // String (null), link or button for node label
    labelType: String,
    noPadding: Boolean,
    isDropdown: Boolean,
    treeData: {
      type: Array,
      required: true,
    },
    // List of expanded node ID's
    value: {
      required: true,
      type: Array,
    },
  },
};
</script>

<style lang="scss">
.tui-settingsNavigationTree {
  hyphens: none;

  &__spacing {
    padding: var(--gap-2) 0;
  }

  &__popover {
    &-topBranch {
      padding-bottom: var(--gap-2);
      font-weight: bold;
    }

    &-contentsItem {
      margin-top: var(--gap-2);
      white-space: normal;
    }

    &-contentsLabel {
      width: 100%;
    }
  }
}
</style>
